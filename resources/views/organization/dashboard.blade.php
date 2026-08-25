@extends('layouts.app')

@section('title', 'لوحة تحكم المؤسسة — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('organization.dashboard') }}" class="active">الرئيسية</a>
            <a href="{{ route('organization.opportunities.index') }}">إدارة الفرص</a>
            <a href="{{ route('organization.applications') }}">طلبات التطوع</a>
            <a href="{{ route('organization.hours.select') }}">تسجيل الساعات</a>
            <a href="{{ route('profile.edit') }}">الملف المؤسسي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>لوحة تحكم المؤسسة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">متابعة الفرص التطوعية والطلبات الواردة وساعات المتطوعين</p>
            </div>
            <a href="{{ route('organization.opportunities.create') }}" class="btn btn-green btn-sm">+ نشر فرصة جديدة</a>
        </div>

        <div class="dash-cards">
            <div class="dash-card">
                <div class="dc-icon">الفرص النشطة</div>
                <div class="dc-num">{{ $activeOpportunities }}</div>
                <div class="dc-lbl">فرصة معتمدة ومتاحة</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الطلبات</div>
                <div class="dc-num">{{ $totalApplications }}</div>
                <div class="dc-lbl">طلب تطوع وارد</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">طلبات قيد المراجعة</div>
                <div class="dc-num">{{ $pendingApplications }}</div>
                <div class="dc-lbl">طلب بانتظار الرد والفرز</div>
            </div>
        </div>

        <div class="panel">
            <h2>معدل استقطاب المتطوعين (آخر 6 أشهر)</h2>
            <div class="bar-chart">
                @php $max = max(1, collect($monthlyGrowth)->max('value')); @endphp
                @foreach ($monthlyGrowth as $m)
                    <div class="bar-col">
                        <div class="bar" style="height:{{ max(4, ($m['value'] / $max) * 100) }}%" title="{{ $m['value'] }} متطوع"></div>
                        <div class="bar-lbl">{{ $m['label'] }}</div>
                        <div style="font-size:11.5px;color:var(--navy);font-weight:700;">{{ $m['value'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <h2>أحدث طلبات التطوع الواردة</h2>
            @if ($newApplications->isNotEmpty())
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>اسم المتطوع</th>
                                <th>الفرصة المستهدفة</th>
                                <th>تاريخ التقديم</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($newApplications as $app)
                                <tr>
                                    <td>
                                        <strong>{{ $app->user?->name }}</strong>
                                        <div style="font-size:12px;color:var(--text-muted);">{{ $app->user?->city }}</div>
                                    </td>
                                    <td>{{ $app->opportunity->title }}</td>
                                    <td>{{ $app->created_at->translatedFormat('d M Y') }}</td>
                                    <td><span class="badge badge-pending">قيد المراجعة</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:14px;">
                    <a href="{{ route('organization.applications') }}" class="btn btn-ghost btn-sm">استعراض كافة الطلبات</a>
                </div>
            @else
                <div class="empty">
                    <p>لا توجد طلبات تطوع معلقة حالياً.</p>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
