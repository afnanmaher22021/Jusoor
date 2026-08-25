@extends('layouts.app')

@section('title', 'تسجيل الدخول — منصة جسور للتطوع')

@section('content')
<section class="auth-page">
    <div style="width:100%;max-width:440px;">
        <div class="form-card">
            <div style="text-align:center;margin-bottom:16px;">
                <span class="brand-logo" style="margin:0 auto 12px;width:44px;height:44px;font-size:22px;">ج</span>
            </div>
            <h1>تسجيل الدخول</h1>
            <p class="form-sub">أدخل بيانات الاعتماد للمتابعة إلى حسابك في المنصة</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">البريد الإلكتروني <span class="req">*</span></label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@domain.com" required autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password">كلمة المرور <span class="req">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="display:flex;align-items:center;justify-content:space-between;font-size:13.5px;">
                    <label style="display:inline-flex;align-items:center;gap:6px;margin:0;cursor:pointer;font-weight:500;color:var(--text-muted);">
                        <input type="checkbox" name="remember" id="remember">
                        <span>تذكر بيانات الدخول</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-green btn-block" style="padding:12px;font-size:15px;margin-top:10px;">دخول إلى الحساب</button>
            </form>

            <div style="border-top:1px solid var(--border-light);margin-top:24px;padding-top:18px;">
                <p class="auth-alt" style="margin:0;">ليس لديك حساب مسجل؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
