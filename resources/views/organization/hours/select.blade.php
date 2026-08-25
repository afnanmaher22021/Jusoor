@extends('layouts.app')

@section('title', 'تسجيل الساعات التطوعية — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('organization.dashboard') }}">الرئيسية</a>
            <a href="{{ route('organization.opportunities.index') }}">إدارة الفرص</a>
            <a href="{{ route('organization.applications') }}">طلبات التطوع</a>
            <a href="{{ route('organization.hours.select') }}" class="active">تسجيل الساعات</a>
            <a href="{{ route('profile.edit') }}">الملف المؤسسي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>تسجيل الساعات التطوعية المعتمدة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">اختر الفرصة التطوعية لعرض قائمة المتطوعين المقبولين وتسجيل ساعاتهم</p>
            </div>
        </div>

        <div class="panel">
            <h2>الفرص التطوعية المتاحة</h2>

            @if ($opportunities->isNotEmpty())
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                    @foreach ($opportunities as $opp)
                        <div class="opp-card">
                            <div class="opp-body">
                                <div class="opp-cat">{{ $opp->category?->name ?? 'تطوع' }}</div>
                                <div class="opp-title" style="font-size:16px;">{{ $opp->title }}</div>
                                <div style="font-size:13px;color:var(--text-muted);">
                                    المتطوعون المقبولون: <strong>{{ $opp->acceptedCount() }}</strong>
                                </div>
                            </div>
                            <div class="opp-foot">
                                <a href="{{ route('organization.hours.manage', $opp) }}" class="btn btn-green btn-sm">إدارة وتسجيل الساعات</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty">
                    <p>لا توجد فرص تطوعية منشورة حالياً. يرجى نشر فرصة أولاً.</p>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
