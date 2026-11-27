@extends('layouts.app')

@section('title', 'Mantiq - Verify Certificate')
@section('meta_description', 'تحقّق من صحة شهادة صادرة عن منصة منطق.')

@section('content')
<main class="dashboard-container" style="max-width:640px;margin:60px auto;text-align:center;">
    <img src="{{ asset('images/brand/logo-mark.svg') }}" alt="Mantiq" width="64" height="64" style="margin:0 auto 14px;">
    <h1 style="color:#29212d;">Certificate verification</h1>

    @if($certificate)
        <div class="learning-course-card" style="margin-top:22px;">
            <div class="learning-course-info">
                <p style="color:green;font-weight:700;font-size:18px;margin:0 0 12px;">✓ Valid certificate</p>
                <p dir="auto" style="margin:4px 0;"><strong>{{ $certificate->user->name }}</strong></p>
                <p dir="auto" style="margin:4px 0;">{{ $certificate->content->title }}</p>
                <small style="color:#777;">Issued {{ $certificate->issued_at->format('F j, Y') }} · {{ $certificate->code }}</small>
            </div>
        </div>
    @else
        <div class="learning-course-card" style="margin-top:22px;">
            <div class="learning-course-info">
                <p style="color:#b42318;font-weight:700;margin:0 0 8px;">✗ Certificate not found</p>
                <small style="color:#777;">No certificate matches the code “{{ \Illuminate\Support\Str::limit($code, 40) }}”.</small>
            </div>
        </div>
    @endif
</main>
@endsection
