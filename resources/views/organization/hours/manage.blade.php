@extends('layouts.app')

@section('title', 'تسجيل ساعات — ' . $opportunity->title)

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
                <h1>تسجيل واعتماد ساعات العمل التطوعي</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">الفرصة: <strong>{{ $opportunity->title }}</strong></p>
            </div>
            <a href="{{ route('organization.hours.select') }}" class="btn btn-ghost btn-sm">&rarr; العودة لقائمة الفرص</a>
        </div>

        <div class="panel" style="max-width:860px;">
            <h2>قائمة المتطوعين المقبولين</h2>
            @if ($volunteers->isNotEmpty())
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>اسم المتطوع</th>
                                <th>إجمالي الساعات المعتمدة</th>
                                <th>تسجيل ساعات عمل جديدة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($volunteers as $app)
                                @php
                                    $total = $app->user->participation->where('opportunity_id', $opportunity->id)->where('status', 'approved')->sum('hours');
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $app->user?->name }}</strong>
                                        <div style="font-size:12px;color:var(--text-muted);">{{ $app->user?->city }} &middot; {{ $app->user?->phone ?? 'بدون هاتف' }}</div>
                                    </td>
                                    <td>
                                        @if ($total > 0)
                                            <strong style="color:var(--green);font-size:14.5px;">{{ $total }}</strong> ساعة معتمدة
                                        @else
                                            <span style="color:var(--text-muted);font-size:13px;">لا يوجد ساعات مسجلة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('organization.hours.store', $opportunity) }}" style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $app->user_id }}">
                                            <div>
                                                <input type="number" step="0.5" min="0.5" max="24" name="hours" class="form-control" placeholder="الساعات" style="width:85px;padding:6px 10px;font-size:13px;" required>
                                            </div>
                                            <div>
                                                <input type="date" name="work_date" class="form-control" value="{{ date('Y-m-d') }}" style="width:130px;padding:6px 10px;font-size:13px;" required>
                                            </div>
                                            <div>
                                                <input type="text" name="notes" class="form-control" placeholder="ملاحظة أو تفاصيل المهمة" style="width:150px;padding:6px 10px;font-size:13px;">
                                            </div>
                                            <button type="submit" class="btn btn-green btn-sm">تسجيل واحتساب</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty">
                    <p>لا يوجد متطوعون مقبولون في هذه الفرصة حتى الآن.</p>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
