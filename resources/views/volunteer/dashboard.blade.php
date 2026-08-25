@extends('layouts.app')

@section('title', 'لوحة تحكم المتطوع — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('volunteer.dashboard') }}" class="active">الرئيسية</a>
            <a href="{{ route('browse') }}">استكشف الفرص</a>
            <a href="{{ route('volunteer.applications') }}">طلبات التطوع</a>
            <a href="{{ route('volunteer.hours') }}">سجل الساعات</a>
            <a href="{{ route('volunteer.certificate') }}">شهادة التطوع</a>
            <a href="{{ route('profile.edit') }}">الملف الشخصي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>لوحة تحكم المتطوع</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">أهلاً بك، {{ $user->name }}</p>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('volunteer.certificate') }}" class="btn btn-outline btn-sm">إصدار الشهادة الرسمية</a>
                <a href="{{ route('browse') }}" class="btn btn-green btn-sm">+ استكشاف الفرص</a>
            </div>
        </div>

        <div class="dash-cards">
            <div class="dash-card">
                <div class="dc-icon">ساعات الشهر الحالي</div>
                <div class="dc-num">{{ number_format($monthHours, 1) }}</div>
                <div class="dc-lbl">ساعة معتمدة هذا الشهر</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الساعات المعتمدة</div>
                <div class="dc-num">{{ number_format($totalHours, 1) }}</div>
                <div class="dc-lbl">ساعة تطوعية موثقة</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">الهدف الشهري المستهدف</div>
                <div class="dc-num">{{ $user->monthly_hours_goal }}</div>
                <div class="dc-lbl">ساعة مستهدفة شهرياً</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">الأنشطة القادمة</div>
                <div class="dc-num">{{ $upcoming->count() }}</div>
                <div class="dc-lbl">مبادرة مقبولة ونشطة</div>
            </div>
        </div>

        <div class="panel">
            <h2>معدل الإنجاز نحو الهدف الشهري</h2>
            <div class="progress-wrap">
                <div class="progress-top">
                    <span>تم إنجاز <strong>{{ $monthHours }}</strong> ساعة من أصل <strong>{{ $goal }}</strong> ساعة</span>
                    <strong>{{ round(min(100, ($monthHours / $goal) * 100)) }}%</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ min(100, ($monthHours / $goal) * 100) }}%"></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <h2>الأنشطة والمبادرات المقبولة</h2>
            @if ($upcoming->isNotEmpty())
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>اسم الفرصة</th>
                                <th>المؤسسة المنظمة</th>
                                <th>الموقع</th>
                                <th>تاريخ البدء</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcoming as $app)
                                <tr>
                                    <td>
                                        <a href="{{ route('opportunities.show', $app->opportunity) }}" style="color:var(--navy);font-weight:700;">{{ $app->opportunity->title }}</a>
                                    </td>
                                    <td>{{ $app->opportunity->organization?->name }}</td>
                                    <td>{{ $app->opportunity->location }}</td>
                                    <td>{{ $app->opportunity->starts_at?->translatedFormat('d M Y') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('opportunities.show', $app->opportunity) }}" class="btn btn-ghost btn-sm">عرض التفاصيل</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty">
                    <p>لا توجد أنشطة قادمة حالياً. يمكنك استكشاف الفرص التطوعية المتاحة والتقديم عليها.</p>
                </div>
            @endif
        </div>

        <div class="panel">
            <h2>مستويات الالتزام والإنجاز</h2>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
                @php
                    $badges = [
                        ['code' => 'L1', 'label' => 'المتطوع المبتدئ', 'desc' => 'إنجاز ساعة تطوعية واحدة', 'earned' => $totalHours >= 1],
                        ['code' => 'L2', 'label' => 'المتطوع النشط', 'desc' => 'إنجاز 10 ساعات معتمدة', 'earned' => $totalHours >= 10],
                        ['code' => 'L3', 'label' => 'الملتزم بالهدف', 'desc' => 'تحقيق الهدف الشهري', 'earned' => $monthHours >= $goal],
                        ['code' => 'L4', 'label' => 'المتطوع المتميز', 'desc' => 'إنجاز 50 ساعة معتمدة', 'earned' => $totalHours >= 50],
                    ];
                @endphp
                @foreach ($badges as $b)
                    <div style="background:#f8faf9;border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;opacity:{{ $b['earned'] ? '1' : '0.45' }};">
                        <div style="font-weight:800;font-size:16px;color:var(--green);margin-bottom:4px;">{{ $b['code'] }}</div>
                        <div style="font-weight:700;font-size:14px;color:var(--navy);">{{ $b['label'] }}</div>
                        <div style="font-size:12px;color:var(--text-muted);margin:4px 0 8px;">{{ $b['desc'] }}</div>
                        <span class="badge {{ $b['earned'] ? 'badge-accepted' : 'badge-cancelled' }}">
                            {{ $b['earned'] ? 'تم الإنجاز' : 'غير مكتمل' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <h2>فرص مقترحة لاهتماماتك</h2>
            @if ($recommended->isNotEmpty())
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                    @foreach ($recommended as $opp)
                        <div class="opp-card">
                            <div class="opp-body">
                                <div class="opp-cat">{{ $opp->category?->name }}</div>
                                <a href="{{ route('opportunities.show', $opp) }}" class="opp-title">{{ $opp->title }}</a>
                                <div class="opp-meta">
                                    <span>الموقع: {{ $opp->location }}</span>
                                    <span>الالتزام: {{ $opp->required_hours }} ساعة</span>
                                </div>
                                <div class="opp-org">{{ $opp->organization?->name }}</div>
                            </div>
                            <div class="opp-foot">
                                <a href="{{ route('opportunities.show', $opp) }}" class="btn btn-green btn-sm">قدّم الآن</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty">
                    <p>لا توجد توصيات إضافية حالياً.</p>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
