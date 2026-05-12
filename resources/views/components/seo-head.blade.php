@props(['seo' => null, 'schema' => null, 'noindex' => false, 'post' => null])

@php
use Illuminate\Support\Str;
    // Auto-noindex pages with query params that produce thin/duplicate content
    $hasFilterParams = request()->hasAny(['type', 'page', 'q']);
    if ($hasFilterParams) {
        $noindex = true;
    }

    // Canonical should always be the clean URL without query params
    $cleanCanonical = url()->current(); // url()->current() already strips query string

    $defaultSeo = [
        'title'          => 'LokSewaAlert – Lok Sewa Aayog ' . date('Y') . ', Nepal Govt Jobs Today, Daily Updates',
        'description'    => 'LokSewaAlert – Nepal\'s fastest-updated Lok Sewa portal. Today\'s government job vacancies, exam results, admit cards, answer keys & syllabus for PSC, Nepal Police, Nepal Army, Banking & Teaching jobs.',
        'keywords'       => 'lok sewa aayog, nepal govt jobs, government jobs nepal ' . date('Y') . ', PSC Nepal, Nepal Police, Nepal Army, loksewaalert',
        'canonical'      => $cleanCanonical,
        'og_title'       => 'LokSewaAlert – Nepal Govt Jobs & Lok Sewa Updated Daily ' . date('Y'),
        'og_description' => 'Nepal\'s fastest Lok Sewa portal — govt job vacancies, results, admit cards & more. Updated every day on LokSewaAlert.',
        'og_image'       => asset('images/og-image.jpg'),
        'og_url'         => $cleanCanonical,
    ];
    $seo     = array_merge($defaultSeo, $seo ?? []);
    $isHome  = request()->is('/');
    $ogType  = ($post && in_array($post->type ?? '', ['blog', 'job', 'admit_card', 'result', 'answer_key', 'syllabus'])) ? 'article' : 'website';
    // Ensure og_image is absolute
    if (!empty($seo['og_image']) && !str_starts_with($seo['og_image'], 'http')) {
        $seo['og_image'] = url($seo['og_image']);
    }
    // Use post's featured image if available (scraper-set image = real job image)
    if ($post && !empty($post->featured_image)) {
        $seo['og_image'] = $post->featured_image;
    }
    if (empty($seo['og_image'])) {
        $seo['og_image'] = url('/images/og-image.jpg');
    }
    // Truncate description to 155 chars for OG
    $seo['og_description'] = Str::limit(strip_tags($seo['og_description'] ?? $seo['description'] ?? ''), 155);
    $seo['description']    = Str::limit(strip_tags($seo['description'] ?? ''), 155);

    // Build home schemas as PHP (avoids Blade parsing JSON curly braces as directives)
    $homeSchemas = [];
    if ($isHome) {
        $homeSchemas[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => 'LokSewaAlert',
            'alternateName' => ['LokSewaAlert', 'Lok Sewa Alert Nepal'],
            'url'      => 'http://13.206.244.237',
            'description' => "Nepal's trusted government job portal for Lok Sewa Aayog, PSC, Nepal Police, Nepal Army, Banking, Teaching jobs.",
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => 'http://13.206.244.237/search?q={search_term_string}'],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        $homeSchemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Organization',
            'name'        => 'LokSewaAlert',
            'url'         => 'http://13.206.244.237',
            'logo'        => 'http://13.206.244.237/images/logo.png',
            'description' => "Nepal's fastest-updated Lok Sewa portal — daily govt job vacancies, admit cards, exam results, answer keys and syllabus.",
            'foundingDate'=> '2026',
            'areaServed'  => 'Nepal',
            'knowsAbout'  => ['Lok Sewa Aayog', 'Government Jobs Nepal', 'PSC Nepal', 'Nepal Police', 'Nepal Army', 'Banking Jobs Nepal', 'Teaching Jobs', 'Admit Cards', 'Exam Results'],
            'sameAs'      => ['https://t.me/loksewaalert', 'https://whatsapp.com/channel/0029VbAn'],
            'contactPoint'=> ['@type' => 'ContactPoint', 'contactType' => 'customer support', 'areaServed' => 'NP', 'availableLanguage' => ['English', 'Nepali']],
        ];
        $homeSchemas[] = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'How to find latest government jobs in Nepal?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Visit LokSewaAlert to find the latest government job notifications across Nepal including Lok Sewa Aayog, PSC, Nepal Police, Nepal Army, Banking, and Teaching jobs. We update daily.']],
                ['@type' => 'Question', 'name' => 'How to download admit card for Lok Sewa exams?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Visit the Admit Cards section on LokSewaAlert, search for your exam name, and click the official link to download your admit card or hall ticket.']],
                ['@type' => 'Question', 'name' => 'How to check Lok Sewa result ' . date('Y') . '?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Go to the Results section on LokSewaAlert to find the latest Lok Sewa results. We provide direct links to official result pages for PSC, Nepal Police, Nepal Army, and Banking exams.']],
                ['@type' => 'Question', 'name' => 'Is LokSewaAlert free to use?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes, LokSewaAlert is completely free. Browse all government job notifications, admit cards, results, answer keys and syllabus without any registration or payment.']],
            ],
        ];
    }
