<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Category;
use DOMDocument;
use DOMXPath;

class ScrapeCollegeNP extends Command
{
    protected $signature = 'scrape:collegenp 
                            {--page=1 : Page number to scrape}
                            {--max-pages=5 : Maximum number of pages to scrape}
                            {--auto-publish : Automatically publish posts}';

    protected $description = 'Scrape Nepal government job vacancies from CollegeNP.com';

    private $baseUrl = 'https://www.collegenp.com';
    private $scrapedCount = 0;
    private $skippedCount = 0;
    private $errorCount = 0;

    public function handle()
    {
        $this->info('🇳🇵 Starting CollegeNP.com scraper...');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $startPage = (int) $this->option('page');
        $maxPages = (int) $this->option('max-pages');
        $autoPublish = $this->option('auto-publish');

        for ($page = $startPage; $page < $startPage + $maxPages; $page++) {
            $this->info("\n📄 Scraping page {$page}...");
            
            $vacancyUrls = $this->getVacancyListFromPage($page);
            
            if (empty($vacancyUrls)) {
                $this->warn("No vacancies found on page {$page}. Stopping.");
                break;
            }

            $this->info("Found " . count($vacancyUrls) . " vacancies on page {$page}");

            foreach ($vacancyUrls as $url) {
                $this->scrapeAndPostVacancy($url, $autoPublish);
                sleep(2); // Be respectful
            }
        }

        $this->info("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Scraping completed!");
        $this->info("📊 Summary:");
        $this->info("   • Scraped: {$this->scrapedCount}");
        $this->info("   • Skipped: {$this->skippedCount}");
        $this->info("   • Errors: {$this->errorCount}");

        return 0;
    }

