<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'جسور') | منصة جسور للتطوع</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%231B5E20'/%3E%3Ctext x='50' y='72' font-size='60' text-anchor='middle' fill='white' font-family='Arial'%3Eج%3C/text%3E%3C/svg%3E">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<header class="site-header">
    <div class="container nav-wrap">
        <a href="{{ route('landing') }}" class="brand">
            <span class="brand-logo">ج</span>
            <span>منصة جسور</span>
        </a>

        <nav class="nav-links" id="navLinks">
            <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">الرئيسية</a>
            <a href="{{ route('browse') }}" class="{{ request()->routeIs('browse*') ? 'active' : '' }}">استكشف الفرص</a>
            @auth
                <a href="{{ route('home') }}" class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">لوحة التحكم</a>
            @endauth
        </nav>

        <div class="nav-actions">
            @guest
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="btn btn-green btn-sm">انضم للمنصة</a>
            @else
                <a href="{{ route('notifications.index') }}" class="badge-dot" title="الإشعارات" data-count="{{ auth()->user()->notifications()->unread()->count() ?: '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </a>
                <div class="nav-user">
                    <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-ghost btn-sm" title="الملف الشخصي">{{ auth()->user()->name }}</a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="border-color:rgba(255,255,255,0.3);color:#fff;">خروج</button>
                </form>
            @endguest
            <button class="menu-toggle" id="menuToggle" aria-label="القائمة">☰</button>
        </div>
    </div>
</header>

<main>
    @if (session('success'))
        <div class="container" style="margin-top:20px;">
            <div class="flash flash-success">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="container" style="margin-top:20px;">
            <div class="flash flash-error">
                <ul style="padding-right:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="brand" style="color:#fff;margin-bottom:14px;">
                    <span class="brand-logo">ج</span>
                    <span>منصة جسور للتطوع</span>
                </div>
                <p style="font-size:13.5px;max-width:340px;line-height:1.7;">منصة وطنية متكاملة لربط الكفاءات والمتطوعين بالمؤسسات المعتمدة، لتوثيق الساعات التطوعية وبناء الخبرات المهنية.</p>
            </div>
            <div>
                <h4>روابط رئيسية</h4>
                <a href="{{ route('landing') }}">الرئيسية</a>
                <a href="{{ route('browse') }}">استكشف الفرص</a>
                <a href="{{ route('register') }}">تسجيل متطوع</a>
                <a href="{{ route('register') }}">تسجيل مؤسسة</a>
            </div>
            <div>
                <h4>بوابة المنصة</h4>
                <a href="{{ route('browse') }}">دليل الفرص</a>
                <a href="{{ route('login') }}">تسجيل الدخول</a>
                <a href="{{ route('register') }}">إنشاء حساب جديد</a>
            </div>
            <div>
                <h4>الدعم والتواصل</h4>
                <a href="mailto:info@jusoor.org">info@jusoor.org</a>
                <a href="tel:+97022980000">+970 (02) 298-0000</a>
            </div>
        </div>
        <div class="footer-bottom">© {{ date('Y') }} منصة جسور للتطوع — جميع الحقوق محفوظة</div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
