<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Category;
use App\Models\State;
use Illuminate\Support\Str;

class SeoService
{
    public function generateTitle($page, $data = null): string
    {
        $year = date('Y');
        $ny   = date('Y') + 1;
        return match($page) {
            'home'        => "JobOne.in – Sarkari Naukri {$year}, Govt Jobs Today, Daily Alert Updates",
            'post'        => $this->generatePostTitle($data),
            'category'    => "{$data->name} Recruitment {$year} – Apply Online, Dates & Details | JobOne",
            'state'       => "{$data->name} Sarkari Naukri {$year} – Govt Vacancy & Updates | JobOne",
            'jobs'        => "Sarkari Naukri {$year} – Today's Govt Job Openings Across India | JobOne",
            'admit-cards' => "Admit Card {$year} – Download Hall Ticket & Call Letter Today | JobOne",
            'results'     => "Sarkari Result {$year} – Latest Merit List, Cut-Off & Score Card | JobOne",
            'answer-keys' => "Answer Key {$year} – Official Keys, Objection & Cut-Off Analysis | JobOne",
            'syllabus'    => "Exam Syllabus {$year} – PDF Pattern, Marking Scheme & Tips | JobOne",
            'scholarship' => "Scholarship {$year} – Central & State Schemes for Students | JobOne",
            'blogs'       => "Exam Preparation Blog – Tips, Strategy & Career Guidance | JobOne",
            'search'      => "Search \"" . Str::limit($data, 40) . "\" – Govt Jobs, Results & More | JobOne",
            default       => 'JobOne.in – Your Daily Sarkari Naukri & Result Portal'
        };
    }

    private function generatePostTitle($post): string
    {
        $suffix = ' | JobOne';

        // Use custom meta title if set — avoid double branding
        if ($post->meta_title) {
            $clean = $post->meta_title;
            // Strip any existing JobOne suffix to avoid duplication
            $clean = preg_replace('/\s*\|\s*JobOne(\.in)?$/i', '', $clean);
            return Str::limit($clean, 55, '') . $suffix;
        }

        // Extract components from title using regex
        $title = $post->title;
        $year = $this->extractYear($title);
        $org = $this->extractOrganization($title);
        $role = $this->extractRole($title);
        $total = $this->extractTotalPosts($title);

        // Generate smart title based on post type
        $smartTitle = match($post->type) {
            'job' => $this->generateJobTitle($org, $role, $year, $total),
            'admit_card' => $this->generateAdmitCardTitle($org, $role, $year),
            'result' => $this->generateResultTitle($org, $role, $year),
            'syllabus' => $this->generateSyllabusTitle($org, $role, $year),
            'answer_key' => $this->generateAnswerKeyTitle($org, $role, $year),
            'blog' => Str::limit($title, 50, ''),
            default => Str::limit($title, 50, '')
        };

        return $smartTitle . $suffix;
    }

    private function extractYear($title): string
    {
        if (preg_match('/\b(20\d{2})\b/', $title, $matches)) {
            return $matches[1];
        }
        return date('Y');
    }

