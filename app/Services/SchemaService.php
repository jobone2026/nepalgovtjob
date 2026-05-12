<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Category;
use App\Models\State;
use Illuminate\Support\Str;

class SchemaService
{
    public function generateJobPosting(Post $post): array
    {
        // Remove style and script tags
        $cleanContent = preg_replace('/<(style|script)\b[^>]*>.*?<\/\1>/is', '', $post->content);
        // Only strip unsafe tags, preserve formatting tags for Google Jobs description
        $cleanContent = strip_tags($cleanContent, '<p><br><ul><li><strong><b><em><i><h1><h2><h3><h4><h5><h6>');
        // If content is too long, we don't truncate because cutting HTML tags is unsafe. 
        // Job posts are usually within reasonable limits (under 100kb).
        $description = trim($cleanContent) ?: $post->title;

        // datePosted: ISO8601 of notification_date or created_at
        $datePosted = $post->notification_date ? $post->notification_date->toIso8601String() : $post->created_at->toIso8601String();
        $dateModified = $post->updated_at->toIso8601String();

        // Get state name for location
        $stateName = $post->state->name ?? 'All India';
        
        // directApply: strict boolean, but verify URL is not a bare homepage
        $applyUrl = $post->apply_url ?? $post->online_form ?? '';
        $isDirect = false;
        if (!empty($applyUrl)) {
            $parsedUrl = parse_url($applyUrl);
            $path = rtrim($parsedUrl['path'] ?? '/', '/');
            if ($path !== '' || !empty($parsedUrl['query'])) {
                $isDirect = true;
            }
        }
        $directApply = (bool)($post->direct_apply ?? $isDirect);

        // Employment type mapping
        $empType = 'FULL_TIME';
        $titleLower = strtolower($post->title ?? '');
        if (strpos($titleLower, 'apprentice') !== false || strpos($titleLower, 'trainee') !== false || ($post->salary_type === 'stipend')) {
            $empType = 'INTERN';
        } elseif (strpos($titleLower, 'contract') !== false) {
            $empType = 'CONTRACTOR';
        } elseif (strpos($titleLower, 'part time') !== false) {
            $empType = 'PART_TIME';
        } elseif (strpos($titleLower, 'volunteer') !== false) {
            $empType = 'VOLUNTEER';
        } elseif (strpos($titleLower, 'temporary') !== false) {
            $empType = 'TEMPORARY';
        }

        // Hiring Organization Official URL
        $orgUrl = url('/');
        if (is_array($post->important_links)) {
            foreach ($post->important_links as $link) {
                if (stripos($link['label'] ?? '', 'official website') !== false && !empty($link['url'])) {
                    $orgUrl = $link['url'];
                    break;
                }
            }
        }

        $schema = [
            '@context'           => 'https://schema.org',
            '@type'              => 'JobPosting',
            'title'              => $post->title,
            'description'        => $description,
            'datePosted'         => $datePosted,
            'dateModified'       => $dateModified,
            'employmentType'     => $empType,
            'directApply'        => $directApply,
            'hiringOrganization' => [
                '@type'  => 'Organization',
                'name'   => $post->organization ?: ($post->category->name ?? 'Government of India'),
                'sameAs' => $orgUrl,
                'logo'   => [
                    '@type' => 'ImageObject',
                    'url'   => asset('images/jobone-logo.png'),
                ],
            ],
        ];

        // Job Location
        if ($stateName === 'All India') {
            $schema['jobLocationType'] = 'TELECOMMUTE';
            $schema['applicantLocationRequirements'] = [
                '@type' => 'Country',
                'name' => 'India'
            ];
        } else {
            $schema['jobLocation'] = [
                '@type'   => 'Place',
                'address' => [
                    '@type'           => 'PostalAddress',
                    'streetAddress'   => $stateName,
                    'addressLocality' => $stateName,
                    'addressRegion'   => $stateName,
                    'postalCode'      => '000000',
                    'addressCountry'  => 'IN',
                ],
            ];
        }

        // Education Requirements (Google recommends specific enums)
        $eduText = strtolower(strip_tags($post->qualifications ?? '') . ' ' . implode(' ', $post->education ?? []));
        $eduMap = [];
        
        // Google-accepted credentialCategory values (lowercase, exact match required)
        if (strpos($eduText, '10th') !== false || strpos($eduText, '12th') !== false || strpos($eduText, 'high school') !== false || strpos($eduText, 'matriculation') !== false || strpos($eduText, 'sslc') !== false) {
            $eduMap[] = 'high school';
        }
        if (strpos($eduText, 'diploma') !== false || strpos($eduText, 'iti') !== false || strpos($eduText, 'polytechnic') !== false) {
            $eduMap[] = 'associate degree';
        }
        if (strpos($eduText, 'bachelor') !== false || strpos($eduText, 'b.tech') !== false || strpos($eduText, 'b.e') !== false || strpos($eduText, 'b.sc') !== false || strpos($eduText, 'b.com') !== false || strpos($eduText, 'ba ') !== false || strpos($eduText, 'graduate') !== false || strpos($eduText, 'degree') !== false || strpos($eduText, 'ug') !== false) {
            $eduMap[] = 'bachelor degree';
        }
        if (strpos($eduText, 'master') !== false || strpos($eduText, 'm.sc') !== false || strpos($eduText, 'm.tech') !== false || strpos($eduText, 'm.com') !== false || strpos($eduText, 'mba') !== false || strpos($eduText, 'post graduate') !== false || strpos($eduText, 'postgraduate') !== false || strpos($eduText, 'pg ') !== false) {
            $eduMap[] = 'postgraduate degree';
        }
        if (strpos($eduText, 'phd') !== false || strpos($eduText, 'ph.d') !== false || strpos($eduText, 'doctorate') !== false || strpos($eduText, 'doctoral') !== false) {
            $eduMap[] = 'postgraduate degree';
        }

        if (!empty($eduMap)) {
            // Google supports an array of these objects
            $eduReqsObj = [];
            $fullQuals = strip_tags($post->qualifications ?? '');
            foreach (array_unique($eduMap) as $level) {
                $eduReqsObj[] = [
                    '@type' => 'EducationalOccupationalCredential',
                    'credentialCategory' => $level,
                    'description' => $fullQuals ?: $level
                ];
            }
            // If it's just one, send the object directly, otherwise array
            $schema['educationRequirements'] = count($eduReqsObj) === 1 ? $eduReqsObj[0] : $eduReqsObj;
        }

        // validThrough: use last_date, fallback to +90 days
        if ($post->last_date) {
            $schema['validThrough'] = $post->last_date->toIso8601String();
        } else {
            $schema['validThrough'] = now()->addDays(90)->toIso8601String();
        }

        // applicationDeadline (same as validThrough for Google)
        $schema['applicationDeadline'] = $schema['validThrough'];

        // Apply URL
        if (!empty($applyUrl)) {
            $schema['applicationContact'] = [
                '@type'       => 'ContactPoint',
                'contactType' => 'Apply Online',
                'url'         => $applyUrl,
            ];
        }

        // baseSalary
        $minSal = (int) $post->salary_min;
        $maxSal = (int) $post->salary_max;
        
        // If min/max are not strictly set, try to extract from 'salary' string or default to 0
        if ($minSal === 0 && $maxSal === 0 && !empty($post->salary)) {
            preg_match_all('/\d+/', str_replace(',', '', $post->salary), $matches);
            if (!empty($matches[0])) {
                $salaries = array_map('intval', $matches[0]);
                sort($salaries);
                $minSal = $salaries[0];
                $maxSal = $salaries[count($salaries) - 1];
            }
        }

        // Ensure max >= min
        if ($maxSal < $minSal) $maxSal = $minSal;

        $schema['baseSalary'] = [
            '@type'    => 'MonetaryAmount',
            'currency' => 'INR',
            'value'    => [
                '@type'    => 'QuantitativeValue',
                'minValue' => $minSal,
                'maxValue' => $maxSal > 0 ? $maxSal : ($minSal > 0 ? $minSal : 0),
                'unitText' => 'MONTH',
            ],
        ];

        // Optional fields
        if ($post->total_posts) {
            $schema['totalJobOpenings'] = (int)$post->total_posts;
        }

        return $schema;
    }

