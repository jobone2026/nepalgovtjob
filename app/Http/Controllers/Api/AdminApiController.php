<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\State;
use App\Models\Admin;
use App\Services\CacheInvalidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AdminApiController extends Controller
{
    protected $cacheInvalidation;

    public function __construct(CacheInvalidationService $cacheInvalidation)
    {
        $this->cacheInvalidation = $cacheInvalidation;
    }
    /**
     * Login and get API token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Generate API token
        $token = $admin->createToken('admin-api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ],
                'token' => $token
            ]
        ]);
    }

    /**
     * Get all posts
     */
    public function getPosts(Request $request)
    {
        $posts = Post::with(['category', 'state', 'admin'])
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->state_id, fn($q) => $q->where('state_id', $request->state_id))
            ->when($request->is_published !== null, fn($q) => $q->where('is_published', $request->is_published))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get single post
     */
    public function getPost($id)
    {
        $post = Post::with(['category', 'state', 'admin'])->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    /**
     * Create new post
     */
    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'type'              => 'required|in:job,admit_card,result,answer_key,syllabus,blog,scholarship',
            'category_id'       => 'required|exists:categories,id',
            'state_id'          => 'nullable|exists:states,id',
            'short_description' => 'required|string',
            'content'           => 'required|string',
            'organization'      => 'nullable|string|max:255',
            // Dates
            'notification_date' => 'nullable|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date',
            'last_date'         => 'nullable|date',
            // Vacancy & Salary
            'total_posts'       => 'nullable|integer|min:1',
            'salary'            => 'nullable|string|max:255',
            // Links
            'online_form'       => 'nullable|url|max:500',
            'final_result'      => 'nullable|url|max:500',
            'important_links'   => 'nullable|json',
            // Classification
            'tags'              => 'nullable|array',
            'tags.*'            => 'string',
            'education'         => 'nullable|array',
            'education.*'       => 'string',
            // Flags
            'is_featured'       => 'boolean',
            'is_upcoming'       => 'boolean',
            'is_published'      => 'boolean',
            // SEO
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $post = new Post();
        $post->title              = $request->title;
        $post->slug               = Str::slug($request->title) . '-' . Str::random(13);
        $post->type               = $request->type;
        $post->category_id        = $request->category_id;
        $post->state_id           = $request->state_id;
        $post->short_description  = $request->short_description;
        $post->content            = $request->content;
        $post->organization       = $request->organization;
        // Dates
        $post->notification_date  = $request->notification_date;
        $post->start_date         = $request->start_date;
        $post->end_date           = $request->end_date;
        $post->last_date          = $request->last_date;
        // Vacancy & Salary
        $post->total_posts        = $request->total_posts;
        $post->salary             = $request->salary;
        // Links
        $post->online_form        = $request->online_form;
        $post->final_result       = $request->final_result;
        $post->important_links    = $request->important_links;
        // Classification
        $post->tags               = $request->tags ?? [];
        $post->education          = $request->education ?? [];
        // Flags
        $post->is_featured        = $request->is_featured ?? false;
        $post->is_upcoming        = $request->is_upcoming ?? false;
        $post->is_published       = $request->is_published ?? true;
        $post->admin_id           = $request->user()->id;
        // SEO — auto-generate if not provided
        $post->meta_title         = $request->meta_title ?: Str::limit($request->title, 60);
        $post->meta_description   = $request->meta_description ?: Str::limit($request->short_description, 160);
        $post->meta_keywords      = $request->meta_keywords;

        $post->save();

        // Invalidate cache
        $this->cacheInvalidation->invalidatePostCache($post);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post->load(['category', 'state', 'admin'])
        ], 201);
    }

    /**
     * Update post
     */
    public function updatePost(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'             => 'string|max:255',
            'type'              => 'in:job,admit_card,result,answer_key,syllabus,blog,scholarship',
            'category_id'       => 'exists:categories,id',
            'state_id'          => 'nullable|exists:states,id',
            'short_description' => 'string',
            'content'           => 'string',
            'organization'      => 'nullable|string|max:255',
            // Dates
            'notification_date' => 'nullable|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date',
            'last_date'         => 'nullable|date',
            // Vacancy & Salary
            'total_posts'       => 'nullable|integer|min:1',
            'salary'            => 'nullable|string|max:255',
            // Links
            'online_form'       => 'nullable|url|max:500',
            'final_result'      => 'nullable|url|max:500',
            'important_links'   => 'nullable|json',
            // Classification
            'tags'              => 'nullable|array',
            'tags.*'            => 'string',
            'education'         => 'nullable|array',
            'education.*'       => 'string',
            // Flags
            'is_featured'       => 'boolean',
            'is_upcoming'       => 'boolean',
            'is_published'      => 'boolean',
            // SEO
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        if ($request->has('title')) {
            $post->title        = $request->title;
            $post->meta_title   = $request->meta_title ?: Str::limit($request->title, 60);
        }
        if ($request->has('type'))              $post->type              = $request->type;
        if ($request->has('category_id'))       $post->category_id       = $request->category_id;
        if ($request->has('state_id'))          $post->state_id          = $request->state_id;
        if ($request->has('organization'))      $post->organization      = $request->organization;
        if ($request->has('short_description')) {
            $post->short_description = $request->short_description;
            $post->meta_description  = $request->meta_description ?: Str::limit($request->short_description, 160);
        }
        if ($request->has('content'))           $post->content           = $request->content;
        // Dates
        if ($request->has('notification_date')) $post->notification_date = $request->notification_date;
        if ($request->has('start_date'))        $post->start_date        = $request->start_date;
        if ($request->has('end_date'))          $post->end_date          = $request->end_date;
        if ($request->has('last_date'))         $post->last_date         = $request->last_date;
        // Vacancy & Salary
        if ($request->has('total_posts'))       $post->total_posts       = $request->total_posts;
        if ($request->has('salary'))            $post->salary            = $request->salary;
        // Links
        if ($request->has('online_form'))       $post->online_form       = $request->online_form;
        if ($request->has('final_result'))      $post->final_result      = $request->final_result;
        if ($request->has('important_links'))   $post->important_links   = $request->important_links;
        // Classification
        if ($request->has('tags'))              $post->tags              = $request->tags;
        if ($request->has('education'))         $post->education         = $request->education;
        // Flags
        if ($request->has('is_featured'))       $post->is_featured       = $request->is_featured;
        if ($request->has('is_upcoming'))       $post->is_upcoming       = $request->is_upcoming;
        if ($request->has('is_published'))      $post->is_published      = $request->is_published;
        // SEO
        if ($request->has('meta_keywords'))     $post->meta_keywords     = $request->meta_keywords;

        $post->save();

        // Invalidate cache
        $this->cacheInvalidation->invalidatePostCache($post);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post->load(['category', 'state', 'admin'])
        ]);
    }

    /**
     * Delete post
     */
    public function deletePost($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        // Invalidate cache before deleting
        $this->cacheInvalidation->invalidatePostCache($post);

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get all states
     */
    public function getStates()
    {
        $states = State::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $states
        ]);
    }

    /**
     * Logout (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
