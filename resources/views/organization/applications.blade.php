@extends('layouts.app')

@section('title', 'طلبات التطوع الواردة — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('organization.dashboard') }}">الرئيسية</a>
            <a href="{{ route('organization.opportunities.index') }}">إدارة الفرص</a>
            <a href="{{ route('organization.applications') }}" class="active">طلبات التطوع</a>
            <a href="{{ route('organization.hours.select') }}">تسجيل الساعات</a>
            <a href="{{ route('profile.edit') }}">الملف المؤسسي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>إدارة طلبات التطوع الواردة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">مراجعة وفرز طلبات المتطوعين واتخاذ قرارات القبول والرفض</p>
            </div>
        </div>

        @if ($applications->isNotEmpty())
            <div class="panel">
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>بيانات المتطوع</th>
                                <th>الفرصة المستهدفة</th>
                                <th>تاريخ التقديم</th>
                                <th>حالة الطلب</th>
                                <th>قرار المراجعة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $app)
                                <tr>
                                    <td>
                                        <strong>{{ $app->user?->name }}</strong>
                                        <div style="font-size:12px;color:var(--text-muted);">{{ $app->user?->city }} &middot; {{ $app->user?->phone ?? 'بدون هاتف' }}</div>
                                        @if ($app->message)
                                            <div style="font-size:12px;color:var(--navy);margin-top:4px;background:#f0f7f3;padding:6px 10px;border-radius:6px;border:1px solid #dcece3;">
                                                <strong>رسالة المتقدم:</strong> {{ $app->message }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('opportunities.show', $app->opportunity) }}" style="color:var(--navy);font-weight:700;">{{ $app->opportunity->title }}</a>
                                    </td>
                                    <td>{{ $app->created_at->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $app->status }}">
                                            {{ match ($app->status) { 'pending' => 'قيد المراجعة', 'accepted' => 'مقبول', 'rejected' => 'مرفوض', 'cancelled' => 'ملغي' } }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($app->status === 'pending')
                                            <form method="POST" action="{{ route('organization.applications.respond', $app) }}" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                                                @csrf
                                                <input type="text" name="reviewer_note" class="form-control" placeholder="ملاحظة للمتطوع (اختياري)" style="width:160px;padding:6px 10px;font-size:12.5px;">
                                                <button type="submit" name="status" value="accepted" class="btn btn-green btn-sm">قبول</button>
                                                <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm">رفض</button>
                                            </form>
                                        @elseif ($app->reviewer_note)
                                            <span style="font-size:12px;color:var(--text-muted);">ملاحظة: {{ $app->reviewer_note }}</span>
                                        @else
                                            <span style="color:var(--text-muted);font-size:13px;">تمت المعالجة</span>
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
                <p>لا توجد طلبات تطوع واردة في الوقت الحالي.</p>
            </div>
        @endif
    </main>
</div>
@endsection
