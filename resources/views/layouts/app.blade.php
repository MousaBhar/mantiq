<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @yield('content')

    {{-- Learning mentor: for signed-in learners on learner-facing pages --}}
    @auth
        @unless(request()->routeIs('admin.*', 'assistant.*', 'certificates.show'))
            <x-assistant-widget :lesson-id="trim($__env->yieldContent('assistant-lesson')) ?: null" />
        @endunless
    @endauth

    @stack('scripts')
</body>
</html>
