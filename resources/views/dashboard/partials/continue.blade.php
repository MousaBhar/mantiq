{{-- Learner shortcuts: search, mentor, certificates, continue learning --}}
<section style="margin-bottom: 26px;">

    <form action="{{ route('search') }}" method="GET"
          style="display:flex; gap:10px; margin-bottom:18px;">
        <input type="search" name="q" placeholder="ابحث عن دورة أو درس…" dir="auto"
               style="flex:1; padding:13px 16px; border:1px solid #e3d3e3; border-radius:12px; font-size:15px; background:#fff;">
        <button type="submit" class="learning-view-button" style="border:0; cursor:pointer;">Search</button>
    </form>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:14px; margin-bottom: 22px;">
        <a href="{{ route('assistant.index') }}"
           style="text-decoration:none; background:#fff; border:1px solid #eee5f0; border-radius:16px; padding:18px; display:block;">
            <strong style="color:#800080;">💬 {{ config('assistant.name') }}</strong>
            <p style="margin:6px 0 0; color:#777; font-size:14px;">اسأل عن أي درس أو اطلب ترشيح دورة.</p>
        </a>
        <a href="{{ route('certificates.index') }}"
           style="text-decoration:none; background:#fff; border:1px solid #eee5f0; border-radius:16px; padding:18px; display:block;">
            <strong style="color:#800080;">🎓 My Certificates ({{ $certificatesCount }})</strong>
            <p style="margin:6px 0 0; color:#777; font-size:14px;">شهاداتك عند إكمال الدورات.</p>
        </a>
    </div>

    @if($continueLearning->isNotEmpty())
        <div class="dashboard-section-heading">
            <h2>Continue Learning</h2>
            <p>Pick up where you left off.</p>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:14px;">
            @foreach($continueLearning as $item)
                <a href="{{ route('learning.show', $item->content) }}"
                   style="text-decoration:none; color:inherit; background:#fff; border:1px solid #eee5f0; border-radius:16px; padding:18px; display:block;">
                    <strong dir="auto">{{ $item->content->title }}</strong>
                    <div style="height:8px; background:#eee; border-radius:20px; overflow:hidden; margin:12px 0 8px;">
                        <div style="width:{{ $item->percent }}%; height:100%; background:#800080;"></div>
                    </div>
                    <small style="color:#777;">{{ $item->done }}/{{ $item->total }} lessons · {{ $item->percent }}%</small>
                </a>
            @endforeach
        </div>
    @endif
</section>
