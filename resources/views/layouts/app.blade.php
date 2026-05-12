<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

    <!-- SEO Meta Tags -->
    @php
        $seoData = $seo ?? [
            'title' => 'LokSewaAlert – ' . date('Y') . ' लोकसेवा सुचना, सरकारी नोकरी आज',
            'description' => 'LokSewaAlert – Nepal\'s fastest-updated लोकसेवा सुचना portal. आजको सरकारी नोकरी, परिणाम, प्रवेश पत्र, उत्तर कुंजी र पाठ्यक्रम।',
            'keywords' => 'लोकसेवा, सरकारी नोकरी, loksewa, lok sewa ' . date('Y') . ', loksewaalert',
            'canonical' => url()->current(),
            'og_title' => 'LokSewaAlert – ' . date('Y') . ' लोकसेवा सुचना',
            'og_description' => 'Nepal\'s fastest लोकसेवा सुचना portal — सरकारी नोकरी, परिणाम, प्रवेश पत्र & more. Updated daily on LokSewaAlert.',
            'og_image' => asset('images/og-image.jpg'),
            'og_url' => url()->current(),
        ];
    @endphp
    <x-seo-head :seo="$seoData" :schema="$schema ?? null" :post="$post ?? null" />

    <!-- Google Analytics -->
    @php
        // Env-first, DB as fallback — ensures GA always loads on live server
        $gaTrackingId = env('GA_TRACKING_ID')
            ?: \App\Models\SiteSetting::where('key', 'ga_tracking_id')->value('value');
    @endphp
    @if($gaTrackingId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaTrackingId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $gaTrackingId }}', {
                'anonymize_ip': true,
                'page_title': {!! json_encode(html_entity_decode($seoData['title'] ?? 'LokSewaAlert')) !!},
                'page_location': window.location.href
            });
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- DNS Prefetch for Performance (lightweight hints) -->
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//translate.google.com">
    
    <!-- Preconnect ONLY to most critical origins (max 3-4) -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Font Awesome: load non-render-blocking, then swap to all.min.css -->
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    <!-- Preload Logo for LCP -->
    <link rel="preload" as="image" href="{{ asset('images/jobone-logo.png') }}" fetchpriority="high">
    
    <style>
        /* Fix font-display for Font Awesome webfonts */
        @font-face { font-family: 'Font Awesome 6 Free'; font-display: swap; }
        @font-face { font-family: 'Font Awesome 6 Brands'; font-display: swap; }
    </style>

    <style>
        /* RTL Support */
        html[dir="rtl"] {
            direction: rtl;
            text-align: right;
        }

        html[dir="rtl"] body {
            direction: rtl;
        }

        html[dir="rtl"] .flex {
            flex-direction: row-reverse;
        }

        /* Hide Google Translate banner */
        .goog-te-banner-frame {
            display: none !important;
        }

        body {
            top: 0 !important;
        }

        /* Hide the top frame completely */
        body>.skiptranslate {
            display: none !important;
        }

        iframe.goog-te-banner-frame {
            display: none !important;
        }

        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        /* Hide Google Translate widget completely */
        #google_translate_element {
            position: fixed;
            bottom: -200px;
            left: -200px;
            opacity: 0;
            pointer-events: none;
        }

        #custom_language_select {
            padding: 4px 8px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            background-color: white;
            color: #4b5563;
            cursor: pointer;
            min-width: 60px;
            max-width: 80px;
        }

        #custom_language_select:hover {
            border-color: #3b82f6;
        }
    </style>

<style>
    html, body { 
        overflow-x: hidden !important; 
        position: relative; 
        width: 100%; 
        touch-action: pan-y; /* Prevent horizontal swipe gestures */
        overscroll-behavior-x: none;
    }
