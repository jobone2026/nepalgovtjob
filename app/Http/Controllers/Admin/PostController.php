<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SubmitToIndexNow;
use App\Models\Category;
use App\Models\Post;
use App\Models\State;
use App\Services\CacheInvalidationService;
use App\Services\NotificationService;
use App\Services\OgImageService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category', 'state');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published' ? 1 : 0);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by state
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(50)->withQueryString();
        $categories = Category::all();
        $states = State::all();

        return view('admin.posts.index', compact('posts', 'categories', 'states'));
    }

    public function create()
    {
        $categories = Category::all();
        $states = State::all();

        return view('admin.posts.create', compact('categories', 'states'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title'              => 'required|string|max:255',
            'type'               => 'required|in:job,admit_card,syllabus,result,answer_key,blog,scholarship',
            'category_id'        => 'required|exists:categories,id',
            'state_id'           => 'nullable|exists:states,id',
            'content'            => 'required|string',
            'organization'       => 'nullable|string|max:255',
            'total_posts'        => 'nullable|integer|min:1',
            'salary'             => 'nullable|string|max:255',
            'last_date'          => 'nullable|date',
            'notification_date'  => 'nullable|date',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'online_form'        => 'nullable|url|max:500',
            'final_result'       => 'nullable|url|max:500',
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:160',
            'meta_keywords'      => 'nullable|string',
            'tags'               => 'nullable|array',
            'tags.*'             => 'string',
            'education'          => 'nullable|array',
            'education.*'        => 'string',
            'is_featured'        => 'boolean',
            'is_upcoming'        => 'boolean',
            'is_published'       => 'boolean',
            // New SEO structured fields
            'age_min'            => 'nullable|integer|min:0',
            'age_max_gen'        => 'nullable|integer|min:0',  // nullable — non-job posts don't have age
            'age_as_on_date'     => 'nullable|date',
            'age_relaxation_note'=> 'nullable|string|max:500',
            'salary_min'         => 'nullable|integer|min:0',
            'salary_max'         => 'nullable|integer|min:0',
            'salary_type'        => 'nullable|in:salary,stipend,consolidated,pay_scale',
            'pay_scale_level'    => 'nullable|string|max:255',
            'fee_general'        => 'nullable|integer|min:0',
            'fee_obc'            => 'nullable|integer|min:0',
            'fee_sc_st'          => 'nullable|integer|min:0',
            'fee_women'          => 'nullable|integer|min:0',
            'fee_ph'             => 'nullable|integer|min:0',
            'fee_payment_mode'   => 'nullable|string|max:255',
            'recruitment_year'   => 'nullable|integer|min:1900|max:2100',
        ];

        // Custom Education & Notification Date validation removed per request

        // Duplicate detection check
        $orgSlug = \Illuminate\Support\Str::slug($request->input('organization'));
        $lastDateInput = $request->input('last_date');
        if ($orgSlug && $lastDateInput) {
            $duplicate = \App\Models\Post::where('organization_slug', $orgSlug)
                ->whereDate('last_date', $lastDateInput)
                ->first();
            
            if ($duplicate) {
                return redirect()->route('admin.posts.edit', $duplicate->id)
                    ->with('warning', 'A post for this organization and last date already exists. You have been redirected to edit it instead of creating a duplicate.');
            }
        }

        $validated = $request->validate($rules);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['admin_id'] = auth('admin')->id();
        // Preserve existing short_description if set, otherwise generate from content
        $validated['short_description'] = $validated['short_description']
            ?? trim(substr(strip_tags($validated['content'] ?? ''), 0, 160));
        $validated['important_links'] = null;
        $validated['tags'] = $request->has('tags') ? ($validated['tags'] ?? []) : [];
        $validated['education'] = $request->has('education') ? ($validated['education'] ?? []) : [];
        $validated['is_upcoming'] = $request->has('is_upcoming') ? 1 : 0;
        // Set salary_type default if not provided
        if (empty($validated['salary_type'])) {
            $validated['salary_type'] = 'salary';
        }
        // Set direct_apply based on online_form
        $validated['direct_apply'] = !empty($validated['online_form']);
        // Auto-set apply_url from online_form if not separately provided
        if (!isset($validated['apply_url']) && !empty($validated['online_form'])) {
            $validated['apply_url'] = $validated['online_form'];
        }

        $post = Post::create($validated);
        
        // Generate OG image if not provided (only if GD extension is available)
        if (empty($post->image) && extension_loaded('gd')) {
            try {
                $ogImageService = app(OgImageService::class);
                $ogImageUrl = $ogImageService->generateImage($post->title, $post->slug);
                $post->update(['image' => $ogImageUrl]);
            } catch (\Exception $e) {
                // Log error but don't fail the post creation
                Log::warning('Failed to generate OG image: ' . $e->getMessage());
            }
        }
        
        // Invalidate cache
        try {
            app(CacheInvalidationService::class)->invalidatePostCache($post);
        } catch (\Exception $e) {
            // Log error but don't fail the post creation
            Log::warning('Failed to invalidate cache: ' . $e->getMessage());
        }
        
        // Submit to IndexNow and send notifications only for published posts
        if ($post->is_published) {
            try {
                $url = route('posts.show', [$post->type, $post->slug]);
                SubmitToIndexNow::dispatch($url)->delay(now()->addSeconds(30));

                // Send notifications (Telegram, WhatsApp, Web Push)
                app(NotificationService::class)->sendNewPostNotifications($post);
            } catch (\Exception $e) {
                // Log error but don't fail the post creation
                Log::warning('Failed to submit to IndexNow or send notifications: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $states = State::all();

        return view('admin.posts.edit', compact('post', 'categories', 'states'));
    }

    public function update(Request $request, Post $post)
    {
        $rules = [
            'title'              => 'required|string|max:255',
            'type'               => 'required|in:job,admit_card,syllabus,result,answer_key,blog,scholarship',
            'category_id'        => 'required|exists:categories,id',
            'state_id'           => 'nullable|exists:states,id',
            'content'            => 'required|string',
            'organization'       => 'nullable|string|max:255',
            'total_posts'        => 'nullable|integer|min:1',
            'salary'             => 'nullable|string|max:255',
            'last_date'          => 'nullable|date',
            'notification_date'  => 'nullable|date',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'online_form'        => 'nullable|url|max:500',
            'final_result'       => 'nullable|url|max:500',
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:160',
            'meta_keywords'      => 'nullable|string',
            'tags'               => 'nullable|array',
            'tags.*'             => 'string',
            'education'          => 'nullable|array',
            'education.*'        => 'string',
            'is_featured'        => 'boolean',
            'is_upcoming'        => 'boolean',
            'is_published'       => 'boolean',
            // New SEO structured fields
            'age_min'            => 'nullable|integer|min:0',
            'age_max_gen'        => 'nullable|integer|min:0',  // nullable — non-job posts don't have age
            'age_as_on_date'     => 'nullable|date',
            'age_relaxation_note'=> 'nullable|string|max:500',
            'salary_min'         => 'nullable|integer|min:0',
            'salary_max'         => 'nullable|integer|min:0',
            'salary_type'        => 'nullable|in:salary,stipend,consolidated,pay_scale',
            'pay_scale_level'    => 'nullable|string|max:255',
            'fee_general'        => 'nullable|integer|min:0',
            'fee_obc'            => 'nullable|integer|min:0',
            'fee_sc_st'          => 'nullable|integer|min:0',
            'fee_women'          => 'nullable|integer|min:0',
            'fee_ph'             => 'nullable|integer|min:0',
            'fee_payment_mode'   => 'nullable|string|max:255',
            'recruitment_year'   => 'nullable|integer|min:1900|max:2100',
        ];

        // Custom Education & Notification Date validation removed per request

        $validated = $request->validate($rules);

        $validated['slug'] = Str::slug($validated['title']);
        // Do NOT overwrite short_description — preserve the AI-generated value from the API
        unset($validated['short_description']);
        $validated['important_links'] = $post->important_links; // Keep existing value
        $validated['tags'] = $request->has('tags') ? ($validated['tags'] ?? []) : [];
        $validated['education'] = $request->has('education') ? ($validated['education'] ?? []) : [];
        $validated['is_upcoming'] = $request->has('is_upcoming') ? 1 : 0;
        // Set salary_type default if not provided
        if (empty($validated['salary_type'])) {
            $validated['salary_type'] = 'salary';
        }
        // Sync direct_apply with online_form
        $validated['direct_apply'] = !empty($validated['online_form']);
        if (!isset($validated['apply_url']) && !empty($validated['online_form'])) {
            $validated['apply_url'] = $validated['online_form'];
        }

        // ── Auto Date-Extension Detection ─────────────────────────────────────────
        // If admin changes last_date to a future date that differs from the current one
        // → automatically mark is_date_extended = true and send Telegram alert.
        $oldLastDate    = $post->last_date ? $post->last_date->toDateString() : null;
        $newLastDate    = !empty($validated['last_date'])
            ? \Carbon\Carbon::parse($validated['last_date'])->toDateString()
            : null;
        $dateWasExtended = false;

        if ($newLastDate && $oldLastDate && $newLastDate !== $oldLastDate
            && \Carbon\Carbon::parse($newLastDate)->isFuture()) {
            // New date is in the future and different — treat as extension
            $validated['is_date_extended'] = true;
            $dateWasExtended = true;
        }
        // If admin explicitly unchecks is_date_extended, respect that
        if ($request->has('is_date_extended') && !$request->boolean('is_date_extended')) {
            $validated['is_date_extended'] = false;
            $dateWasExtended = false;
        }

        $wasPublished = $post->is_published;

        $post->update($validated);

        // ── Send Telegram alert for date extension ────────────────────────────────
        if ($dateWasExtended && $post->is_published) {
            try {
                $telegram = app(TelegramService::class);
                if ($telegram->isConfigured()) {
                    $msg = TelegramService::buildDateExtendedMessage($post);
                    $telegram->sendMessage($msg);
                    Log::info("Date-extension Telegram alert sent for post #{$post->id}");
                }
            } catch (\Exception $e) {
                Log::warning('Telegram date-extension alert failed: ' . $e->getMessage());
            }
        }
        
        // Regenerate OG image if title changed and no custom image (only if GD extension is available)
        if ($post->wasChanged('title') && empty($post->image) && extension_loaded('gd')) {
            try {
                $ogImageService = app(OgImageService::class);
                $ogImageService->deleteImage($post->slug);
                $ogImageUrl = $ogImageService->generateImage($post->title, $post->slug);
                $post->update(['image' => $ogImageUrl]);
            } catch (\Exception $e) {
                // Log error but don't fail the post update
                Log::warning('Failed to generate OG image: ' . $e->getMessage());
            }
        }
        
        // Invalidate cache
        try {
            app(CacheInvalidationService::class)->invalidatePostCache($post);
        } catch (\Exception $e) {
            // Log error but don't fail the post update
            Log::warning('Failed to invalidate cache: ' . $e->getMessage());
        }
        
        // Submit to IndexNow if published or status changed to published
        if ($post->is_published && (!$wasPublished || $post->wasChanged('title') || $post->wasChanged('content'))) {
            try {
                $url = route('posts.show', [$post->type, $post->slug]);
                SubmitToIndexNow::dispatch($url)->delay(now()->addSeconds(30));
            } catch (\Exception $e) {
                Log::warning('Failed to submit to IndexNow: ' . $e->getMessage());
            }
        }

        // Send notifications if newly published OR manual resend requested
        if ($post->is_published && (!$wasPublished || $request->has('resend_notifications'))) {
            try {
                app(NotificationService::class)->sendNewPostNotifications($post);
            } catch (\Exception $e) {
                Log::warning('Failed to send notifications: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        try {
            // Invalidate cache before deletion
            app(CacheInvalidationService::class)->invalidatePostCache($post);
        } catch (\Exception $e) {
            Log::warning('Failed to invalidate cache: ' . $e->getMessage());
        }
        
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully');
    }

    public function togglePublished(Post $post)
    {
        $wasPublished = $post->is_published;
        $post->update(['is_published' => !$post->is_published]);
        
        // Invalidate cache
        try {
            app(CacheInvalidationService::class)->invalidatePostCache($post);
        } catch (\Exception $e) {
            Log::warning('Failed to invalidate cache: ' . $e->getMessage());
        }
        
        // Submit to IndexNow if newly published
        if (!$wasPublished && $post->is_published) {
            try {
                $url = route('posts.show', [$post->type, $post->slug]);
                SubmitToIndexNow::dispatch($url)->delay(now()->addSeconds(30));
                
                // Send notifications
                app(NotificationService::class)->sendNewPostNotifications($post);
            } catch (\Exception $e) {
                // Log error but don't fail the toggle
                Log::warning('Failed to submit to IndexNow or send notifications: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true]);
    }

    public function toggleFeatured(Post $post)
    {
        $post->update(['is_featured' => !$post->is_featured]);
        
        // Invalidate cache
        try {
            app(CacheInvalidationService::class)->invalidatePostCache($post);
        } catch (\Exception $e) {
            Log::warning('Failed to invalidate cache: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'posts' => 'required|array|min:1',
            'posts.*' => 'exists:posts,id',
        ]);

        $posts = Post::whereIn('id', $validated['posts'])->get();

        foreach ($posts as $post) {
            if ($validated['action'] === 'publish') {
                $post->update(['is_published' => true]);
            } elseif ($validated['action'] === 'unpublish') {
                $post->update(['is_published' => false]);
            } elseif ($validated['action'] === 'delete') {
                $post->delete();
            }
            
            // Invalidate cache for each post
            try {
                app(CacheInvalidationService::class)->invalidatePostCache($post);
            } catch (\Exception $e) {
                Log::warning('Failed to invalidate cache: ' . $e->getMessage());
            }
        }

        $message = match($validated['action']) {
            'publish' => 'Posts published successfully',
            'unpublish' => 'Posts unpublished successfully',
            'delete' => 'Posts deleted successfully',
        };

        return redirect()->route('admin.posts.index')
            ->with('success', $message);
    }

    public function loadMore(Request $request)
    {
        $page = $request->input('page', 2);
        $query = Post::with('category', 'state');

        // Apply same filters as index
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published' ? 1 : 0);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(50, ['*'], 'page', $page);

        return view('admin.posts.load-more', compact('posts'));
    }
}
