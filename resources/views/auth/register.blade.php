@extends('layouts.app')

@section('title', 'إنشاء حساب جديد — منصة جسور للتطوع')

@section('content')
<section class="auth-page">
    <div style="width:100%;max-width:620px;">
        <div class="form-card" style="max-width:620px;">
            <div style="text-align:center;margin-bottom:14px;">
                <span class="brand-logo" style="margin:0 auto 10px;width:44px;height:44px;font-size:22px;">ج</span>
            </div>
            <h1>إنشاء حساب جديد</h1>
            <p class="form-sub">اختر نوع الحساب وأدخل البيانات المطلوبة للانضمام لمنصة جسور</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label>نوع الحساب <span class="req">*</span></label>
                    <div class="role-cards">
                        <div class="role-card @if (old('role', 'volunteer') === 'volunteer') selected @endif" data-role="volunteer">
                            <div class="rc-icon">متطوع</div>
                            <h3>حساب متطوع</h3>
                            <p>للأفراد الراغبين في المشاركة بالمبادرات وتوثيق ساعات التطوع</p>
                        </div>
                        <div class="role-card @if (old('role') === 'organization') selected @endif" data-role="organization">
                            <div class="rc-icon">مؤسسة</div>
                            <h3>حساب مؤسسة / جمعية</h3>
                            <p>للجهات والمؤسسات التي تطرح فرصاً تطوعية وتعتمد الساعات</p>
                        </div>
                    </div>
                    <div class="form-error">@error('role'){{ $message }}@enderror</div>
                </div>

                <input type="hidden" name="role" value="{{ old('role', 'volunteer') }}">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label for="name">الاسم الكامل <span class="req">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="الاسم ثلاثي" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني <span class="req">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@domain.com" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label for="password">كلمة المرور <span class="req">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="8 أحرف على الأقل" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">تأكيد كلمة المرور <span class="req">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="أعد كتابة كلمة المرور" required>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label for="city">المدينة <span class="req">*</span></label>
                        <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}" placeholder="مثال: غزة، رام الله، القدس" required>
                        @error('city')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="05xxxxxxxx">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div id="org-fields" style="display:{{ old('role') === 'organization' ? 'block' : 'none' }};border-top:1px solid var(--border);padding-top:18px;margin-top:6px;">
                    <h3 style="color:var(--navy);font-size:16px;margin-bottom:14px;font-weight:700;">بيانات المؤسسة / الجمعية</h3>
                    <div class="form-group">
                        <label for="organization_name">الاسم الرسمي للمؤسسة <span class="req">*</span></label>
                        <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ old('organization_name') }}" placeholder="اسم المؤسسة أو الجمعية">
                        @error('organization_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="organization_description">نبذة عن المؤسسة ونشاطها</label>
                        <textarea id="organization_description" name="organization_description" class="form-control" placeholder="مجال عمل المؤسسة وأهدافها المجتمعية">{{ old('organization_description') }}</textarea>
                        @error('organization_description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label for="website">الموقع الإلكتروني</label>
                            <input type="url" id="website" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://example.org">
                            @error('website')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="founded_year">سنة التأسيس</label>
                            <input type="text" id="founded_year" name="founded_year" class="form-control" value="{{ old('founded_year') }}" maxlength="4" placeholder="مثال: 2018">
                            @error('founded_year')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-green btn-block" style="padding:12px;font-size:15px;margin-top:10px;">إتمام التسجيل</button>
            </form>

            <div style="border-top:1px solid var(--border-light);margin-top:24px;padding-top:18px;">
                <p class="auth-alt" style="margin:0;">لديك حساب مسجل بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