    private function getVacancyListFromPage($page)
    {
        try {
            $url = $page === 1 
                ? "{$this->baseUrl}/vacancy" 
                : "{$this->baseUrl}/vacancy?page={$page}";

            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);

            if (!$response->successful()) {
                $this->error("Failed to fetch page {$page}: HTTP {$response->status()}");
                return [];
            }

            $html = $response->body();
            $urls = [];
            if (preg_match_all('/<a[^>]+href="(https:\/\/www\.collegenp\.com\/vacancy\/[^"]+)"/', $html, $matches)) {
                $urls = array_unique($matches[1]);
            }

            return $urls;

        } catch (\Exception $e) {
            $this->error("Error fetching page {$page}: " . $e->getMessage());
            Log::error('CollegeNP page fetch error', ['page' => $page, 'error' => $e->getMessage()]);
            return [];
        }
    }

    private function scrapeAndPostVacancy($url, $autoPublish = false)
    {
        try {
            $this->line("  → Processing: {$url}");

            if (Post::where('source_url', $url)->exists()) {
                $this->warn("    ⏭️  Already exists, skipping");
                $this->skippedCount++;
                return;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);

            if (!$response->successful()) {
                $this->error("    ❌ Failed to fetch: HTTP {$response->status()}");
                $this->errorCount++;
                return;
            }

            $html = $response->body();
            $data = $this->extractVacancyData($html, $url);

            if (!$data) {
                $this->error("    ❌ Failed to extract data");
                $this->errorCount++;
                return;
            }

            $post = $this->createPost($data, $autoPublish);

            if ($post) {
                $this->info("    ✅ Created: {$post->title}");
                $this->scrapedCount++;
            } else {
                $this->error("    ❌ Failed to create post");
                $this->errorCount++;
            }

        } catch (\Exception $e) {
            $this->error("    ❌ Error: " . $e->getMessage());
            $this->errorCount++;
            Log::error('CollegeNP vacancy scrape error', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function extractVacancyData($html, $url)
    {
        libxml_use_internal_errors(true);

        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        $doc = new DOMDocument();
        @$doc->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
        $xpath = new DOMXPath($doc);

        $data = [];

        // Title
        $titleNode = $xpath->query('//h1')->item(0) ?? $xpath->query('//title')->item(0);
        $data['title'] = $titleNode ? $this->cleanText($titleNode->textContent) : 'Untitled Vacancy';
        $data['title'] = preg_replace('/\s*-?\s*CollegeNP.*$/i', '', $data['title']);

        // Description
        $descNode = $xpath->query('//meta[@property="og:description"]/@content')->item(0)
            ?? $xpath->query('//meta[@name="description"]/@content')->item(0);
        $data['short_description'] = $descNode ? $this->cleanText($descNode->nodeValue) : '';

        // Content
        $contentNode = $xpath->query('//*[contains(@class,"article-content")]')->item(0)
            ?? $xpath->query('//*[contains(@class,"post-content")]')->item(0)
            ?? $xpath->query('//article')->item(0);

        if ($contentNode) {
            $data['content'] = $this->extractContent($contentNode, $doc);
        } else {
            $data['content'] = '<p>Content extraction failed. Please visit the source URL.</p>';
        }

        // Dates
        $fullText = $doc->textContent;
        $data['last_date'] = $this->extractDate($fullText, [
            'application deadline', 'last date', 'deadline', 'closing date', 'within office hours'
        ]);
        
        $data['notification_date'] = $this->extractDate($fullText, [
            'published', 'notice', 'advertisement date'
        ]);

        // Links
        $data['important_links'] = $this->extractLinks($xpath, $url);

        // Category & State
        $data['category'] = $this->guessNepalCategory($data['title'] . ' ' . $fullText);
        $data['state'] = 'Nepal';
        $data['source_url'] = $url;

        return $data;
    }

    private function extractContent($node, $doc)
    {
        $clone = $node->cloneNode(true);
        $cloneDoc = new DOMDocument();
        $cloneDoc->appendChild($cloneDoc->importNode($clone, true));
        $xpath = new DOMXPath($cloneDoc);

        $removeTags = ['script', 'style', 'nav', 'footer', 'header', 'aside', 'iframe', 'form'];
        foreach ($removeTags as $tag) {
            $elements = $xpath->query('.//' . $tag);
            foreach (iterator_to_array($elements) as $el) {
                if ($el->parentNode) {
                    $el->parentNode->removeChild($el);
                }
            }
        }

        $html = '';
        foreach ($cloneDoc->documentElement->childNodes as $child) {
            $html .= $cloneDoc->saveHTML($child);
        }

        $html = preg_replace('/<(\w+)[^>]*>\s*<\/\1>/', '', $html);
        $html = preg_replace('/\s*style="[^"]*"/', '', $html);
        $html = preg_replace('/\s+/', ' ', $html);

        return trim($html);
    }

    private function extractLinks($xpath, $baseUrl)
    {
        $links = [];
        $nodes = $xpath->query('//a[@href]');

        foreach ($nodes as $node) {
            $href = trim($node->getAttribute('href'));
            $text = $this->cleanText($node->textContent);

            if (!$href || $href === '#' || strlen($text) < 3) continue;

            if (!str_starts_with($href, 'http')) {
                $href = $this->baseUrl . '/' . ltrim($href, '/');
            }

            $skipDomains = ['facebook.com', 'twitter.com', 'instagram.com', 'youtube.com'];
            $skip = false;
            foreach ($skipDomains as $domain) {
                if (str_contains($href, $domain)) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            $keywords = ['apply', 'form', 'notification', 'download', 'official', 'website', 'gov.np'];
            $relevant = false;
            foreach ($keywords as $kw) {
                if (str_contains(strtolower($text . ' ' . $href), $kw)) {
                    $relevant = true;
                    break;
                }
            }

            if ($relevant && count($links) < 10) {
                $links[] = ['title' => $text, 'url' => $href];
            }
        }

        return $links;
    }

    private function extractDate($text, $labels)
    {
        foreach ($labels as $label) {
            $pattern = '/' . preg_quote($label, '/') . '[^\d]{0,30}(\d{4}[\/\-]\d{2}[\/\-]\d{2})/i';
            if (preg_match($pattern, $text, $m)) {
                return $this->convertNepalDateToGregorian($m[1]);
            }

            $pattern2 = '/' . preg_quote($label, '/') . '[^\d]{0,30}(\d{1,2}\s+(?:january|february|march|april|may|june|july|august|september|october|november|december)\s+\d{4})/i';
            if (preg_match($pattern2, $text, $m)) {
                $ts = strtotime($m[1]);
                if ($ts) return date('Y-m-d', $ts);
            }
        }
        return null;
    }

    private function convertNepalDateToGregorian($nepalDate)
    {
        if (preg_match('/(\d{4})[\/\-](\d{2})[\/\-](\d{2})/', $nepalDate, $m)) {
            $year = (int)$m[1] - 57;
            $month = (int)$m[2];
            $day = (int)$m[3];
            
            if ($year > 1900 && $year < 2100 && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }
        
        return null;
    }

    private function guessNepalCategory($text)
    {
        $text = strtolower($text);

        $categories = [
            'Lok Sewa Aayog' => ['lok sewa', 'loksewa', 'public service commission', 'psc'],
            'Nepal Police' => ['nepal police', 'police', 'inspector', 'constable'],
            'Nepal Army' => ['nepal army', 'army', 'sainya', 'military'],
            'Health Service' => ['health', 'hospital', 'nurse', 'doctor', 'medical', 'staff nurse'],
            'Teaching Service' => ['teacher', 'teaching', 'school', 'college', 'university', 'professor', 'lecturer'],
            'Banking' => ['bank', 'banking', 'rastra bank', 'financial'],
            'Local Government' => ['municipality', 'rural municipality', 'gaunpalika', 'nagarpalika', 'metropolitan'],
            'Provincial Government' => ['province', 'provincial', 'pradesh'],
        ];

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $category;
                }
            }
        }

        return 'Government Jobs Nepal';
    }

    private function cleanText($text)
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/CollegeNP/i', '', $text);
        return trim($text);
    }

    private function createPost($data, $autoPublish)
    {
        try {
            $category = Category::firstOrCreate(
                ['name' => $data['category']],
                [
                    'slug' => \Str::slug($data['category']),
                    'description' => 'Nepal ' . $data['category'],
                    'is_active' => true
                ]
            );

            $post = Post::create([
                'title' => $data['title'],
                'slug' => \Str::slug($data['title']) . '-' . time(),
                'short_description' => $data['short_description'] ?: substr(strip_tags($data['content']), 0, 150),
                'content' => $data['content'],
                'category_id' => $category->id,
                'post_type' => 'job',
                'state' => $data['state'],
                'last_date' => $data['last_date'],
                'notification_date' => $data['notification_date'],
                'source_url' => $data['source_url'],
                'important_links' => !empty($data['important_links']) ? json_encode($data['important_links']) : null,
                'status' => $autoPublish ? 'published' : 'draft',
                'published_at' => $autoPublish ? now() : null,
                'author_id' => 1,
                'views' => 0,
                'is_featured' => false,
            ]);

            return $post;

        } catch (\Exception $e) {
            Log::error('Failed to create post', [
                'title' => $data['title'] ?? 'Unknown',
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
