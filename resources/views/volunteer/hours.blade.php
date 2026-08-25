@extends('layouts.app')

@section('title', 'سجل الساعات المعتمدة — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('volunteer.dashboard') }}">الرئيسية</a>
            <a href="{{ route('browse') }}">استكشف الفرص</a>
            <a href="{{ route('volunteer.applications') }}">طلبات التطوع</a>
            <a href="{{ route('volunteer.hours') }}" class="active">سجل الساعات</a>
            <a href="{{ route('volunteer.certificate') }}">شهادة التطوع</a>
            <a href="{{ route('profile.edit') }}">الملف الشخصي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>سجل الساعات التطوعية المعتمدة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">بيان تفصيلي بكافة الساعات التطوعية المسجلة والمعتمدة من المؤسسات</p>
            </div>
            <a href="{{ route('volunteer.certificate') }}" class="btn btn-green btn-sm">إصدار الشهادة الرسمية</a>
        </div>

        @if ($records->isNotEmpty())
            <div class="panel">
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>اسم الفرصة / المبادرة</th>
                                <th>المؤسسة المانحة</th>
                                <th>تاريخ العمل التطوعي</th>
                                <th>عدد الساعات</th>
                                <th>ملاحظات الاعتماد</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $rec)
                                <tr>
                                    <td><strong>{{ $rec->opportunity?->title }}</strong></td>
                                    <td>{{ $rec->opportunity?->organization?->name }}</td>
                                    <td>{{ $rec->work_date?->translatedFormat('d M Y') ?? '—' }}</td>
                                    <td><strong style="color:var(--green-dark);font-size:15px;">{{ $rec->hours }}</strong> ساعة</td>
                                    <td>{{ $rec->notes ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $rec->status }}">
                                            {{ match ($rec->status) { 'approved' => 'معتمد رسمياً', 'pending' => 'قيد الاعتماد', 'rejected' => 'مرفوض' } }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="panel empty">
                <p>لا توجد ساعات تطوعية مسجلة حتى الآن. بعد قبولك في الفرص التطوعية، ستقوم المؤسسات بتسجيل واعتماد ساعاتك هنا.</p>
                <a href="{{ route('browse') }}" class="btn btn-outline btn-sm" style="margin-top:12px;">استكشف الفرص المتاحة</a>
            </div>
        @endif
    </main>
</div>
@endsection
