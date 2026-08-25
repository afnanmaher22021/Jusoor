@extends('layouts.app')

@section('title', 'الفرص التطوعية — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('organization.dashboard') }}">الرئيسية</a>
            <a href="{{ route('organization.opportunities.index') }}" class="active">إدارة الفرص</a>
            <a href="{{ route('organization.applications') }}">طلبات التطوع</a>
            <a href="{{ route('organization.hours.select') }}">تسجيل الساعات</a>
            <a href="{{ route('profile.edit') }}">الملف المؤسسي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>إدارة الفرص التطوعية</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">قائمة الفرص التطوعية المطروحة من قبل المؤسسة</p>
            </div>
            <a href="{{ route('organization.opportunities.create') }}" class="btn btn-green btn-sm">+ نشر فرصة جديدة</a>
        </div>

        @if ($opportunities->isNotEmpty())
            <div class="panel">
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>عنوان الفرصة</th>
                                <th>التصنيف</th>
                                <th>الموقع</th>
                                <th>الساعات</th>
                                <th>الطلبات والمقبولين</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($opportunities as $opp)
                                <tr>
                                    <td>
                                        <a href="{{ route('opportunities.show', $opp) }}" style="color:var(--navy);font-weight:700;">{{ $opp->title }}</a>
                                    </td>
                                    <td>{{ $opp->category?->name }}</td>
                                    <td>{{ $opp->location }}</td>
                                    <td>{{ $opp->required_hours }} ساعة</td>
                                    <td>
                                        <strong>{{ $opp->applications->count() }}</strong> طلب
                                        ({{ $opp->acceptedCount() }} مقبول)
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $opp->status }}">
                                            {{ match ($opp->status) { 'open' => 'متاحة', 'closed' => 'مغلقة', 'completed' => 'منتهية' } }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;">
                                            <a href="{{ route('organization.opportunities.edit', $opp) }}" class="btn btn-ghost btn-sm">تعديل</a>
                                            <a href="{{ route('organization.hours.manage', $opp) }}" class="btn btn-outline btn-sm">الساعات</a>
                                            <form method="POST" action="{{ route('organization.opportunities.destroy', $opp) }}" data-confirm="هل أنت متأكد من حذف هذه الفرصة؟">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="panel empty">
                <p>لم يتم نشر أي فرصة تطوعية حتى الآن.</p>
                <a href="{{ route('organization.opportunities.create') }}" class="btn btn-green btn-sm" style="margin-top:12px;">نشر أول فرصة تطوعية</a>
            </div>
        @endif
    </main>
</div>
@endsection
