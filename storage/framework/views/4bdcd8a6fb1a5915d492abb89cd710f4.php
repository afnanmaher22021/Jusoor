<?php $__env->startSection('title', 'لوحة تحكم المتطوع — منصة جسور'); ?>

<?php $__env->startSection('content'); ?>
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="<?php echo e(route('volunteer.dashboard')); ?>" class="active">الرئيسية</a>
            <a href="<?php echo e(route('browse')); ?>">استكشف الفرص</a>
            <a href="<?php echo e(route('volunteer.applications')); ?>">طلبات التطوع</a>
            <a href="<?php echo e(route('volunteer.hours')); ?>">سجل الساعات</a>
            <a href="<?php echo e(route('volunteer.certificate')); ?>">شهادة التطوع</a>
            <a href="<?php echo e(route('profile.edit')); ?>">الملف الشخصي</a>
            <a href="<?php echo e(route('notifications.index')); ?>">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>لوحة تحكم المتطوع</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">أهلاً بك، <?php echo e($user->name); ?></p>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="<?php echo e(route('volunteer.certificate')); ?>" class="btn btn-outline btn-sm">إصدار الشهادة الرسمية</a>
                <a href="<?php echo e(route('browse')); ?>" class="btn btn-green btn-sm">+ استكشاف الفرص</a>
            </div>
        </div>

        <div class="dash-cards">
            <div class="dash-card">
                <div class="dc-icon">ساعات الشهر الحالي</div>
                <div class="dc-num"><?php echo e(number_format($monthHours, 1)); ?></div>
                <div class="dc-lbl">ساعة معتمدة هذا الشهر</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الساعات المعتمدة</div>
                <div class="dc-num"><?php echo e(number_format($totalHours, 1)); ?></div>
                <div class="dc-lbl">ساعة تطوعية موثقة</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">الهدف الشهري المستهدف</div>
                <div class="dc-num"><?php echo e($user->monthly_hours_goal); ?></div>
                <div class="dc-lbl">ساعة مستهدفة شهرياً</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">الأنشطة القادمة</div>
                <div class="dc-num"><?php echo e($upcoming->count()); ?></div>
                <div class="dc-lbl">مبادرة مقبولة ونشطة</div>
            </div>
        </div>

        <div class="panel">
            <h2>معدل الإنجاز نحو الهدف الشهري</h2>
            <div class="progress-wrap">
                <div class="progress-top">
                    <span>تم إنجاز <strong><?php echo e($monthHours); ?></strong> ساعة من أصل <strong><?php echo e($goal); ?></strong> ساعة</span>
                    <strong><?php echo e(round(min(100, ($monthHours / $goal) * 100))); ?>%</strong>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:<?php echo e(min(100, ($monthHours / $goal) * 100)); ?>%"></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <h2>الأنشطة والمبادرات المقبولة</h2>
            <?php if($upcoming->isNotEmpty()): ?>
                <div class="table-wrap">
                    <table class="data">
                        <thead>
                            <tr>
                                <th>اسم الفرصة</th>
                                <th>المؤسسة المنظمة</th>
                                <th>الموقع</th>
                                <th>تاريخ البدء</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('opportunities.show', $app->opportunity)); ?>" style="color:var(--navy);font-weight:700;"><?php echo e($app->opportunity->title); ?></a>
                                    </td>
                                    <td><?php echo e($app->opportunity->organization?->name); ?></td>
                                    <td><?php echo e($app->opportunity->location); ?></td>
                                    <td><?php echo e($app->opportunity->starts_at?->translatedFormat('d M Y') ?? '—'); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('opportunities.show', $app->opportunity)); ?>" class="btn btn-ghost btn-sm">عرض التفاصيل</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty">
                    <p>لا توجد أنشطة قادمة حالياً. يمكنك استكشاف الفرص التطوعية المتاحة والتقديم عليها.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="panel">
            <h2>مستويات الالتزام والإنجاز</h2>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
                <?php
                    $badges = [
                        ['code' => 'L1', 'label' => 'المتطوع المبتدئ', 'desc' => 'إنجاز ساعة تطوعية واحدة', 'earned' => $totalHours >= 1],
                        ['code' => 'L2', 'label' => 'المتطوع النشط', 'desc' => 'إنجاز 10 ساعات معتمدة', 'earned' => $totalHours >= 10],
                        ['code' => 'L3', 'label' => 'الملتزم بالهدف', 'desc' => 'تحقيق الهدف الشهري', 'earned' => $monthHours >= $goal],
                        ['code' => 'L4', 'label' => 'المتطوع المتميز', 'desc' => 'إنجاز 50 ساعة معتمدة', 'earned' => $totalHours >= 50],
                    ];
                ?>
                <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background:#f8faf9;border:1px solid var(--border);border-radius:var(--radius-sm);padding:16px;text-align:center;opacity:<?php echo e($b['earned'] ? '1' : '0.45'); ?>;">
                        <div style="font-weight:800;font-size:16px;color:var(--green);margin-bottom:4px;"><?php echo e($b['code']); ?></div>
                        <div style="font-weight:700;font-size:14px;color:var(--navy);"><?php echo e($b['label']); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);margin:4px 0 8px;"><?php echo e($b['desc']); ?></div>
                        <span class="badge <?php echo e($b['earned'] ? 'badge-accepted' : 'badge-cancelled'); ?>">
                            <?php echo e($b['earned'] ? 'تم الإنجاز' : 'غير مكتمل'); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="panel">
            <h2>فرص مقترحة لاهتماماتك</h2>
            <?php if($recommended->isNotEmpty()): ?>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                    <?php $__currentLoopData = $recommended; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="opp-card">
                            <div class="opp-body">
                                <div class="opp-cat"><?php echo e($opp->category?->name); ?></div>
                                <a href="<?php echo e(route('opportunities.show', $opp)); ?>" class="opp-title"><?php echo e($opp->title); ?></a>
                                <div class="opp-meta">
                                    <span>الموقع: <?php echo e($opp->location); ?></span>
                                    <span>الالتزام: <?php echo e($opp->required_hours); ?> ساعة</span>
                                </div>
                                <div class="opp-org"><?php echo e($opp->organization?->name); ?></div>
                            </div>
                            <div class="opp-foot">
                                <a href="<?php echo e(route('opportunities.show', $opp)); ?>" class="btn btn-green btn-sm">قدّم الآن</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="empty">
                    <p>لا توجد توصيات إضافية حالياً.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\jusoor\jusoor\resources\views/volunteer/dashboard.blade.php ENDPATH**/ ?>