@endphp

<!-- Primary Meta Tags -->
<title>{{ $seo['title'] }}</title>
<meta name="title" content="{{ $seo['title'] }}">
<meta name="description" content="{{ $seo['description'] }}">
<meta name="keywords" content="{{ $seo['keywords'] }}">
<meta name="author" content="LokSewaAlert">
<meta name="publisher" content="LokSewaAlert">
<meta name="language" content="English">
<meta name="revisit-after" content="1 day">
<link rel="canonical" href="{{ $seo['canonical'] }}">

<!-- Geo Tags -->
<meta name="geo.region" content="NP">
<meta name="geo.placename" content="Nepal">

<!-- Mobile -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="LokSewaAlert">
<meta name="application-name" content="LokSewaAlert">
<meta name="theme-color" content="#2563eb">

<!-- Robots -->
@if($noindex)
<meta name="robots" content="noindex, follow">
<meta name="googlebot" content="noindex, follow">
@else
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
@endif
<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

<!-- Open Graph -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="LokSewaAlert">
<meta property="og:title" content="{{ $seo['og_title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] }}">
<meta property="og:image" content="{{ $seo['og_image'] }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:alt" content="{{ Str::limit($seo['og_title'], 100) }}">
<meta property="og:url" content="{{ $seo['og_url'] }}">
<meta property="og:locale" content="en_NP">
@if($post && $post->created_at)
<meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
<meta property="article:author" content="LokSewaAlert">
<meta property="article:section" content="{{ ucwords(str_replace('_', ' ', $post->type ?? 'blog')) }}">
@endif

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@LokSewaAlert">
<meta name="twitter:title" content="{{ $seo['og_title'] }}">
<meta name="twitter:description" content="{{ $seo['og_description'] }}">
<meta name="twitter:image" content="{{ $seo['og_image'] }}">

<!-- Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://www.google-analytics.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//www.google-analytics.com">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

<!-- Hreflang -->
<link rel="alternate" hreflang="en-np" href="{{ $seo['canonical'] }}">
<link rel="alternate" hreflang="x-default" href="{{ $seo['canonical'] }}">

<!-- Structured Data (page-specific schemas from controller) -->
@if($schema)
    @foreach($schema as $schemaItem)
        <script type="application/ld+json">{!! json_encode($schemaItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @endforeach
@endif

<!-- Home-page schemas (WebSite, Organization, FAQPage) -->
@foreach($homeSchemas as $hs)
<script type="application/ld+json">{!! json_encode($hs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endforeach
