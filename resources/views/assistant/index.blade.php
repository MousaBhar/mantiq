@extends('layouts.app')

@section('title', 'Mantiq - ' . config('assistant.name'))
@section('meta_description', 'تحدّث مع مرشد منطق: ترشيح دورات، تبسيط دروس، وخطوات واضحة لتقدّمك.')

@section('content')
<div class="dashboard-page">
    <main class="dashboard-container">

        <header class="dashboard-header">
            <h1>{{ config('assistant.name') }}</h1>
            <a href="{{ route('dashboard') }}" class="coffee-button">Back to Dashboard</a>
        </header>

        <div style="display:grid; grid-template-columns: 260px 1fr; gap:18px; align-items:start;" class="mq-ai-layout">

            <aside class="learning-course-card" style="padding:16px;">
                <a href="{{ route('assistant.index') }}" class="learning-view-button" style="display:block;text-align:center;margin-bottom:12px;">
                    + محادثة جديدة
                </a>

                @forelse($conversations as $c)
                    <a href="{{ route('assistant.index', ['c' => $c->id]) }}"
                       dir="auto"
                       style="display:block;padding:9px 10px;border-radius:10px;text-decoration:none;color:#292333;font-size:14px;
                              {{ $active && $active->id === $c->id ? 'background:#f5eaf5;color:#690069;font-weight:600;' : '' }}">
                        {{ $c->title ?: 'محادثة' }}
                    </a>
                @empty
                    <p style="color:#898391;font-size:13px;margin:6px 4px;">لا توجد محادثات سابقة بعد.</p>
                @endforelse
            </aside>

            <section>
                <x-assistant-widget
                    :fullpage="true"
                    :messages="$active ? $active->messages : []"
                    :conversation-id="$active?->id" />
            </section>
        </div>

    </main>
</div>

<style>
    @media (max-width: 820px) { .mq-ai-layout { grid-template-columns: 1fr !important; } }
</style>
@endsection
