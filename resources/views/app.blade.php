@php
    $siteSettings = \App\Models\Setting::getAll();
    $siteName = $siteSettings['site_name'] ?? 'NUWESOFT';
    $logoUrl = $siteSettings['logo_url'] ?? null;
    $tagline = $siteSettings['tagline'] ?? '';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ $siteName }}</title>

        <!-- Favicon -->
        @if($logoUrl)
        <link rel="icon" type="image/svg+xml" href="{{ $logoUrl }}">
        @endif
        <link rel="alternate icon" href="/favicon.ico">

        <!-- Open Graph / Social -->
        <meta property="og:site_name" content="{{ $siteName }}" />
        <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}" />
        @if($logoUrl)
        <meta property="og:image" content="{{ $logoUrl }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        @endif
        <meta name="description" content="{{ $tagline }}" />
        <meta name="twitter:card" content="summary_large_image" />

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- RSS Feeds -->
        <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} — Portafolio" href="{{ url('/rss.xml') }}" />
        <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} — Blog" href="{{ url('/rss/blog.xml') }}" />

<script type="application/ld+json">
{!! json_encode(\App\Helpers\SchemaHelper::organization($siteSettings), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode(\App\Helpers\SchemaHelper::website($siteSettings), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<script type="application/ld+json">
{!! json_encode(\App\Helpers\SchemaHelper::breadcrumb([
    ['name' => 'Home', 'url' => url('/')],
    ['name' => $siteName, 'url' => url()->current()]
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Prevent flash of wrong theme: apply class before first paint -->
        <script>
            (function() {
                var theme = localStorage.getItem('theme');
                var isDark = theme === 'dark' ||
                    (theme !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (isDark) document.documentElement.classList.add('dark');
            })();
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased" role="document">
        <!-- Skip to content link (PublicSiteHeader.vue renders the primary one) -->
        <a href="#main-content" 
           class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-6 focus:py-3 focus:bg-white focus:text-black focus:border-4 focus:border-black focus:font-black focus:text-sm focus:uppercase focus:tracking-wider focus:shadow-brutalist focus:outline-none">
            Saltar al contenido
        </a>
        @inertia
    </body>
</html>
