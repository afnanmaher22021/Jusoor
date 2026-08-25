@extends('layouts.app')

@section('title', 'لوحة تحكم الإدارة العامة — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('admin.dashboard') }}" class="active">الرئيسية</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>لوحة تحكم الإدارة العامة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">الإشراف العام على المؤسسات الشريكة، المتطوعين، والفرص المعتمدة</p>
            </div>
        </div>

        <div class="dash-cards">
            <div class="dash-card">
                <div class="dc-icon">المتطوعون المسجلون</div>
                <div class="dc-num">{{ $volunteersCount }}</div>
                <div class="dc-lbl">حساب متطوع نشط</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">المؤسسات المسجلة</div>
                <div class="dc-num">{{ $organizationsCount }}</div>
                <div class="dc-lbl">مؤسسة / جمعية</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الفرص المطروحة</div>
                <div class="dc-num">{{ $opportunitiesCount }}</div>
                <div class="dc-lbl">فرصة تطوعية</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الساعات المعتمدة</div>
                <div class="dc-num">{{ number_format($totalHours, 1) }}</div>
                <div class="dc-lbl">ساعة عمل تطوعي</div>
            </div>
        </div>

        <div class="panel">
            <h2>إدارة واعتماد المؤسسات الشريكة</h2>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>اسم المؤسسة</th>
                            <th>المدينة المقر</th>
                            <th>البريد الإلكتروني / المسؤول</th>
                            <th>الفرص المنشورة</th>
                            <th>حالة التوثيق</th>
                            <th>إجراء الإدارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organizations as $org)
                            <tr>
                                <td>
                                    <strong>{{ $org->name }}</strong>
                                    @if ($org->website)
                                        <div style="font-size:12px;"><a href="{{ $org->website }}" target="_blank" style="color:var(--green);">{{ $org->website }}</a></div>
                                    @endif
                                </td>
                                <td>{{ $org->city }}</td>
                                <td>
                                    {{ $org->user?->name }}
                                    <div style="font-size:12px;color:var(--text-muted);">{{ $org->user?->email }}</div>
                                </td>
                                <td><strong>{{ $org->opportunities_count }}</strong> فرصة</td>
                                <td>
                                    @if ($org->verified)
                                        <span class="badge badge-accepted">موثقة ومعتمدة</span>
                                    @else
                                        <span class="badge badge-pending">غير موثقة</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.organizations.verify', $org) }}">
                                        @csrf
                                        @if ($org->verified)
                                            <button type="submit" class="btn btn-danger btn-sm">إلغاء التوثيق</button>
                                        @else
                                            <button type="submit" class="btn btn-green btn-sm">توثيق واعتماد</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--text-muted);">لا توجد مؤسسات مسجلة بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <h2>أحدث الفرص التطوعية المنشورة</h2>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>عنوان الفرصة</th>
                            <th>المؤسسة</th>
                            <th>القطاع</th>
                            <th>الموقع</th>
                            <th>الساعات المطلوبة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOpportunities as $opp)
                            <tr>
                                <td><a href="{{ route('opportunities.show', $opp) }}" style="color:var(--navy);font-weight:700;">{{ $opp->title }}</a></td>
                                <td>{{ $opp->organization?->name }}</td>
                                <td>{{ $opp->category?->name }}</td>
                                <td>{{ $opp->location }}</td>
                                <td>{{ $opp->required_hours }} ساعة</td>
                                <td>
                                    <span class="badge badge-{{ $opp->status }}">
                                        {{ match ($opp->status) { 'open' => 'متاحة', 'closed' => 'مغلقة', 'completed' => 'منتهية' } }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--text-muted);">لا توجد فرص منشورة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
