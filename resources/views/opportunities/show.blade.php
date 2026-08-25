@extends('layouts.app')

@section('title', $opportunity->title . ' — منصة جسور')

@section('content')
<section class="section" style="padding-top:35px;">
    <div class="container opp-detail">
        <div class="opp-detail-cover">{{ $opportunity->category?->name ?? 'فرصة تطوعية' }}</div>

        <h1>{{ $opportunity->title }}</h1>
        <div class="opp-meta">
            <span>الموقع: {{ $opportunity->location }}</span>
            <span>الالتزام: {{ $opportunity->required_hours }} ساعة</span>
            <span>الجهة المنظمة: {{ $opportunity->organization?->name }}</span>
            <span>الفترة: {{ $opportunity->starts_at?->translatedFormat('d M Y') }} — {{ $opportunity->ends_at?->translatedFormat('d M Y') }}</span>
        </div>
        <div class="badge badge-{{ $opportunity->status }}">
            {{ match ($opportunity->status) { 'open' => 'متاحة للتقديم', 'closed' => 'مغلقة', 'completed' => 'منتهية' } }}
        </div>

        <div class="content" style="margin-top:20px;">
            <h3>تفاصيل الفرصة التطوعية</h3>
            <p style="white-space:pre-line;line-height:1.8;">{{ $opportunity->description }}</p>

            @if ($opportunity->skills_required)
                <h3 style="margin-top:20px;">المهارات والمتطلبات</h3>
                <p style="white-space:pre-line;line-height:1.8;">{{ $opportunity->skills_required }}</p>
            @endif

            <div style="margin-top:24px;border-top:1px solid var(--border-light);padding-top:16px;">
                <div class="capacity-bar" style="height:8px;margin-bottom:8px;">
                    <div class="capacity-fill" style="width:{{ min(100, $acceptedCount / max(1, $opportunity->max_volunteers) * 100) }}%"></div>
                </div>
                <span style="color:var(--text-muted);font-size:13.5px;">المقاعد المشغولة: <strong style="color:var(--navy);">{{ $acceptedCount }}</strong> من إجمالي {{ $opportunity->max_volunteers }} مقعد</span>
            </div>
        </div>

        <div class="opp-apply">
            @auth
                @if (auth()->user()->isVolunteer())
                    @if ($opportunity->status !== 'open')
                        <p style="color:var(--text-muted);text-align:center;font-weight:600;">هذه الفرصة غير متاحة للتقديم حالياً.</p>
                    @elseif ($opportunity->isFull())
                        <p style="color:var(--amber);text-align:center;font-weight:700;">اكتمل عدد المتطوعين المقبولين لهذه الفرصة.</p>
                    @elseif ($hasApplied)
                        <div style="text-align:center;padding:10px;">
                            <span class="badge badge-accepted" style="font-size:14px;padding:6px 16px;">تم تقديم طلبك لهذه الفرصة وهو قيد المتابعة</span>
                            <div style="margin-top:10px;">
                                <a href="{{ route('volunteer.applications') }}" class="btn btn-ghost btn-sm">متابعة طلباتي</a>
                            </div>
                        </div>
                    @else
                        <h3 style="color:var(--navy);font-size:17px;margin-bottom:14px;font-weight:700;">التقديم على هذه الفرصة</h3>
                        <form method="POST" action="{{ route('opportunities.apply', $opportunity) }}">
                            @csrf
                            <div class="form-group">
                                <label for="message">رسالة / نبذة للمؤسسة (اختياري)</label>
                                <textarea id="message" name="message" class="form-control" placeholder="وضح دوافعك ومهاراتك المناسبة لهذه الفرصة..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-green btn-block" style="padding:12px;">إرسال طلب التطوع</button>
                        </form>
                    @endif
                @else
                    <p style="color:var(--text-muted);text-align:center;font-weight:600;">التقديم على الفرص متاح للمتطوعين المسجلين في المنصة.</p>
                @endif
            @else
                <div style="text-align:center;padding:10px;">
                    <p style="color:var(--text-muted);margin-bottom:16px;font-weight:600;">سجّل الدخول إلى حسابك أو أنشئ حساباً جديداً للتقديم على هذه الفرصة.</p>
                    <div style="display:flex;gap:10px;justify-content:center;">
                        <a href="{{ route('login') }}" class="btn btn-green">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="btn btn-outline">إنشاء حساب جديد</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</section>
@endsection
