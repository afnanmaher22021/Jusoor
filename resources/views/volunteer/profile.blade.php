@extends('layouts.app')

@section('title', 'الملف الشخصي — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('volunteer.dashboard') }}">الرئيسية</a>
            <a href="{{ route('browse') }}">استكشف الفرص</a>
            <a href="{{ route('volunteer.applications') }}">طلبات التطوع</a>
            <a href="{{ route('volunteer.hours') }}">سجل الساعات</a>
            <a href="{{ route('volunteer.certificate') }}">شهادة التطوع</a>
            <a href="{{ route('profile.edit') }}" class="active">الملف الشخصي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>إعدادات الملف الشخصي</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">تحديث بياناتك الشخصية ومعلومات الحساب</p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:24px;">
            <div class="panel">
                <h2>البيانات الشخصية</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label for="name">الاسم الكامل <span class="req">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="email">البريد الإلكتروني <span class="req">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label for="city">المدينة <span class="req">*</span></label>
                            <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $user->city) }}" required>
                            @error('city')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label for="birth_date">تاريخ الميلاد</label>
                            <input type="date" id="birth_date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                            @error('birth_date')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="monthly_hours_goal">الهدف الشهري لساعات التطوع (ساعة)</label>
                            <input type="number" min="1" max="300" id="monthly_hours_goal" name="monthly_hours_goal" class="form-control" value="{{ old('monthly_hours_goal', $user->monthly_hours_goal ?? 10) }}">
                            @error('monthly_hours_goal')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="skills">المهارات والاهتمامات</label>
                        <input type="text" id="skills" name="skills" class="form-control" value="{{ old('skills', $user->skills) }}" placeholder="مثال: التصميم، الترجمة، إدارة الفعاليات">
                        @error('skills')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="bio">نبذة تعريفية</label>
                        <textarea id="bio" name="bio" class="form-control" placeholder="اكتب نبذة مختصرة عن خبراتك وأهدافك التطوعية...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-green">حفظ التغييرات</button>
                </form>
            </div>

            <div class="panel" style="height:fit-content;">
                <h2>تغيير كلمة المرور</h2>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">كلمة المرور الحالية <span class="req">*</span></label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                        @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">كلمة المرور الجديدة <span class="req">*</span></label>
                        <input type="password" id="new_password" name="password" class="form-control" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">تأكيد كلمة المرور الجديدة <span class="req">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-outline btn-block">تحديث كلمة المرور</button>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
