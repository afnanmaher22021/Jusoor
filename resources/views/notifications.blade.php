@extends('layouts.app')

@section('title', 'الإشعارات والتنبيهات — منصة جسور')

@section('content')
<section class="section" style="padding-top:35px;">
    <div class="container" style="max-width:820px;">
        <div class="dash-head">
            <div>
                <h1>الإشعارات والتنبيهات</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">سجل التنبيهات الخاصة بطلباتك وساعات التطوع المعتمدة</p>
            </div>
            @if ($notifications->isNotEmpty() && $unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">تحديد الكل كمقروء</button>
                </form>
            @endif
        </div>

        @if ($notifications->isNotEmpty())
            <div>
                @foreach ($notifications as $notif)
                    <div class="notif-card {{ $notif->read_at ? '' : 'unread' }}">
                        <div class="notif-icon">
                            {{ match ($notif->type) { 'application' => 'طلب', 'application_response' => 'قرار', 'hours' => 'ساعات', default => 'تنبيه' } }}
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
                                <h4>{{ $notif->title }}</h4>
                                @if (! $notif->read_at)
                                    <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-sm" style="font-size:12px;padding:3px 10px;">تعليم كمقروء</button>
                                    </form>
                                @endif
                            </div>
                            <p style="margin-top:4px;">{{ $notif->body }}</p>
                            <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                            @if ($notif->action_url)
                                <div style="margin-top:8px;">
                                    <a href="{{ $notif->action_url }}" class="btn btn-outline btn-sm">الانتقال للتفاصيل</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="panel empty">
                <p>لا توجد إشعارات أو تنبيهات في سجلك حالياً.</p>
            </div>
        @endif
    </div>
</section>
@endsection