    public function generateArticle(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->short_description,
            'datePublished' => $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => 'JobOne.in',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'JobOne.in',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/jobone-logo.png'),
                ],
            ],
        ];
    }

    public function generateBreadcrumb(array $items): array
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    public function generateWebSite(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'JobOne.in',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/search?q={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public function generateOrganization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'JobOne.in',
            'url' => url('/'),
            'logo' => asset('images/jobone-logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => 'jobone2026@gmail.com',
                'contactType' => 'Customer Service',
            ],
            'sameAs' => [
                config('services.facebook_url'),
                config('services.twitter_url'),
                config('services.telegram_url'),
            ],
        ];
    }

    public function generateFAQ(string $content, ?array $faqJson = null): ?array
    {
        // Prefer structured FAQ JSON from DB column
        if (!empty($faqJson)) {
            $questions = [];
            foreach (array_slice($faqJson, 0, 10) as $item) {
                $q = trim(strip_tags($item['question'] ?? ''));
                $a = trim(strip_tags($item['answer'] ?? ''));
                if (empty($q) || empty($a)) continue;
                
                $questions[] = [
                    '@type' => 'Question',
                    'name'  => $q,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $a,
                    ],
                ];
            }
            if (!empty($questions)) {
                return [
                    '@context'   => 'https://schema.org',
                    '@type'      => 'FAQPage',
                    'mainEntity' => $questions,
                ];
            }
        }

        // Fallback: extract H3 + P pairs from HTML content
        $cleanContent = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $content);
        preg_match_all('/<h3[^>]*>(.*?)<\/h3>\s*<p[^>]*>(.*?)<\/p>/is', $cleanContent, $matches, PREG_SET_ORDER);

        if (count($matches) < 2) return null;

        $questions = [];
        foreach (array_slice($matches, 0, 10) as $match) {
            $q = trim(strip_tags($match[1]));
            $a = trim(strip_tags($match[2]));
            if (empty($q) || empty($a)) continue;
            
            $questions[] = [
                '@type' => 'Question',
                'name'  => $q,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $a,
                ],
            ];
        }

        if (empty($questions)) return null;

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $questions,
        ];
    }

    // Alias methods for consistency
    public function generateJobPostingSchema(Post $post): array
    {
        return $this->generateJobPosting($post);
    }

    public function generateArticleSchema(Post $post): array
    {
        return $this->generateArticle($post);
    }

    public function generateBreadcrumbSchema(Post $post): array
    {
        // Map post types to their route names
        $routeMap = [
            'job'        => 'posts.jobs',
            'admit_card' => 'posts.admit-cards',
            'result'     => 'posts.results',
            'answer_key' => 'posts.answer-keys',
            'syllabus'   => 'posts.syllabus',
            'blog'       => 'posts.blogs',
        ];

        $routeName  = $routeMap[$post->type] ?? 'home';
        // Use canonical slug URL instead of url()->current() to avoid CDN/cache issues
        $canonicalUrl = url('/' . $post->slug);

        $items = [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => ucfirst(str_replace('_', ' ', $post->type)), 'url' => route($routeName)],
            ['label' => $post->title, 'url' => $canonicalUrl],
        ];

        return $this->generateBreadcrumb($items);
    }

    public function generateWebSiteSchema(): array
    {
        return $this->generateWebSite();
    }

    public function generateOrganizationSchema(): array
    {
        return $this->generateOrganization();
    }

    public function generateFAQSchema(string $content): ?array
    {
        return $this->generateFAQ($content);
    }
}
