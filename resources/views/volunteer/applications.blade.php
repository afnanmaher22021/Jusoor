@extends('layouts.app')

@section('title', 'طلبات التطوع — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('volunteer.dashboard') }}">الرئيسية</a>
            <a href="{{ route('browse') }}">استكشف الفرص</a>
            <a href="{{ route('volunteer.applications') }}" class="active">طلبات التطوع</a>
            <a href="{{ route('volunteer.hours') }}">سجل الساعات</a>
            <a href="{{ route('volunteer.certificate') }}">شهادة التطوع</a>
            <a href="{{ route('profile.edit') }}">الملف الشخصي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>طلبات التطوع المقدمة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">متابعة حالة طلباتك المرسلة للمؤسسات</p>
            </div>
            <a href="{{ route('browse') }}" class="btn btn-green btn-sm">+ التقديم على فرصة</a>
        </div>

        @if ($applications->isNotEmpty())
            <div class="panel">
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>اسم الفرصة</th>
                                <th>المؤسسة المنظمة</th>
                                <th>تاريخ التقديم</th>
                                <th>حالة الطلب</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $app)
                                <tr>
                                    <td>
                                        <a href="{{ route('opportunities.show', $app->opportunity) }}" style="color:var(--navy);font-weight:700;">{{ $app->opportunity->title }}</a>
                                        @if ($app->reviewer_note)
                                            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">ملاحظة المؤسسة: {{ $app->reviewer_note }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $app->opportunity->organization?->name }}</td>
                                    <td>{{ $app->created_at->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $app->status }}">
                                            {{ match ($app->status) { 'pending' => 'قيد المراجعة', 'accepted' => 'مقبول', 'rejected' => 'مرفوض', 'cancelled' => 'ملغي' } }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($app->status === 'pending')
                                            <form method="POST" action="{{ route('volunteer.applications.cancel', $app) }}" data-confirm="هل أنت متأكد من إلغاء هذا الطلب؟">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">إلغاء الطلب</button>
                                            </form>
                                        @else
                                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="panel empty">
                <p>لم تتقدم لأي فرصة تطوعية حتى الآن.</p>
                <a href="{{ route('browse') }}" class="btn btn-green btn-sm" style="margin-top:12px;">استكشف الفرص المتاحة</a>
            </div>
        @endif
    </main>
</div>
@endsection
