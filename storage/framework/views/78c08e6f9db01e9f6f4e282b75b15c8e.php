<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'جسور'); ?> | منصة جسور للتطوع</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%231B5E20'/%3E%3Ctext x='50' y='72' font-size='60' text-anchor='middle' fill='white' font-family='Arial'%3Eج%3C/text%3E%3C/svg%3E">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<header class="site-header">
    <div class="container nav-wrap">
        <a href="<?php echo e(route('landing')); ?>" class="brand">
            <span class="brand-logo">ج</span>
            <span>منصة جسور</span>
        </a>

        <nav class="nav-links" id="navLinks">
            <a href="<?php echo e(route('landing')); ?>" class="<?php echo e(request()->routeIs('landing') ? 'active' : ''); ?>">الرئيسية</a>
            <a href="<?php echo e(route('browse')); ?>" class="<?php echo e(request()->routeIs('browse*') ? 'active' : ''); ?>">استكشف الفرص</a>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('*.dashboard') ? 'active' : ''); ?>">لوحة التحكم</a>
            <?php endif; ?>
        </nav>

        <div class="nav-actions">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-ghost btn-sm">تسجيل الدخول</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-green btn-sm">انضم للمنصة</a>
            <?php else: ?>
                <a href="<?php echo e(route('notifications.index')); ?>" class="badge-dot" title="الإشعارات" data-count="<?php echo e(auth()->user()->notifications()->unread()->count() ?: ''); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </a>
                <div class="nav-user">
                    <span class="avatar"><?php echo e(mb_substr(auth()->user()->name, 0, 1)); ?></span>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-ghost btn-sm" title="الملف الشخصي"><?php echo e(auth()->user()->name); ?></a>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline btn-sm" style="border-color:rgba(255,255,255,0.3);color:#fff;">خروج</button>
                </form>
            <?php endif; ?>
            <button class="menu-toggle" id="menuToggle" aria-label="القائمة">☰</button>
        </div>
    </div>
</header>

<main>
    <?php if(session('success')): ?>
        <div class="container" style="margin-top:20px;">
            <div class="flash flash-success"><?php echo e(session('success')); ?></div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="container" style="margin-top:20px;">
            <div class="flash flash-error">
                <ul style="padding-right:18px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
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
                <a href="<?php echo e(route('landing')); ?>">الرئيسية</a>
                <a href="<?php echo e(route('browse')); ?>">استكشف الفرص</a>
                <a href="<?php echo e(route('register')); ?>">تسجيل متطوع</a>
                <a href="<?php echo e(route('register')); ?>">تسجيل مؤسسة</a>
            </div>
            <div>
                <h4>بوابة المنصة</h4>
                <a href="<?php echo e(route('browse')); ?>">دليل الفرص</a>
                <a href="<?php echo e(route('login')); ?>">تسجيل الدخول</a>
                <a href="<?php echo e(route('register')); ?>">إنشاء حساب جديد</a>
            </div>
            <div>
                <h4>الدعم والتواصل</h4>
                <a href="mailto:info@jusoor.org">info@jusoor.org</a>
                <a href="tel:+97022980000">+970 (02) 298-0000</a>
            </div>
        </div>
        <div class="footer-bottom">© <?php echo e(date('Y')); ?> منصة جسور للتطوع — جميع الحقوق محفوظة</div>
    </div>
</footer>

<script src="<?php echo e(asset('js/app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\jusoor\jusoor\resources\views/layouts/app.blade.php ENDPATH**/ ?>