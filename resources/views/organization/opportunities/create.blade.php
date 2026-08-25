@extends('layouts.app')

@section('title', 'نشر فرصة تطوعية — منصة جسور')

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
                <h1>نشر فرصة تطوعية جديدة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">إضافة بيانات الفرصة المتاحة للمتطوعين</p>
            </div>
            <a href="{{ route('organization.opportunities.index') }}" class="btn btn-ghost btn-sm">&rarr; العودة لقائمة الفرص</a>
        </div>

        <div class="panel" style="max-width:760px;">
            <form method="POST" action="{{ route('organization.opportunities.store') }}">
                @csrf

                <div class="form-group">
                    <label for="title">عنوان الفرصة التطوعية <span class="req">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="مثال: مبادرة الدعم التعليمي للأطفال" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="category_id">التصنيف / القطاع <span class="req">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">اختر القطاع المناسب...</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="description">وصف الفرصة والمهام المطلوبة <span class="req">*</span></label>
                    <textarea id="description" name="description" class="form-control" placeholder="اشرح طبيعة العمل التطوعي، الأهداف، والمهام المنوطة بالمتطوع..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="location">المدينة / مكان العمل التطوعي <span class="req">*</span></label>
                    <input type="text" id="location" name="location" class="form-control" value="{{ old('location') }}" placeholder="مثال: غزة - مقر الجمعية، أو عن بُعد" required>
                    @error('location')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label for="required_hours">ساعات الالتزام الإجمالية <span class="req">*</span></label>
                        <input type="number" id="required_hours" name="required_hours" class="form-control" value="{{ old('required_hours', 1) }}" min="1" required>
                        @error('required_hours')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="max_volunteers">العدد المطلوب من المتطوعين <span class="req">*</span></label>
                        <input type="number" id="max_volunteers" name="max_volunteers" class="form-control" value="{{ old('max_volunteers', 1) }}" min="1" required>
                        @error('max_volunteers')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label for="starts_at">تاريخ البداية <span class="req">*</span></label>
                        <input type="date" id="starts_at" name="starts_at" class="form-control" value="{{ old('starts_at') }}" required>
                        @error('starts_at')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="ends_at">تاريخ النهاية <span class="req">*</span></label>
                        <input type="date" id="ends_at" name="ends_at" class="form-control" value="{{ old('ends_at') }}" required>
                        @error('ends_at')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="skills_required">المهارات والمتطلبات (اختياري)</label>
                    <textarea id="skills_required" name="skills_required" class="form-control" placeholder="مثال: إجادة اللغة الإنجليزية، مهارات الاتصال والتواصل...">{{ old('skills_required') }}</textarea>
                    @error('skills_required')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-green btn-block" style="padding:12px;">نشر الفرصة رسمياً</button>
            </form>
        </div>
    </main>
</div>
@endsection
