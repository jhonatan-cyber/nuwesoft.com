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

        @php
            $socialLinks = array_filter([
                $siteSettings['social_facebook'] ?? null,
                $siteSettings['social_twitter'] ?? null,
                $siteSettings['social_linkedin'] ?? null,
                $siteSettings['social_github'] ?? null,
            ]);
            $orgEmail = $siteSettings['email'] ?? '';
        @endphp

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "{{ $siteName }}",
    "url": "{{ url('/') }}",
    @if($logoUrl)"logo": "{{ $logoUrl }}",
    @endif"description": "{{ $tagline }}",
    "foundingDate": "2024",
    @if($orgEmail)"contactPoint": {
        "@@type": "ContactPoint",
        "contactType": "customer support",
        "email": "{{ $orgEmail }}",
        "availableLanguage": ["English", "Spanish"]
    },
    @endif"sameAs": {{ json_encode(array_values($socialLinks)) }}
}
</script>

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ url('/') }}",
    "description": "{{ $tagline }}",
    "potentialAction": {
        "@@type": "SearchAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ url('/') }}/?s={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "name": "{{ $siteName }} Breadcrumb",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "{{ $siteName }}",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <!-- Skip to content link -->
        <a href="#main-content" 
           class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-6 focus:py-3 focus:bg-white focus:text-black focus:border-4 focus:border-black focus:font-black focus:text-sm focus:uppercase focus:tracking-wider focus:shadow-brutalist focus:outline-none">
            Saltar al contenido
        </a>
        @inertia
    </body>
</html>
