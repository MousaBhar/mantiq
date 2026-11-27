@php
    $mqTitle = trim($__env->yieldContent('title', 'Mantiq'));
    $mqDescription = trim($__env->yieldContent(
        'meta_description',
        'Mantiq — منصة منطق للتعلّم التقني والمهني: دورات ودروس وبودكاست ومرشد يرافقك خطوة بخطوة.'
    ));
    $mqImage = asset('images/brand/og-image.png');
@endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $mqTitle }}</title>
<meta name="description" content="{{ $mqDescription }}">
<meta name="theme-color" content="#800080">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Icons / PWA --}}
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

{{-- Link preview (WhatsApp, Telegram, X, LinkedIn, Facebook...) --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="Mantiq">
<meta property="og:locale" content="ar_AR">
<meta property="og:title" content="{{ $mqTitle }}">
<meta property="og:description" content="{{ $mqDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $mqImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $mqTitle }}">
<meta name="twitter:description" content="{{ $mqDescription }}">
<meta name="twitter:image" content="{{ $mqImage }}">