</style>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal overflow-x-hidden" style="padding-bottom: 0; overflow-x: hidden !important;">

    <!-- Language Chooser Bar -->
    <div
        class="bg-gradient-to-r from-sky-50 via-blue-50 to-indigo-50 border-b border-gray-200 py-2 shadow-sm notranslate">
        <div class="w-full px-2 sm:px-4 lg:px-6">
            <!-- Fix flex center cutting off first item left scroll -->
            <div class="flex items-center justify-start md:justify-center gap-3 overflow-x-auto hide-scrollbar whitespace-nowrap notranslate px-1">
                <div class="flex items-center gap-2 notranslate w-max">
                    <button onclick="changeLanguage('')" class="language-btn notranslate" data-lang="">English</button>

                    @if (!config('app.domain_state_id'))
                        <!-- Show all languages for main domain -->
                        <button onclick="changeLanguage('hi')" class="language-btn notranslate"
                            data-lang="hi">हिंदी</button>
                        <button onclick="changeLanguage('te')" class="language-btn notranslate"
                            data-lang="te">తెలుగు</button>
                        <button onclick="changeLanguage('ta')" class="language-btn notranslate"
                            data-lang="ta">தமிழ்</button>
                        <button onclick="changeLanguage('kn')" class="language-btn notranslate"
                            data-lang="kn">ಕನ್ನಡ</button>
                        <button onclick="changeLanguage('ml')" class="language-btn notranslate"
                            data-lang="ml">മലയാളം</button>
                        <button onclick="changeLanguage('mr')" class="language-btn notranslate"
                            data-lang="mr">मराठी</button>
                        <button onclick="changeLanguage('gu')" class="language-btn notranslate"
                            data-lang="gu">ગુજરાતી</button>
                        <button onclick="changeLanguage('bn')" class="language-btn notranslate"
                            data-lang="bn">বাংলা</button>
                        <button onclick="changeLanguage('pa')" class="language-btn notranslate"
                            data-lang="pa">ਪੰਜਾਬੀ</button>
                        <button onclick="changeLanguage('or')" class="language-btn notranslate"
                            data-lang="or">ଓଡ଼ିଆ</button>
                        <button onclick="changeLanguage('as')" class="language-btn notranslate"
                            data-lang="as">অসমীয়া</button>
                    @else
                        <!-- Show only state-specific language for filtered domains -->
                        @if (config('app.domain_state_slug') === 'nepal')
                            <button onclick="changeLanguage('ne')" class="language-btn notranslate"
                                data-lang="ne">नेपाली</button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div><!-- /language bar -->

    <style>
        .language-btn {
            padding: 6px 16px;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: #0c4a6e;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .language-btn:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        @media (max-width: 767px) {
            .language-btn {
                padding: 4px 10px;
                font-size: 11px;
                border-radius: 6px;
            }
        }
    </style>

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50 border-b-2 border-blue-500"
        style="background: white !important; border-bottom: 2px solid #3b82f6 !important;">
        <nav class="w-full px-2 sm:px-4 lg:px-6 py-1 md:py-2">
            <div class="flex justify-between items-center gap-2">
                <!-- Logo -->
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 flex-shrink-0 transform hover:scale-105 transition-transform">
                    <img src="{{ asset('images/jobone-logo.png') }}" alt="LokSewaAlert - Nepal Government Jobs Portal"
                        class="h-10 md:h-12 w-auto object-contain drop-shadow-sm"
                        width="160" height="48" loading="eager" fetchpriority="high">
                </a>

                <!-- Custom Language Selector - Hidden -->
                <div class="hidden">
                    <select id="custom_language_select" class="text-xs notranslate">
                        <option value="">English</option>
                        @if (!config('app.domain_state_id'))
                            <!-- Show all languages for main domain -->
                            <option value="hi">हिंदी</option>
                            <option value="te">తెలుగు</option>
                            <option value="ta">தமிழ்</option>
                            <option value="kn">ಕನ್ನಡ</option>
                            <option value="ml">മലയാളം</option>
                            <option value="mr">मराठी</option>
                            <option value="gu">ગુજરાતી</option>
                            <option value="bn">বাংলা</option>
                            <option value="pa">ਪੰਜਾਬੀ</option>
                            <option value="or">ଓଡ଼ିଆ</option>
                            <option value="as">অসমীয়া</option>
                        @else
                            <!-- Show only state-specific language for filtered domains -->
                            @if (config('app.domain_state_slug') === 'nepal')
                                <option value="ne">नेपाली</option>
                            @endif
                        @endif
                    </select>
                </div>

                <!-- Hidden Google Translate Widget -->
                <div id="google_translate_element"></div>

                <!-- Mobile Menu Button - hidden on mobile, shown only on tablet -->
                <div class="hidden sm:flex md:hidden">
                    <button id="mobile-menu-button"
                        aria-label="Open navigation menu"
                        aria-expanded="false"
                        aria-controls="mobile-menu"
                        class="p-3 text-gray-700 hover:text-blue-600 focus:outline-none bg-gray-100 rounded-lg"
                        style="color: #374151 !important; background-color: #f3f4f6 !important;">
                        <i id="mobile-menu-icon" class="fas fa-bars text-xl" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('home') }}"
                        class="px-4 py-1 text-blue-700 hover:text-white hover:bg-blue-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #1d4ed8 !important; font-weight: 900 !important;"><i class="fas fa-home mr-1"></i>
                        Home</a>
                    <a href="{{ route('posts.jobs') }}"
                        class="px-4 py-1 text-green-700 hover:text-white hover:bg-green-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #15803d !important; font-weight: 900 !important;"><i class="fas fa-briefcase mr-1"></i>
                        Jobs</a>
                    <a href="{{ route('posts.admit-cards') }}"
                        class="px-4 py-1 text-purple-700 hover:text-white hover:bg-purple-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #7e22ce !important; font-weight: 900 !important;"><i class="fas fa-id-card mr-1"></i>
                        Admit</a>
                    <a href="{{ route('posts.results') }}"
                        class="px-4 py-1 text-orange-700 hover:text-white hover:bg-orange-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #c2410c !important; font-weight: 900 !important;"><i class="fas fa-chart-bar mr-1"></i>
                        Results</a>
                    <a href="{{ route('posts.syllabus') }}"
                        class="px-4 py-1 text-indigo-700 hover:text-white hover:bg-indigo-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #4338ca !important; font-weight: 900 !important;"><i class="fas fa-book mr-1"></i>
                        Syllabus</a>
                    <a href="{{ route('posts.blogs') }}"
                        class="px-4 py-1 text-pink-700 hover:text-white hover:bg-pink-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #be185d !important; font-weight: 900 !important;"><i class="fas fa-pen-fancy mr-1"></i>
                        Blogs</a>
                    <a href="{{ route('posts.scholarships') }}"
                        class="px-4 py-1 text-teal-700 hover:text-white hover:bg-teal-600 rounded-lg text-lg font-black transition-all hover:shadow transform hover:scale-105"
                        style="color: #0f766e !important; font-weight: 900 !important;"><i class="fas fa-graduation-cap mr-1"></i>
                        Scholarships</a>
                </div>

                <!-- Search Bar -->
                <div class="relative flex-1 max-w-[280px] sm:max-w-[320px] md:max-w-none md:w-96" id="search-container">
                    <form action="{{ route('search') }}" method="GET" class="flex items-center gap-2">
                        <input type="text" name="q" id="search-input" placeholder="Search jobs, results..."
                            class="px-4 py-2 bg-gray-50 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 w-full text-base font-bold shadow-sm"
                            style="background-color: #f9fafb !important; border: 2px solid #93c5fd !important; font-weight: 700 !important;"
                            autocomplete="off">
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 font-black text-base shadow-sm flex-shrink-0 transform hover:scale-105 transition-all"
                            style="background: linear-gradient(to right, #2563eb, #4f46e5) !important; color: white !important; font-weight: 900 !important;"><i
                                class="fas fa-search"></i></button>
                    </form>

                    <!-- Autocomplete Dropdown -->
                    <div id="search-results"
                        class="absolute top-full left-0 right-0 mt-2 bg-white border-2 border-blue-300 rounded-lg shadow-xl max-h-96 overflow-y-auto z-50 hidden"
                        style="background: white !important; border: 2px solid #93c5fd !important;"></div>
                </div>

                <script>
                    (function () {
                        const searchInput = document.getElementById('search-input');
                        const searchResults = document.getElementById('search-results');
                        const searchContainer = document.getElementById('search-container');
                        let debounceTimer;

                        if (!searchInput) return;

                        searchInput.addEventListener('input', function () {
                            clearTimeout(debounceTimer);
                            const query = this.value.trim();

                            if (query.length < 2) {
                                searchResults.classList.add('hidden');
                                searchResults.innerHTML = '';
                                return;
                            }

                            debounceTimer = setTimeout(() => {
                                fetch('/search/autocomplete?q=' + encodeURIComponent(query))
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.length > 0) {
                                            let html = '';
                                            data.forEach(post => {
                                                const typeLabel = post.type.replace('_', ' ').toUpperCase();
                                                html += `
                                                <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors" onclick="window.location.href='/${post.type}/${post.slug}'">
                                                    <p class="text-sm text-gray-900 font-semibold leading-snug">${post.title}</p>
                                                    <p class="text-xs text-blue-600 mt-1 font-medium">${typeLabel}</p>
                                                </div>
                                            `;
                                            });
                                            searchResults.innerHTML = html;
                                            searchResults.classList.remove('hidden');
                                        } else {
                                            searchResults.classList.add('hidden');
                                            searchResults.innerHTML = '';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Search error:', error);
                                        searchResults.classList.add('hidden');
                                    });
                            }, 300);
                        });

                        // Close dropdown when clicking outside
                        document.addEventListener('click', function (e) {
                            if (!searchContainer.contains(e.target)) {
                                searchResults.classList.add('hidden');
                            }
                        });
                    })();
                </script>
            </div>
        </nav>

        <!-- Mobile Slide-down Menu (only visible sm+ since mobile uses bottom nav) -->
        <div id="mobile-menu" class="hidden bg-white border-t-2 border-blue-500 shadow-xl sm:hidden"
            style="background: white !important; border-top: 2px solid #3b82f6 !important;">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('home') }}"
                    class="block px-4 py-3 text-blue-700 hover:text-white hover:bg-blue-600 rounded-lg text-base font-bold transition-all"
                    style="color: #1d4ed8 !important; font-weight: 700 !important;">
                    <i class="fas fa-home w-5 mr-2"></i> Home
                </a>
                <a href="{{ route('posts.jobs') }}"
                    class="block px-4 py-3 text-green-700 hover:text-white hover:bg-green-600 rounded-lg text-base font-bold transition-all"
                    style="color: #15803d !important; font-weight: 700 !important;">
                    <i class="fas fa-briefcase w-5 mr-2"></i> Jobs
                </a>
                <a href="{{ route('posts.admit-cards') }}"
                    class="block px-4 py-3 text-purple-700 hover:text-white hover:bg-purple-600 rounded-lg text-base font-bold transition-all"
                    style="color: #7e22ce !important; font-weight: 700 !important;">
                    <i class="fas fa-id-card w-5 mr-2"></i> Admit Cards
                </a>
                <a href="{{ route('posts.results') }}"
                    class="block px-4 py-3 text-orange-700 hover:text-white hover:bg-orange-600 rounded-lg text-base font-bold transition-all"
                    style="color: #c2410c !important; font-weight: 700 !important;">
                    <i class="fas fa-chart-bar w-5 mr-2"></i> Results
                </a>
                <a href="{{ route('posts.syllabus') }}"
                    class="block px-4 py-3 text-indigo-700 hover:text-white hover:bg-indigo-600 rounded-lg text-base font-bold transition-all"
                    style="color: #4338ca !important; font-weight: 700 !important;">
                    <i class="fas fa-book w-5 mr-2"></i> Syllabus
                </a>
                <a href="{{ route('posts.blogs') }}"
                    class="block px-4 py-3 text-pink-700 hover:text-white hover:bg-pink-600 rounded-lg text-base font-bold transition-all"
                    style="color: #be185d !important; font-weight: 700 !important;">
                    <i class="fas fa-pen-fancy w-5 mr-2"></i> Blogs
                </a>
                <a href="{{ route('posts.scholarships') }}"
                    class="block px-4 py-3 text-teal-700 hover:text-white hover:bg-teal-600 rounded-lg text-base font-bold transition-all"
                    style="color: #0f766e !important; font-weight: 700 !important;">
                    <i class="fas fa-graduation-cap w-5 mr-2"></i> Scholarships
                </a>

                <!-- Mobile Search -->
                <div class="pt-3 border-t-2 border-blue-300" style="border-top: 2px solid #93c5fd !important;">
                    <div class="relative" id="mobile-search-container">
                        <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                            <input type="text" name="q" id="mobile-search-input" placeholder="Search..."
                                class="flex-1 px-4 py-3 bg-gray-50 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm font-bold"
                                style="background-color: #f9fafb !important; border: 2px solid #93c5fd !important; font-weight: 700 !important;"
                                autocomplete="off">
                            <button type="submit"
                                class="px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 text-sm font-black"
                                style="background: linear-gradient(to right, #2563eb, #4f46e5) !important; color: white !important; font-weight: 900 !important;">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        <!-- Mobile Autocomplete Dropdown -->
                        <div id="mobile-search-results"
                            class="absolute top-full left-0 right-0 mt-2 bg-white border-2 border-blue-300 rounded-lg shadow-xl max-h-64 overflow-y-auto z-50 hidden"
                            style="background: white !important; border: 2px solid #93c5fd !important;"></div>
                    </div>

                    <script>
                        (function () {
                            const mobileInput = document.getElementById('mobile-search-input');
                            const mobileResults = document.getElementById('mobile-search-results');
                            const mobileContainer = document.getElementById('mobile-search-container');
                            let mobileTimer;

                            if (!mobileInput) return;

                            mobileInput.addEventListener('input', function () {
                                clearTimeout(mobileTimer);
                                const query = this.value.trim();

                                if (query.length < 2) {
                                    mobileResults.classList.add('hidden');
                                    mobileResults.innerHTML = '';
                                    return;
                                }

                                mobileTimer = setTimeout(() => {
                                    fetch('/search/autocomplete?q=' + encodeURIComponent(query))
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data && data.length > 0) {
                                                let html = '';
                                                data.forEach(post => {
                                                    const typeLabel = post.type.replace('_', ' ').toUpperCase();
                                                    html += `
                                                    <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="window.location.href='/${post.type}/${post.slug}'">
                                                        <p class="text-xs text-gray-900 font-semibold">${post.title}</p>
                                                        <p class="text-xs text-blue-600 mt-0.5 font-medium">${typeLabel}</p>
                                                    </div>
                                                `;
                                                });
                                                mobileResults.innerHTML = html;
                                                mobileResults.classList.remove('hidden');
                                            } else {
                                                mobileResults.classList.add('hidden');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Mobile search error:', error);
                                            mobileResults.classList.add('hidden');
                                        });
                                }, 300);
                            });

                            document.addEventListener('click', function (e) {
                                if (!mobileContainer.contains(e.target)) {
                                    mobileResults.classList.add('hidden');
                                }
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </header>



    <!-- Category Image Cards Section -->
    <style>
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: 12px;
            padding: 16px 0;
        }

        .category-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px solid;
            text-decoration: none;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Banking - Emerald/Green */
        .category-card:nth-child(1) {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-color: #10b981;
        }

        .category-card:nth-child(1) .category-icon {
            color: #059669;
        }

        .category-card:nth-child(1) .category-label {
            color: #065f46;
        }

        .category-card:nth-child(1):hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .category-card:nth-child(1):hover .category-icon,
        .category-card:nth-child(1):hover .category-label {
            color: white;
        }

        /* Railways - Teal */
        .category-card:nth-child(2) {
            background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
            border-color: #14b8a6;
        }

        .category-card:nth-child(2) .category-icon {
            color: #0f766e;
        }

        .category-card:nth-child(2) .category-label {
            color: #134e4a;
        }

        .category-card:nth-child(2):hover {
            background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%);
        }

        .category-card:nth-child(2):hover .category-icon,
        .category-card:nth-child(2):hover .category-label {
            color: white;
        }

        /* SSC - Cyan */
        .category-card:nth-child(3) {
            background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
            border-color: #06b6d4;
        }

        .category-card:nth-child(3) .category-icon {
            color: #0891b2;
        }

        .category-card:nth-child(3) .category-label {
            color: #164e63;
        }

        .category-card:nth-child(3):hover {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .category-card:nth-child(3):hover .category-icon,
        .category-card:nth-child(3):hover .category-label {
            color: white;
        }

        /* UPSC - Indigo */
        .category-card:nth-child(4) {
            background: linear-gradient(135deg, #c7d2fe 0%, #a5b4fc 100%);
            border-color: #6366f1;
        }

        .category-card:nth-child(4) .category-icon {
            color: #4f46e5;
        }

        .category-card:nth-child(4) .category-label {
            color: #3730a3;
        }

        .category-card:nth-child(4):hover {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .category-card:nth-child(4):hover .category-icon,
        .category-card:nth-child(4):hover .category-label {
            color: white;
        }

        /* State PSC - Teal */
        .category-card:nth-child(5) {
            background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
            border-color: #14b8a6;
        }

        .category-card:nth-child(5) .category-icon {
            color: #0d9488;
        }

        .category-card:nth-child(5) .category-label {
            color: #115e59;
        }

        .category-card:nth-child(5):hover {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
        }

        .category-card:nth-child(5):hover .category-icon,
        .category-card:nth-child(5):hover .category-label {
            color: white;
        }

        /* Defence - Slate */
        .category-card:nth-child(6) {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            border-color: #64748b;
        }

        .category-card:nth-child(6) .category-icon {
            color: #475569;
        }

        .category-card:nth-child(6) .category-label {
            color: #334155;
        }

        .category-card:nth-child(6):hover {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        }

        .category-card:nth-child(6):hover .category-icon,
        .category-card:nth-child(6):hover .category-label {
            color: white;
        }

        /* Police - Blue */
        .category-card:nth-child(7) {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-color: #3b82f6;
        }

        .category-card:nth-child(7) .category-icon {
            color: #2563eb;
        }

        .category-card:nth-child(7) .category-label {
            color: #1e40af;
        }

        .category-card:nth-child(7):hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .category-card:nth-child(7):hover .category-icon,
        .category-card:nth-child(7):hover .category-label {
            color: white;
        }

        /* SSB - Gray/Slate */
        .category-card:nth-child(8) {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-color: #64748b;
        }

        .category-card:nth-child(8) .category-icon {
            color: #475569;
        }

        .category-card:nth-child(8) .category-label {
            color: #334155;
        }

        .category-card:nth-child(8):hover {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        }

        .category-card:nth-child(8):hover .category-icon,
        .category-card:nth-child(8):hover .category-label {
            color: white;
        }

        .category-icon {
            font-size: 32px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
        }

        .category-label {
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .category-grid {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
                gap: 10px;
            }

            .category-card {
                height: 90px;
            }

            .category-icon {
                font-size: 28px;
                margin-bottom: 6px;
            }

            .category-label {
                font-size: 11px;
            }
        }
    </style>

    <!-- Category Menu - Horizontal Scroll Below Header -->
    <x-category-menu />

    <!-- States Navigation Bar -->
    <style>
        .states-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(65px, 1fr));
            gap: 8px;
            padding: 12px 0;
        }

        .state-box {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 6px;
            background: white;
            border: 2px solid;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 35px;
            line-height: 1.1;
        }

        /* All India - Special gradient */
        .state-box:first-child {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: #059669;
            font-weight: 700;
        }

        .state-box:first-child:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
        }

        /* Cycle through colors for other states */
        .state-box:nth-child(6n+2) {
            border-color: #14b8a6;
            color: #0f766e;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        }

        .state-box:nth-child(6n+2):hover {
            background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
        }

        .state-box:nth-child(6n+3) {
            border-color: #06b6d4;
            color: #0891b2;
            background: linear-gradient(135deg, #f0fdff 0%, #cffafe 100%);
        }

        .state-box:nth-child(6n+3):hover {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }

        .state-box:nth-child(6n+4) {
            border-color: #6366f1;
            color: #4f46e5;
            background: linear-gradient(135deg, #faf5ff 0%, #c7d2fe 100%);
        }

        .state-box:nth-child(6n+4):hover {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .state-box:nth-child(6n+5) {
            border-color: #8b5cf6;
            color: #7c3aed;
            background: linear-gradient(135deg, #faf5ff 0%, #e9d5ff 100%);
        }

        .state-box:nth-child(6n+5):hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .state-box:nth-child(6n+6) {
            border-color: #64748b;
            color: #475569;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .state-box:nth-child(6n+6):hover {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
        }

        .state-box:nth-child(6n+7) {
            border-color: #ea580c;
            color: #dc2626;
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
        }

        .state-box:nth-child(6n+7):hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
        }

        .state-box:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 767px) {
            .states-grid {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                gap: 6px;
                padding: 8px 4px;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
            }
            .states-grid::-webkit-scrollbar {
                display: none;
            }
            .state-box {
                flex: 0 0 auto;
                font-size: 11px;
                padding: 6px 10px;
                min-height: 30px;
                white-space: nowrap;
                border-radius: 6px;
            }
        }
    </style>
    <!-- States bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="w-full px-2 sm:px-4 lg:px-6">
            <div class="states-grid">
                @php
                    use Illuminate\Support\Str;
                    $allStates = [
                        'Koshi Province',
                        'Madhesh Province',
                        'Bagmati Province',
                        'Gandaki Province',
                        'Lumbini Province',
                        'Karnali Province',
                        'Sudurpashchim Province',
                    ];

                    // Filter states based on domain
                    $domainStateId = config('app.domain_state_id');
                    if ($domainStateId) {
                        $domainState = \App\Models\State::find($domainStateId);
                        if ($domainState) {
                            $allStates = [$domainState->name];
                        }
                    }

                    // Get states with job counts
                    $statesWithJobs = collect($allStates)->map(function ($stateName) use ($states) {
                        $state = $states->firstWhere('name', $stateName);
                        if ($state) {
                            // Get job count for this state
                            $jobCount = \App\Models\Post::where('state_id', $state->id)
                                ->where('is_published', 1)
                                ->count();
                            return $jobCount > 0 ? $state : null;
                        }
                        return null;
                    })->filter(); // Remove null values (states with 0 jobs)

                    // Sort alphabetically
                    $statesWithJobs = $statesWithJobs->sortBy('name');
                @endphp

                <!-- Show state selector only if not domain-filtered -->
                @php
                        // Detect current type from route defaults or request
                        $currentType = request()->route()->defaults['type'] ?? request()->get('type') ?? null;
                        $typeParam   = $currentType ? '?type=' . $currentType : '';
                    @endphp
                    @if (!config('app.domain_state_id'))
                        <!-- All Nepal Box -->
                        <a href="{{ route('posts.all') }}{{ $typeParam }}" class="state-box">
                            All Nepal
                        </a>

                        @foreach ($statesWithJobs as $state)
                            <a href="{{ route('states.show', $state->slug) }}{{ $typeParam }}" class="state-box">
                                {{ $state->name }}
                            </a>
                        @endforeach
                    @else
                        <!-- Show All Nepal + current state for domain-filtered pages -->
                        <a href="http://13.206.244.237/all-posts" class="state-box">
                            All Nepal
                        </a>

                        @foreach ($statesWithJobs as $state)
                            <a href="{{ route('states.show', $state->slug) }}{{ $typeParam }}" class="state-box">
                                {{ $state->name }}
                            </a>
                        @endforeach
                    @endif
            </div>
        </div>
    </div>
    </div><!-- /states bar -->

    <!-- Ad Slot - Header -->
    <x-ad-slot position="header" />

    <!-- Main Content -->
    <main class="w-full px-2 sm:px-3 lg:px-4 py-4">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            <!-- Content Area -->
            <div>
                @yield('content')
            </div>
        </div>
    </main>

    <!-- ═══════════════════════════════════════════════════════
         MOBILE BOTTOM NAVIGATION  (hidden on sm+ screens)
    ═══════════════════════════════════════════════════════ -->
    <nav id="mobile-bottom-nav"
         style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:900;
                background:#fff;border-top:1.5px solid #e5e7eb;
                box-shadow:0 -3px 20px rgba(0,0,0,0.10);">
        <div style="display:flex;height:64px;">

            <a href="{{ route('home') }}" id="botnav-home"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
                      gap:3px;text-decoration:none;color:#9ca3af;transition:color .2s;">
                <i class="fas fa-house" style="font-size:19px;"></i>
                <span style="font-size:10px;font-weight:700;letter-spacing:.3px;">Home</span>
            </a>

            <a href="{{ route('posts.jobs') }}" id="botnav-jobs"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
                      gap:3px;text-decoration:none;color:#9ca3af;transition:color .2s;">
                <i class="fas fa-briefcase" style="font-size:19px;"></i>
                <span style="font-size:10px;font-weight:700;letter-spacing:.3px;">Jobs</span>
            </a>

            <a href="{{ route('posts.admit-cards') }}" id="botnav-admit"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
                      gap:3px;text-decoration:none;color:#9ca3af;transition:color .2s;">
                <i class="fas fa-id-card" style="font-size:19px;"></i>
                <span style="font-size:10px;font-weight:700;letter-spacing:.3px;">Admit</span>
            </a>

            <a href="{{ route('posts.results') }}" id="botnav-results"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
                      gap:3px;text-decoration:none;color:#9ca3af;transition:color .2s;">
                <i class="fas fa-chart-bar" style="font-size:19px;"></i>
                <span style="font-size:10px;font-weight:700;letter-spacing:.3px;">Results</span>
            </a>

            <button id="botnav-more" onclick="moreDrawerToggle()"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
                      gap:3px;background:none;border:none;color:#9ca3af;cursor:pointer;transition:color .2s;">
                <i class="fas fa-ellipsis" style="font-size:19px;"></i>
                <span style="font-size:10px;font-weight:700;letter-spacing:.3px;">More</span>
            </button>

        </div>
    </nav>

    <!-- Overlay backdrop -->
    <div id="more-overlay"
         onclick="moreDrawerToggle()"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.40);z-index:901;"></div>

    <!-- Slide-up More Drawer -->
    <div id="more-drawer"
         style="display:none;position:fixed;bottom:64px;left:0;right:0;z-index:902;
                background:#fff;border-radius:22px 22px 0 0;
                box-shadow:0 -6px 40px rgba(0,0,0,0.16);
                padding:12px 16px 24px;
                animation:slideUp .25s ease-out;">

        <!-- Pull handle -->
        <div style="width:36px;height:4px;background:#d1d5db;border-radius:99px;margin:0 auto 18px;"></div>

        <!-- 3-column icon grid -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">

            <a href="{{ route('posts.syllabus') }}"
               style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;
                      border-radius:14px;background:#eef2ff;border:1.5px solid #c7d2fe;text-decoration:none;">
                <i class="fas fa-book" style="font-size:24px;color:#4f46e5;"></i>
                <span style="font-size:11px;font-weight:700;color:#3730a3;text-align:center;">Syllabus</span>
            </a>

            <a href="{{ route('posts.answer-keys') }}"
               style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;
                      border-radius:14px;background:#fefce8;border:1.5px solid #fde68a;text-decoration:none;">
                <i class="fas fa-key" style="font-size:24px;color:#ca8a04;"></i>
                <span style="font-size:11px;font-weight:700;color:#92400e;text-align:center;">Answer Keys</span>
            </a>

            <a href="{{ route('posts.blogs') }}"
               style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;
                      border-radius:14px;background:#fdf2f8;border:1.5px solid #fbcfe8;text-decoration:none;">
                <i class="fas fa-pen-fancy" style="font-size:24px;color:#db2777;"></i>
                <span style="font-size:11px;font-weight:700;color:#9d174d;text-align:center;">Blogs</span>
            </a>

            <a href="{{ route('posts.scholarships') }}"
               style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;
                      border-radius:14px;background:#f0fdfa;border:1.5px solid #99f6e4;text-decoration:none;">
                <i class="fas fa-graduation-cap" style="font-size:24px;color:#0d9488;"></i>
                <span style="font-size:11px;font-weight:700;color:#0f766e;text-align:center;">Scholarships</span>
            </a>

            <a href="{{ route('search') }}"
               style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;
                      border-radius:14px;background:#eff6ff;border:1.5px solid #bfdbfe;text-decoration:none;">
                <i class="fas fa-search" style="font-size:24px;color:#2563eb;"></i>
                <span style="font-size:11px;font-weight:700;color:#1d4ed8;text-align:center;">Search</span>
            </a>

            <button onclick="moreDrawerToggle();setTimeout(()=>{if(window.notificationManager)window.notificationManager.showFeedbackModal();},220);"
               style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;
                      border-radius:14px;background:#faf5ff;border:1.5px solid #e9d5ff;
                      cursor:pointer;">
                <i class="fas fa-comment-dots" style="font-size:24px;color:#9333ea;"></i>
                <span style="font-size:11px;font-weight:700;color:#7e22ce;text-align:center;">Feedback</span>
            </button>

        </div>
    </div>

    <style>
        @@keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }
    </style>

    <script>
        // Show bottom nav only on mobile
        (function() {
            function applyMobileNav() {
                const nav = document.getElementById('mobile-bottom-nav');
                if (!nav) return;
                nav.style.display = window.innerWidth < 640 ? 'block' : 'none';
                // body padding
                document.body.style.paddingBottom = window.innerWidth < 640 ? '64px' : '0';
            }
            applyMobileNav();
            window.addEventListener('resize', applyMobileNav);
        })();

        // Drawer toggle
        function moreDrawerToggle() {
            const drawer  = document.getElementById('more-drawer');
            const overlay = document.getElementById('more-overlay');
            const btn     = document.getElementById('botnav-more');
            const isOpen  = drawer.style.display !== 'none' && drawer.style.display !== '';
            if (isOpen) {
                drawer.style.display  = 'none';
                overlay.style.display = 'none';
                btn.style.color = '#9ca3af';
            } else {
                // Only show on mobile
                if (window.innerWidth >= 640) return;
                drawer.style.display  = 'block';
                overlay.style.display = 'block';
                btn.style.color = '#2563eb';
            }
        }

        // Active tab highlighting
        (function() {
            const path = window.location.pathname;
            const map = {
                'botnav-home':    ['/', '/home'],
                'botnav-jobs':    ['/jobs'],
                'botnav-admit':   ['/admit-cards'],
                'botnav-results': ['/results'],
            };
            Object.entries(map).forEach(([id, paths]) => {
                const el = document.getElementById(id);
                if (!el) return;
                if (paths.some(p => path === p || path.startsWith(p + '/'))) {
                    el.style.color      = '#2563eb';
                    el.style.borderTop  = '2.5px solid #2563eb';
                    el.style.background = '#eff6ff';
                }
            });
            // Highlight More for secondary pages
            const morePaths = ['/syllabus','/answer-keys','/blogs','/scholarships','/all-posts','/search'];
            if (morePaths.some(p => path === p || path.startsWith(p + '/'))) {
                const btn = document.getElementById('botnav-more');
                if (btn) { btn.style.color = '#2563eb'; btn.style.background = '#eff6ff'; }
            }
        })();
    </script>


    <!-- Floating Social Buttons - Right Side Middle (visible on all screens) -->
    <div class="fixed top-1/2 transform -translate-y-1/2 z-40 flex flex-col gap-2 md:gap-4 drop-shadow-lg"
         style="right:0;">
        <!-- WhatsApp Button -->
        <a href="{{ \App\Models\SiteSetting::where('key', 'whatsapp_channel')->value('value') ?: 'https://whatsapp.com/channel/0029VbD9cau2P59hFZ1nwh22' }}" target="_blank" rel="noopener noreferrer"
            class="flex items-center justify-center md:w-14 md:h-14 text-white shadow-2xl transition-all duration-300 hover:scale-110"
            style="background-color: #25D366 !important; color: white !important; text-decoration: none !important; width:32px; height:36px; border-radius:6px 0 0 6px;">
            <i class="fab fa-whatsapp" style="font-size:18px;color:white !important;"></i>
        </a>

        <!-- Telegram Button -->
        <a href="{{ \App\Models\SiteSetting::where('key', 'telegram_url')->value('value') ?: 'https://t.me/loksewaalert' }}" target="_blank" rel="noopener noreferrer"
            class="flex items-center justify-center md:w-14 md:h-14 text-white shadow-2xl transition-all duration-300 hover:scale-110"
            style="background-color: #0088cc !important; color: white !important; text-decoration: none !important; width:32px; height:36px; border-radius:6px 0 0 6px;">
            <i class="fab fa-telegram-plane" style="font-size:18px;color:white !important;"></i>
        </a>
    </div>

    <!-- Ad Slot - Footer -->
    <x-ad-slot position="footer" />

    <!-- Footer -->
    <footer
        class="bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900 text-white mt-0 border-t-4 border-blue-500"
        style="background: linear-gradient(135deg, #1f2937 0%, #1e3a8a 50%, #312e81 100%) !important;">
        <div class="w-full px-2 sm:px-4 lg:px-6 py-12">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-8">
                <!-- About Section -->
                <div>
                    <h4 class="text-white font-black mb-4 text-lg flex items-center gap-2"
                        style="color: white !important; font-weight: 900 !important;">
                        <i class="fas fa-info-circle text-blue-400"></i> About LokSewaAlert
                    </h4>
                    <p class="text-gray-300 text-sm mb-4 leading-relaxed">Your trusted source for latest government job
                        notifications, results, admit cards, and exam updates across Nepal.</p>
                    <ul class="space-y-2">
                        <li><a href="{{ route('pages.about') }}"
                                class="text-gray-300 hover:text-blue-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-angle-right"></i> About Us</a></li>
                        <li><a href="{{ route('pages.contact') }}"
                                class="text-gray-300 hover:text-blue-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-angle-right"></i> Contact Us</a>
                        </li>
                    </ul>
                </div>

                <!-- Quick Links Section -->
                <div>
                    <h4 class="text-white font-black mb-4 text-lg flex items-center gap-2"
                        style="color: white !important; font-weight: 900 !important;">
                        <i class="fas fa-link text-green-400"></i> Quick Links
                    </h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('posts.jobs') }}"
                                class="text-gray-300 hover:text-green-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-briefcase text-xs"></i> Latest
                                Jobs</a></li>
                        <li><a href="{{ route('posts.results') }}"
                                class="text-gray-300 hover:text-green-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-chart-bar text-xs"></i> Results</a>
                        </li>
                        <li><a href="{{ route('posts.admit-cards') }}"
                                class="text-gray-300 hover:text-green-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-id-card text-xs"></i> Admit
                                Cards</a></li>
                        <li><a href="{{ route('posts.syllabus') }}"
                                class="text-gray-300 hover:text-green-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-book text-xs"></i> Syllabus</a></li>
                    </ul>
                </div>

                <!-- Legal & Social Section -->
                <div>
                    <h4 class="text-white font-black mb-4 text-lg flex items-center gap-2"
                        style="color: white !important; font-weight: 900 !important;">
                        <i class="fas fa-shield-alt text-purple-400"></i> Legal & Social
                    </h4>
                    <ul class="space-y-2 mb-4">
                        <li><a href="{{ route('pages.privacy') }}"
                                class="text-gray-300 hover:text-purple-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-lock text-xs"></i> Privacy
                                Policy</a></li>
                        <li><a href="{{ route('pages.disclaimer') }}"
                                class="text-gray-300 hover:text-purple-400 text-sm font-semibold transition-colors flex items-center gap-2"
                                style="color: #d1d5db !important;"><i class="fas fa-exclamation-triangle text-xs"></i>
                                Disclaimer</a></li>
                    </ul>
                    <div class="flex gap-3 mt-4">
                        <a href="{{ \App\Models\SiteSetting::where('key', 'telegram_url')->value('value') ?: 'https://t.me/loksewaalert' }}" target="_blank"
                            class="w-10 h-10 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110"
                            style="background-color: #0088cc !important;">
                            <i class="fab fa-telegram-plane text-white text-lg"></i>
                        </a>
                        <a href="{{ \App\Models\SiteSetting::where('key', 'whatsapp_channel')->value('value') ?: 'https://whatsapp.com/channel/0029VbD9cau2P59hFZ1nwh22' }}" target="_blank"
                            class="w-10 h-10 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110"
                            style="background-color: #25D366 !important;">
                            <i class="fab fa-whatsapp text-white text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Section -->
                <div>
                    <h4 class="text-white font-black mb-4 text-lg flex items-center gap-2"
                        style="color: white !important; font-weight: 900 !important;">
                        <i class="fas fa-envelope text-yellow-400"></i> Get In Touch
                    </h4>
                    @php
                        $contactEmail = \App\Models\SiteSetting::where('key', 'contact_email')->value('value');
                        $phone = \App\Models\SiteSetting::where('key', 'phone')->value('value');
                        $androidAppUrl = \App\Models\SiteSetting::where('key', 'android_app_url')->value('value');
                    @endphp
                    @if($contactEmail)
                        <p class="mb-3 text-gray-300 text-sm font-semibold flex items-center gap-2"
                            style="color: #d1d5db !important;"><i class="fas fa-envelope text-yellow-400"></i>
                            {{ $contactEmail }}</p>
                    @endif
                    @if($phone)
                        <p class="mb-4 text-gray-300 text-sm font-semibold flex items-center gap-2"
                            style="color: #d1d5db !important;"><i class="fas fa-phone text-yellow-400"></i> {{ $phone }}</p>
                    @endif
                    @if($androidAppUrl)
                        <a href="{{ $androidAppUrl }}" target="_blank"
                            class="inline-block transform hover:scale-105 transition-transform">
                            <img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"
                                alt="Get it on Google Play" style="height: 50px; width: auto;" loading="lazy">
                        </a>
                    @endif
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-700 pt-6 mt-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-400 text-sm font-semibold text-center md:text-left"
                        style="color: #9ca3af !important;">
                        &copy; 2026 LokSewaAlert.com. All rights reserved.
                    </p>
                    <p class="text-gray-400 text-sm font-semibold flex items-center gap-2"
                        style="color: #9ca3af !important;">
                        Made with <i class="fas fa-heart text-red-500 animate-pulse"></i> for Job Seekers
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Google Translate Script - Load AFTER page load (non-blocking) -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,te,ta,kn,ml,mr,gu,bn,pa,or,as'
            }, 'google_translate_element');

            // Wait for Google Translate to load
            setTimeout(function () {
                var combo = document.querySelector('.goog-te-combo');
                if (combo) {
                    // Restore saved language on page load
                    var savedLang = localStorage.getItem('selectedLanguage');
                    if (savedLang) {
                        combo.value = savedLang;
                        combo.dispatchEvent(new Event('change'));

                        var customSelect = document.getElementById('custom_language_select');
                        if (customSelect) {
                            customSelect.value = savedLang;
                        }
                    }

                    // Sync custom dropdown with Google Translate
                    var customSelect = document.getElementById('custom_language_select');
                    if (customSelect) {
                        customSelect.addEventListener('change', function () {
                            if (this.value === '') {
                                // For English, reset Google Translate
                                combo.value = 'en';
                                combo.dispatchEvent(new Event('change'));

                                // Clear localStorage
                                localStorage.removeItem('selectedLanguage');
                            } else {
                                // Change to selected language
                                combo.value = this.value;
                                combo.dispatchEvent(new Event('change'));

                                // Save to localStorage
                                localStorage.setItem('selectedLanguage', this.value);
                            }
                        });
                    }

                    // Language chooser buttons
                    var languageButtons = document.querySelectorAll('.language-btn');
                    languageButtons.forEach(function (button) {
                        button.addEventListener('click', function () {
                            var langCode = this.getAttribute('data-lang');
                            if (langCode === '') {
                                combo.value = 'en';
                                combo.dispatchEvent(new Event('change'));
                                localStorage.removeItem('selectedLanguage');
                            } else {
                                combo.value = langCode;
                                combo.dispatchEvent(new Event('change'));
                                localStorage.setItem('selectedLanguage', langCode);
                            }

                            // Update custom select
                            var customSelect = document.getElementById('custom_language_select');
                            if (customSelect) {
                                customSelect.value = langCode;
                            }
                        });
                    });
                }
            }, 1500);
        }
        
        // Load Google Translate AFTER page load (non-blocking)
        window.addEventListener('load', function() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            script.async = true;
            document.body.appendChild(script);
        });
    </script>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            var mobileMenuButton = document.getElementById('mobile-menu-button');
            var mobileMenu = document.getElementById('mobile-menu');
            var mobileMenuIcon = document.getElementById('mobile-menu-icon');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');

                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenuIcon.className = 'fas fa-bars text-lg';
                    } else {
                        mobileMenuIcon.className = 'fas fa-times text-lg';
                    }
                });
            }
        });
    </script>

    <!-- Toast Notification Component -->
    <x-toast-notification />

    <!-- Back to Top Button -->
    <x-back-to-top />
    <!-- Web Notification & Feedback Controls -->
    <x-notification-controls />

    <!-- Web Notifications Script -->
    <script src="{{ asset('js/web-notifications.js') }}"></script>
</body>

</html>
