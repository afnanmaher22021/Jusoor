@extends('layouts.app')

@section('title', 'تعديل الفرصة التطوعية — منصة جسور')

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
                <h1>تعديل الفرصة التطوعية</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">تحديث بيانات الفرصة وشروط التقديم والحالة</p>
            </div>
            <a href="{{ route('organization.opportunities.index') }}" class="btn btn-ghost btn-sm">&rarr; العودة لقائمة الفرص</a>
        </div>

        <div class="panel" style="max-width:760px;">
            <form method="POST" action="{{ route('organization.opportunities.update', $opportunity) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">عنوان الفرصة <span class="req">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $opportunity->title) }}" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="category_id">التصنيف / القطاع <span class="req">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $opportunity->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="description">وصف الفرصة <span class="req">*</span></label>
                    <textarea id="description" name="description" class="form-control" required>{{ old('description', $opportunity->description) }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="location">المدينة / الموقع <span class="req">*</span></label>
                    <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $opportunity->location) }}" required>
                    @error('location')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label for="required_hours">ساعات الالتزام <span class="req">*</span></label>
                        <input type="number" id="required_hours" name="required_hours" class="form-control" value="{{ old('required_hours', $opportunity->required_hours) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="max_volunteers">العدد المطلوب من المتطوعين <span class="req">*</span></label>
                        <input type="number" id="max_volunteers" name="max_volunteers" class="form-control" value="{{ old('max_volunteers', $opportunity->max_volunteers) }}" min="1" required>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label for="starts_at">تاريخ البداية <span class="req">*</span></label>
                        <input type="date" id="starts_at" name="starts_at" class="form-control" value="{{ old('starts_at', $opportunity->starts_at?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="ends_at">تاريخ النهاية <span class="req">*</span></label>
                        <input type="date" id="ends_at" name="ends_at" class="form-control" value="{{ old('ends_at', $opportunity->ends_at?->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">حالة الفرصة</label>
                    <select id="status" name="status" class="form-control">
                        <option value="open" @selected(old('status', $opportunity->status) === 'open')>متاحة للتقديم</option>
                        <option value="closed" @selected(old('status', $opportunity->status) === 'closed')>مغلقة</option>
                        <option value="completed" @selected(old('status', $opportunity->status) === 'completed')>منتهية</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="skills_required">المهارات والمتطلبات</label>
                    <textarea id="skills_required" name="skills_required" class="form-control">{{ old('skills_required', $opportunity->skills_required) }}</textarea>
                </div>

                <button type="submit" class="btn btn-green btn-block" style="padding:12px;">حفظ التعديلات</button>
            </form>
        </div>
    </main>
</div>
@endsection