    private function extractOrganization($title): string
    {
        // Common organization patterns
        $patterns = [
            '/^([A-Z]{2,6})\s/',  // SSC, UPSC, IBPS, etc.
            '/^([A-Z][a-z]+\s[A-Z][a-z]+)\s/',  // Indian Army, State Bank, etc.
            '/^([A-Z][a-z]+)\s/',  // Railway, Banking, etc.
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $title, $matches)) {
                return trim($matches[1]);
            }
        }

        // Fallback: first 2-3 words
        $words = explode(' ', $title);
        return implode(' ', array_slice($words, 0, min(2, count($words))));
    }

    private function extractRole($title): string
    {
        // Common role patterns
        $roles = [
            'Constable', 'SI', 'Inspector', 'Officer', 'Clerk', 'PO', 'SO',
            'Assistant', 'Manager', 'Engineer', 'Teacher', 'Professor',
            'Group D', 'Group C', 'Group B', 'Group A', 'NTPC', 'ALP',
            'Technician', 'Stenographer', 'MTS', 'LDC', 'UDC', 'DEO'
        ];

        foreach ($roles as $role) {
            if (stripos($title, $role) !== false) {
                return $role;
            }
        }

        return 'Posts';
    }

    private function extractTotalPosts($title): ?string
    {
        // Match patterns like "150 Posts", "1000 Vacancies", etc.
        if (preg_match('/\b(\d+)\s*(Posts?|Vacancies|Vacancy)\b/i', $title, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function generateJobTitle($org, $role, $year, $total): string
    {
        if ($total) {
            $title = "{$org} Recruitment {$year} – {$total} {$role} Posts, Apply Online";
        } else {
            $title = "{$org} {$role} Recruitment {$year} – Eligibility & Apply";
        }
        return Str::limit($title, 55, '');
    }

    private function generateAdmitCardTitle($org, $role, $year): string
    {
        $title = "{$org} {$role} Admit Card {$year} – Download Hall Ticket";
        return Str::limit($title, 55, '');
    }

    private function generateResultTitle($org, $role, $year): string
    {
        $title = "{$org} {$role} Result {$year} – Merit List & Cut-Off";
        return Str::limit($title, 55, '');
    }

    private function generateSyllabusTitle($org, $role, $year): string
    {
        $title = "{$org} {$role} Syllabus {$year} – PDF & Exam Pattern";
        return Str::limit($title, 55, '');
    }

    private function generateAnswerKeyTitle($org, $role, $year): string
    {
        $title = "{$org} {$role} Answer Key {$year} – Set-Wise PDF";
        return Str::limit($title, 55, '');
    }

    public function generateDescription($page, $data = null): string
    {
        $year = date('Y');
        return match($page) {
            'home' => "JobOne.in – India's fastest-updated sarkari naukri portal. Get today's govt job vacancies, exam results, admit cards, answer keys & syllabus for SSC, UPSC, Railways, Banking, State PSC, Defence & Police. Updated daily {$year}.",
            'post' => $this->generatePostDescription($data),
            'category' => "Latest {$data->name} sarkari naukri {$year} – new vacancies, eligibility, salary, dates & how to apply. Only on JobOne.in.",
            'state' => "All {$data->name} govt jobs {$year} in one place – State PSC, Police, Teaching, Banking & Central govt vacancies. Updated daily on JobOne.",
            'jobs' => "Today's sarkari naukri vacancies {$year} – SSC, UPSC, Railways, Banking, Defence, Police & PSC. Apply online with dates, eligibility & direct links on JobOne.",
            'admit-cards' => "Download admit card & hall ticket {$year} – SSC, UPSC, Railways, RRB, Banking & State exams. Direct official links on JobOne.",
            'results' => "Sarkari result {$year} declared today – SSC, UPSC, Railways, Banking merit list, cut-off marks & score cards. Check instantly on JobOne.",
            'answer-keys' => "Official answer key {$year} – SSC, UPSC, Railways, Banking preliminary & mains. Download set-wise PDF & check expected cut-off on JobOne.",
            'syllabus' => "Exam syllabus & pattern {$year} PDF download – SSC, UPSC, Railways, Banking & State PSC. Subject-wise marks distribution on JobOne.",
            'blogs' => "Exam preparation blog by JobOne – proven study plans, time management tips & topper strategies for SSC, UPSC, Banking & Railway exams {$year}.",
            'search' => "Results for \"{$data}\" – matching govt jobs, admit cards, results & exam updates on JobOne.in.",
            default => "JobOne.in – India's daily-updated sarkari naukri, exam results & admit card portal."
        };
    }

    private function generatePostDescription($post): string
    {
        // Priority: meta_description > short_description > content excerpt
        if (!empty($post->meta_description)) {
            $description = $post->meta_description;
        } elseif (!empty($post->short_description)) {
            $description = $post->short_description;
        } else {
            // Extract text from content, remove HTML tags
            $description = strip_tags($post->content);
            // Remove extra whitespace
            $description = preg_replace('/\s+/', ' ', $description);
        }
        
        // Ensure description is not empty
        if (empty(trim($description))) {
            $description = "Latest {$post->category->name} notification for {$post->title}. Check details, eligibility, application process, and important dates.";
        }
        
        return Str::limit(trim($description), 155, '...');
    }

    public function generateKeywords($page, $data = null): string
    {
        $year = date('Y');
        $ny   = date('Y') + 1;
        $base = "sarkari naukri {$year}, govt jobs {$year}, government jobs india, jobone";

        return match($page) {
            'home'        => "sarkari naukri, govt jobs today, government jobs {$year}, SSC recruitment, UPSC vacancy, railway jobs, banking jobs, state PSC, defence recruitment, police bharti, {$base}",
            'post'        => $this->generatePostKeywords($data),
            'category'    => "{$data->name} recruitment {$year}, {$data->name} vacancy, {$data->name} bharti, {$base}",
            'state'       => "{$data->name} govt jobs {$year}, {$data->name} sarkari naukri, {$data->name} vacancy, {$data->name} recruitment, {$base}",
            'jobs'        => "govt jobs today, sarkari naukri {$year}, latest vacancy, apply online, {$base}",
            'admit-cards' => "admit card {$year}, hall ticket download, call letter, exam hall ticket, {$base}",
            'results'     => "sarkari result {$year}, exam result today, merit list, cut off marks, {$base}",
            'answer-keys' => "answer key {$year}, official answer key, set-wise PDF, expected cut off, {$base}",
            'syllabus'    => "exam syllabus {$year}, syllabus PDF download, exam pattern, marking scheme, {$base}",
            'scholarship' => "scholarship {$year}, govt scholarship, student scholarship scheme, {$base}",
            'blogs'       => "exam tips, study plan, preparation strategy, topper interview, {$base}",
            default       => $base
        };
    }

    private function generatePostKeywords($post): string
    {
        $keywords = $post->meta_keywords ?? '';
        if (empty($keywords)) {
            $keywords = implode(', ', [
                $post->category->name ?? '',
                $post->state->name ?? '',
                $post->type,
                'government jobs',
                '2026'
            ]);
        }
        return $keywords;
    }

    public function generateCanonical($url): string
    {
        return rtrim($url, '/');
    }

    public function generateOgTags($page, $data = null): array
    {
        // Use post image if available, otherwise default OG image
        $ogImage = asset('images/og-image.jpg');
        if ($page === 'post' && $data && !empty($data->image)) {
            $ogImage = $data->image;
        }
        
        return [
            'og:title' => $this->generateTitle($page, $data),
            'og:description' => $this->generateDescription($page, $data),
            'og:url' => $this->generateCanonical(url()->current()),
            'og:type' => $page === 'post' ? 'article' : 'website',
            'og:site_name' => 'JobOne.in',
            'og:locale' => 'en_IN',
            'og:image' => $ogImage,
        ];
    }

    public function generateTwitterTags($page, $data = null): array
    {
        return [
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $this->generateTitle($page, $data),
            'twitter:description' => $this->generateDescription($page, $data),
            'twitter:image' => asset('images/og-image.jpg'),
        ];
    }

    // Convenience methods for controllers
    public function generateHomeSeo(): array
    {
        // Check if domain is filtered to Karnataka
        $isKarnatakaDomain = config('app.domain_state_slug') === 'karnataka';
        
        if ($isKarnatakaDomain) {
            return [
                'title' => 'Karnataka Government Jobs 2026 - Latest Govt Jobs in Karnataka | KarnatakaJob.Online',
                'description' => 'Find latest Karnataka government jobs 2026, Karnataka govt jobs, Karnataka job alerts, Karnataka sarkari naukri, govt jobs in Karnataka for freshers, Karnataka recruitment 2026. Your trusted Karnataka job portal.',
                'keywords' => 'karnataka government jobs, karnataka govt jobs 2026, latest govt jobs in karnataka, karnataka job alerts, karnataka sarkari naukri, govt jobs in karnataka for freshers, karnataka recruitment 2026, karnataka job portal, karnataka sarkari result, karnataka job vacancy',
                'canonical' => url('/'),
                'og_title' => 'Karnataka Government Jobs 2026 - Latest Govt Jobs in Karnataka',
                'og_description' => 'Find latest Karnataka government jobs 2026, Karnataka govt jobs, Karnataka job alerts, Karnataka sarkari naukri, govt jobs in Karnataka for freshers.',
                'og_image' => asset('images/og-image.jpg'),
                'og_url' => url('/'),
            ];
        }
        
        $year = date('Y');
        return [
            'title'          => "JobOne.in – Sarkari Naukri {$year}, Govt Jobs Today, Daily Alert Updates",
            'description'    => "JobOne.in – India's fastest-updated sarkari naukri portal. Get today's govt job vacancies, exam results, admit cards, answer keys & syllabus for SSC, UPSC, Railways, Banking, State PSC, Defence & Police. Updated daily {$year}.",
            'keywords'       => $this->generateKeywords('home'),
            'canonical'      => url('/'),
            'og_title'       => "JobOne – Sarkari Naukri & Govt Jobs Updated Daily {$year}",
            'og_description' => "India's fastest sarkari naukri portal — govt job vacancies, results, admit cards & more. Updated every day on JobOne.in.",
            'og_image'       => asset('images/og-image.jpg'),
            'og_url'         => url('/'),
        ];
    }

    public function generateListingSeo($type): array
    {
        $year = date('Y');
        $typeNames = [
            'job'         => 'Sarkari Naukri',
            'admit_card'  => 'Admit Cards',
            'result'      => 'Sarkari Results',
            'answer_key'  => 'Answer Keys',
            'syllabus'    => 'Exam Syllabus',
            'blog'        => 'Career Blog',
            'scholarship' => 'Scholarships',
        ];
        $typeDesc = [
            'job'         => "Today's sarkari naukri vacancies {$year} – SSC, UPSC, Railways, Banking, Defence, Police & PSC. Apply online with dates, eligibility & direct links on JobOne.",
            'admit_card'  => "Download admit card & hall ticket {$year} – SSC, UPSC, Railways, RRB, Banking & State exams. Direct official links on JobOne.",
            'result'      => "Sarkari result {$year} declared today – SSC, UPSC, Railways, Banking merit list, cut-off marks & score cards. Check instantly on JobOne.",
            'answer_key'  => "Official answer key {$year} – SSC, UPSC, Railways, Banking preliminary & mains. Download set-wise PDF & check expected cut-off on JobOne.",
            'syllabus'    => "Exam syllabus & pattern {$year} PDF download – SSC, UPSC, Railways, Banking & State PSC. Subject-wise marks distribution on JobOne.",
            'blog'        => "Exam preparation blog by JobOne – proven study plans, time management tips & topper strategies for SSC, UPSC, Banking & Railway exams {$year}.",
            'scholarship' => "Government scholarship schemes {$year} – central & state scholarships for students. Eligibility, last dates & direct apply links on JobOne.",
        ];

        $typeName = $typeNames[$type] ?? 'Posts';
        $desc     = $typeDesc[$type]  ?? "Latest {$typeName} {$year} – updated daily on JobOne.in";

        return [
            'title'          => "Latest {$typeName} {$year} – Updated Today | JobOne",
            'description'    => $desc,
            'keywords'       => $this->generateKeywords($type === 'job' ? 'jobs' : str_replace('_', '-', $type)),
            'canonical'      => url()->current(),
            'og_title'       => "Latest {$typeName} {$year} – Updated Today | JobOne",
            'og_description' => $desc,
            'og_image'       => asset('images/og-image.jpg'),
            'og_url'         => url()->current(),
        ];
    }

    public function generatePostSeo(Post $post): array
    {
        // Ensure we have a valid OG image URL (absolute URL)
        $ogImage = asset('images/og-image.jpg');
        if (!empty($post->image)) {
            // If post image is relative, make it absolute
            $ogImage = str_starts_with($post->image, 'http') 
                ? $post->image 
                : asset($post->image);
        }
        
        return [
            'title' => $this->generatePostTitle($post),
            'description' => $this->generatePostDescription($post),
            'keywords' => $this->generatePostKeywords($post),
            'canonical' => url()->current(),
            'og_title' => $this->generatePostTitle($post),
            'og_description' => $this->generatePostDescription($post),
            'og_image' => $ogImage,
            'og_url' => url()->current(),
        ];
    }

    public function generateCategorySeo(Category $category): array
    {
        $year = date('Y');
        $title = $category->meta_title ?: "Latest {$category->name} Jobs {$year} | JobOne.in";
        $desc = $category->meta_description ?: "Browse latest {$category->name} job notifications, admit cards, results, and exam updates. Apply for government jobs in {$category->name} sector.";
        $keywords = $category->meta_keywords ?: "{$category->name} jobs, {$category->name} recruitment, government jobs, sarkari naukri {$year}";

        return [
            'title' => $title,
            'description' => $desc,
            'keywords' => $keywords,
            'canonical' => url()->current(),
            'og_title' => $title,
            'og_description' => $desc,
            'og_image' => asset('images/og-image.jpg'),
            'og_url' => url()->current(),
        ];
    }

    public function generateStateSeo(State $state): array
    {
        $year = date('Y');
        $title = $state->meta_title ?: "{$state->name} Government Jobs {$year} | JobOne.in";
        $desc = $state->meta_description ?: "Latest government job notifications in {$state->name}. Find SSC, UPSC, Railways, Banking, and State PSC jobs in {$state->name}.";
        $keywords = $state->meta_keywords ?: "{$state->name} jobs, {$state->name} government jobs, {$state->name} sarkari naukri {$year}";

        return [
            'title' => $title,
            'description' => $desc,
            'keywords' => $keywords,
            'canonical' => url()->current(),
            'og_title' => $title,
            'og_description' => $desc,
            'og_image' => asset('images/og-image.jpg'),
            'og_url' => url()->current(),
        ];
    }
}
