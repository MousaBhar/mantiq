@extends('layouts.app')

@section('title', 'Mantiq - Certificate ' . $certificate->code)

@section('content')
<style>
    .cert-wrap { max-width: 900px; margin: 30px auto; padding: 0 16px; }
    .cert {
        background: #fff; border: 2px solid #800080; border-radius: 18px; padding: 54px 40px; text-align: center;
        outline: 8px solid #f5eaf5; outline-offset: -18px; position: relative;
    }
    .cert img { margin: 0 auto 14px; }
    .cert h1 { font-size: 34px; color: #800080; margin: 6px 0 4px; letter-spacing: 1px; }
    .cert .name { font-size: 38px; color: #29212d; margin: 18px 0; font-weight: 700; }
    .cert .course { font-size: 24px; color: #690069; margin: 10px 0 24px; }
    .cert small { color: #777; display: block; margin-top: 6px; }
    .cert-actions { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
    @media print {
        .cert-actions, .mq-ai { display: none !important; }
        body { background: #fff; }
        .cert-wrap { margin: 0; max-width: none; }
    }
</style>

<div class="cert-wrap">
    <div class="cert">
        <img src="{{ asset('images/brand/logo-mark.svg') }}" alt="Mantiq" width="64" height="64">
        <h1>Certificate of Completion</h1>
        <p style="color:#777;margin:0;">شهادة إتمام</p>

        <p style="margin-top:30px;color:#777;">This certifies that</p>
        <div class="name" dir="auto">{{ $certificate->user->name }}</div>
        <p style="color:#777;">has successfully completed all lessons of</p>
        <div class="course" dir="auto">{{ $certificate->content->title }}</div>

        <small>Issued on {{ $certificate->issued_at->format('F j, Y') }}</small>
        <small>Certificate ID: <strong>{{ $certificate->code }}</strong></small>
        <small>Verify at {{ route('certificates.verify', $certificate->code) }}</small>
    </div>

    <div class="cert-actions">
        <button type="button" class="learning-view-button" style="border:0;cursor:pointer;" onclick="window.print()">Print / Save as PDF</button>
        <a href="{{ route('certificates.index') }}" class="coffee-button">Back</a>
    </div>
</div>
@endsection
