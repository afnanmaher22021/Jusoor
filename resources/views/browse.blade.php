@extends('layouts.app')

@section('title', 'استكشف الفرص التطوعية — منصة جسور')

@section('content')

<section class="section" style="padding-top:35px;">
    <div class="container">
        <div class="section-head" style="text-align:right;margin-bottom:30px;">
            <h2>استكشف الفرص التطوعية المتاحة</h2>
            <p>تصفح الفرص التطوعية المعتمدة واستخدم خيارات التصفية للوصول إلى المبادرة الأنسب لاهتماماتك.</p>
        </div>

        <div class="browse-layout">
            <aside class="filter-panel">
                <h3>تصفية البحث</h3>
                <form method="GET" action="{{ route('browse') }}">
                    <div class="form-group">
                        <label for="search">كلمة مفتاحية</label>
                        <input type="text" id="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="ابحث باسم الفرصة...">
                    </div>
                    <div class="form-group">
                        <label for="category">التصنيف والقطاع</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">جميع القطاعات</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(($filters['category'] ?? '') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="location">المدينة والموقع</label>
                        <input type="text" id="location" name="location" class="form-control" value="{{ $filters['location'] ?? '' }}" placeholder="المدينة...">
                    </div>
                    <div class="form-group">
                        <label for="max_hours">الحد الأقصى لساعات الالتزام</label>
                        <select id="max_hours" name="max_hours" class="form-control">
                            <option value="">بدون تحديد</option>
                            @foreach ([5, 10, 20, 50] as $h)
                                <option value="{{ $h }}" @selected(($filters['max_hours'] ?? '') == $h)>{{ $h }} ساعة أو أقل</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-green btn-block">تطبيق التصفية</button>
                    <a href="{{ route('browse') }}" class="btn btn-ghost btn-block" style="margin-top:8px;">إعادة تعيين</a>
                </form>
            </aside>

            <div>
                @if ($opportunities->isNotEmpty())
                    <div class="grid-3">
                        @foreach ($opportunities as $opp)
                            @php
                                $isFull = $opp->isFull();
                                $hasApplied = auth()->check() && auth()->user()->isVolunteer()
                                    ? $opp->applications->where('user_id', auth()->id())->isNotEmpty()
                                    : false;
                            @endphp
                            <div class="opp-card">
                                <div class="opp-cover">
                                    <span>{{ $opp->category?->name ?? 'تطوع مجتمعي' }}</span>
                                </div>
                                <div class="opp-body">
                                    <div class="opp-cat">{{ $opp->category?->name ?? 'تطوع' }}</div>
                                    <a href="{{ route('opportunities.show', $opp) }}" class="opp-title">{{ $opp->title }}</a>
                                    <p class="opp-desc">{{ Str::limit($opp->description, 95) }}</p>
                                    <div class="opp-meta">
                                        <span>الموقع: {{ $opp->location }}</span>
                                        <span>الالتزام: {{ $opp->required_hours }} ساعة</span>
                                    </div>
                                    <div class="opp-org">{{ $opp->organization?->name }}</div>
                                    <div>
                                        <div class="capacity-bar">
                                            <div class="capacity-fill" style="width:{{ min(100, $opp->acceptedCount() / max(1, $opp->max_volunteers) * 100) }}%"></div>
                                        </div>
                                        <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                                            المقاعد المشغولة: <strong>{{ $opp->acceptedCount() }}</strong> من {{ $opp->max_volunteers }}
                                        </div>
                                    </div>
                                </div>
                                <div class="opp-foot">
                                    @auth
                                        @if (auth()->user()->isVolunteer())
                                            @if ($hasApplied)
                                                <a href="{{ route('opportunities.show', $opp) }}" class="btn btn-ghost btn-sm btn-apply">تم التقديم</a>
                                            @elseif ($isFull)
                                                <span class="badge badge-closed">المقاعد مكتملة</span>
                                            @else
                                                <a href="{{ route('opportunities.show', $opp) }}" class="btn btn-green btn-sm btn-apply">قدّم الآن</a>
                                            @endif
                                        @else
                                            <a href="{{ route('opportunities.show', $opp) }}" class="btn btn-ghost btn-sm btn-apply">عرض التفاصيل</a>
                                        @endif
                                    @else
                                        <a href="{{ route('opportunities.show', $opp) }}" class="btn btn-green btn-sm btn-apply">قدّم الآن</a>
                                    @endauth
                                    <a href="{{ route('opportunities.show', $opp) }}" style="font-size:12.5px;color:var(--text-muted);font-weight:600;">التفاصيل &larr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $opportunities->links() }}
                @else
                    <div class="panel empty">
                        <div class="empty-icon">—</div>
                        <p>لا توجد فرص تطوعية مطابقة لمعايير البحث الحالية.</p>
                        <a href="{{ route('browse') }}" class="btn btn-outline btn-sm" style="margin-top:12px;">عرض كافة الفرص</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
