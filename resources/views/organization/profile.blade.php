@extends('layouts.app')

@section('title', 'الملف المؤسسي — منصة جسور')

@section('content')
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="{{ route('organization.dashboard') }}">الرئيسية</a>
            <a href="{{ route('organization.opportunities.index') }}">إدارة الفرص</a>
            <a href="{{ route('organization.applications') }}">طلبات التطوع</a>
            <a href="{{ route('organization.hours.select') }}">تسجيل الساعات</a>
            <a href="{{ route('profile.edit') }}" class="active">الملف المؤسسي</a>
            <a href="{{ route('notifications.index') }}">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>إعدادات الملف المؤسسي</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">تحديث بيانات المؤسسة ومعلومات الاتصال المعتمدة</p>
            </div>
            @if ($org->verified)
                <span class="badge badge-verified" style="padding:6px 14px;font-size:13px;">مؤسسة موثقة ومعتمدة</span>
            @else
                <span class="badge badge-pending" style="padding:6px 14px;font-size:13px;">قيد تدقيق التوثيق</span>
            @endif
        </div>

        <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:24px;">
            <div class="panel">
                <h2>بيانات المؤسسة</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="organization_name">اسم المؤسسة / الجمعية <span class="req">*</span></label>
                        <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ old('organization_name', $org->name) }}" required>
                        @error('organization_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="organization_description">نبذة تعريفية عن المؤسسة ونشاطها</label>
                        <textarea id="organization_description" name="organization_description" class="form-control" placeholder="اكتب نبذة عن المؤسسة وأهدافها...">{{ old('organization_description', $org->description) }}</textarea>
                        @error('organization_description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label for="website">الموقع الإلكتروني</label>
                            <input type="url" id="website" name="website" class="form-control" value="{{ old('website', $org->website) }}" placeholder="https://example.org">
                            @error('website')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="founded_year">سنة التأسيس</label>
                            <input type="text" id="founded_year" name="founded_year" class="form-control" value="{{ old('founded_year', $org->founded_year) }}" maxlength="4" placeholder="2015">
                            @error('founded_year')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h3 style="color:var(--navy);font-size:16px;margin:20px 0 12px;font-weight:700;">بيانات ممثل المؤسسة والتواصل</h3>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label for="name">اسم المسؤول المعتمد <span class="req">*</span></label>
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
                            <label for="city">المدينة المقر <span class="req">*</span></label>
                            <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $user->city) }}" required>
                            @error('city')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">رقم الهاتف للتواصل</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="05xxxxxxxx">
                            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-green">حفظ البيانات المؤسسية</button>
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
