@extends('layouts.app')

@section('title', 'Mantiq - My Certificates')

@section('content')
<div class="dashboard-page">
    <main class="dashboard-container">
        <header class="dashboard-header">
            <h1>My Certificates</h1>
            <a href="{{ route('dashboard') }}" class="coffee-button">Back to Dashboard</a>
        </header>

        @forelse($certificates as $certificate)
            <article class="learning-course-card" style="margin-bottom:14px;">
                <div class="learning-course-info" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                    <div>
                        <h3 dir="auto" style="margin:0 0 6px;">{{ $certificate->content->title }}</h3>
                        <small style="color:#777;">
                            Issued {{ $certificate->issued_at->format('M j, Y') }} · {{ $certificate->code }}
                        </small>
                    </div>
                    <a href="{{ route('certificates.show', $certificate) }}" class="learning-view-button">View</a>
                </div>
            </article>
        @empty
            <div class="learning-empty-state">
                <h3>No certificates yet</h3>
                <p>Complete every lesson of a course and your certificate appears here automatically.</p>
            </div>
        @endforelse
    </main>
</div>
@endsection
