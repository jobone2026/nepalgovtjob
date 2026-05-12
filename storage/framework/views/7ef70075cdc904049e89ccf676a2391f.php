<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['seo' => null, 'schema' => null, 'noindex' => false, 'post' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['seo' => null, 'schema' => null, 'noindex' => false, 'post' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
use Illuminate\Support\Str;
    // Auto-noindex pages with query params that produce thin/duplicate content
    $hasFilterParams = request()->hasAny(['type', 'page', 'q']);
    if ($hasFilterParams) {
        $noindex = true;
    }

    // Canonical should always be the clean URL without query params
    $cleanCanonical = url()->current(); // url()->current() already strips query string

    $defaultSeo = [
        'title'          => 'JobOne.in – Sarkari Naukri ' . date('Y') . ', Govt Jobs Today, Daily Alert Updates',
        'description'    => 'JobOne.in – India\'s fastest-updated sarkari naukri portal. Today\'s govt job vacancies, exam results, admit cards, answer keys & syllabus for SSC, UPSC, Railways, Banking, State PSC, Defence & Police.',
        'keywords'       => 'sarkari naukri, govt jobs today, government jobs ' . date('Y') . ', SSC, UPSC, Railways, Banking, jobone',
        'canonical'      => $cleanCanonical,
        'og_title'       => 'JobOne – Sarkari Naukri & Govt Jobs Updated Daily ' . date('Y'),
        'og_description' => 'India\'s fastest sarkari naukri portal — govt job vacancies, results, admit cards & more. Updated every day on JobOne.in.',
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
            'name'     => 'JobOne.in',
            'alternateName' => ['JobOne', 'Sarkari Naukri JobOne'],
            'url'      => 'https://jobone.in',
            'description' => "India's trusted government job portal for SSC, UPSC, Railways, Banking, State PSC.",
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => 'https://jobone.in/search?q={search_term_string}'],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        $homeSchemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Organization',
            'name'        => 'JobOne.in',
            'url'         => 'https://jobone.in',
            'logo'        => 'https://jobone.in/images/logo.png',
            'description' => "India's fastest-updated sarkari naukri portal — daily govt job vacancies, admit cards, exam results, answer keys and syllabus.",
            'foundingDate'=> '2025',
            'areaServed'  => 'India',
            'knowsAbout'  => ['Sarkari Naukri', 'Government Jobs', 'SSC', 'UPSC', 'Railways Recruitment', 'Banking Jobs', 'State PSC', 'Admit Cards', 'Exam Results'],
            'sameAs'      => ['https://www.facebook.com/jobone.in', 'https://twitter.com/JobOneIn', 'https://t.me/jobone_in'],
            'contactPoint'=> ['@type' => 'ContactPoint', 'contactType' => 'customer support', 'areaServed' => 'IN', 'availableLanguage' => ['English', 'Hindi']],
        ];
        $homeSchemas[] = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'How to find latest government jobs in India?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Visit JobOne.in to find the latest government job notifications across India including SSC, UPSC, Railways, Banking, State PSC, Defence, Police and Teaching jobs. We update daily.']],
                ['@type' => 'Question', 'name' => 'How to download admit card for government exams?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Visit the Admit Cards section on JobOne.in, search for your exam name, and click the official link to download your hall ticket or call letter.']],
                ['@type' => 'Question', 'name' => 'How to check sarkari result ' . date('Y') . '?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Go to the Results section on JobOne.in to find the latest sarkari results. We provide direct links to official result pages for SSC, UPSC, Railways, Banking, State PSC exams.']],
                ['@type' => 'Question', 'name' => 'Is JobOne.in free to use?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes, JobOne.in is completely free. Browse all government job notifications, admit cards, results, answer keys and syllabus without any registration or payment.']],
            ],
        ];
    }
?>

<!-- Primary Meta Tags -->
<title><?php echo e($seo['title']); ?></title>
<meta name="title" content="<?php echo e($seo['title']); ?>">
<meta name="description" content="<?php echo e($seo['description']); ?>">
<meta name="keywords" content="<?php echo e($seo['keywords']); ?>">
<meta name="author" content="JobOne.in">
<meta name="publisher" content="JobOne.in">
<meta name="language" content="English">
<meta name="revisit-after" content="1 day">
<link rel="canonical" href="<?php echo e($seo['canonical']); ?>">

<!-- Geo Tags -->
<meta name="geo.region" content="IN">
<meta name="geo.placename" content="India">

<!-- Mobile -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="JobOne">
<meta name="application-name" content="JobOne.in">
<meta name="theme-color" content="#2563eb">

<!-- Robots -->
<?php if($noindex): ?>
<meta name="robots" content="noindex, follow">
<meta name="googlebot" content="noindex, follow">
<?php else: ?>
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<?php endif; ?>
<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

<!-- Open Graph -->
<meta property="og:type" content="<?php echo e($ogType); ?>">
<meta property="og:site_name" content="JobOne.in">
<meta property="og:title" content="<?php echo e($seo['og_title']); ?>">
<meta property="og:description" content="<?php echo e($seo['og_description']); ?>">
<meta property="og:image" content="<?php echo e($seo['og_image']); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:alt" content="<?php echo e(Str::limit($seo['og_title'], 100)); ?>">
<meta property="og:url" content="<?php echo e($seo['og_url']); ?>">
<meta property="og:locale" content="en_IN">
<?php if($post && $post->created_at): ?>
<meta property="article:published_time" content="<?php echo e($post->created_at->toIso8601String()); ?>">
<meta property="article:modified_time" content="<?php echo e($post->updated_at->toIso8601String()); ?>">
<meta property="article:author" content="JobOne.in">
<meta property="article:section" content="<?php echo e(ucwords(str_replace('_', ' ', $post->type ?? 'blog'))); ?>">
<?php endif; ?>

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@JobOneIn">
<meta name="twitter:title" content="<?php echo e($seo['og_title']); ?>">
<meta name="twitter:description" content="<?php echo e($seo['og_description']); ?>">
<meta name="twitter:image" content="<?php echo e($seo['og_image']); ?>">

<!-- Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://www.google-analytics.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//www.google-analytics.com">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

<!-- Hreflang -->
<link rel="alternate" hreflang="en-in" href="<?php echo e($seo['canonical']); ?>">
<link rel="alternate" hreflang="x-default" href="<?php echo e($seo['canonical']); ?>">

<!-- Structured Data (page-specific schemas from controller) -->
<?php if($schema): ?>
    <?php $__currentLoopData = $schema; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schemaItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script type="application/ld+json"><?php echo json_encode($schemaItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<!-- Home-page schemas (WebSite, Organization, FAQPage) -->
<?php $__currentLoopData = $homeSchemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<script type="application/ld+json"><?php echo json_encode($hs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?></script>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\job\loksewaalert-portal\resources\views/components/seo-head.blade.php ENDPATH**/ ?>