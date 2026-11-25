@extends('layouts.app')

@section('content')

    <div class="admin-layout">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <main class="admin-main">

            @yield('admin-content')

        </main>

    </div>

@endsection