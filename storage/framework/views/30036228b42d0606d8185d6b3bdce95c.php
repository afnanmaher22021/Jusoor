<?php $__env->startSection('title', 'لوحة تحكم الإدارة العامة — منصة جسور'); ?>

<?php $__env->startSection('content'); ?>
<div class="dash-layout">
    <aside class="dash-side">
        <div class="brand"><span class="brand-logo">ج</span><span>جسور</span></div>
        <nav class="dash-nav">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="active">الرئيسية</a>
            <a href="<?php echo e(route('notifications.index')); ?>">الإشعارات</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-head">
            <div>
                <h1>لوحة تحكم الإدارة العامة</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">الإشراف العام على المؤسسات الشريكة، المتطوعين، والفرص المعتمدة</p>
            </div>
        </div>

        <div class="dash-cards">
            <div class="dash-card">
                <div class="dc-icon">المتطوعون المسجلون</div>
                <div class="dc-num"><?php echo e($volunteersCount); ?></div>
                <div class="dc-lbl">حساب متطوع نشط</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">المؤسسات المسجلة</div>
                <div class="dc-num"><?php echo e($organizationsCount); ?></div>
                <div class="dc-lbl">مؤسسة / جمعية</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الفرص المطروحة</div>
                <div class="dc-num"><?php echo e($opportunitiesCount); ?></div>
                <div class="dc-lbl">فرصة تطوعية</div>
            </div>
            <div class="dash-card">
                <div class="dc-icon">إجمالي الساعات المعتمدة</div>
                <div class="dc-num"><?php echo e(number_format($totalHours, 1)); ?></div>
                <div class="dc-lbl">ساعة عمل تطوعي</div>
            </div>
        </div>

        <div class="panel">
            <h2>إدارة واعتماد المؤسسات الشريكة</h2>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>اسم المؤسسة</th>
                            <th>المدينة المقر</th>
                            <th>البريد الإلكتروني / المسؤول</th>
                            <th>الفرص المنشورة</th>
                            <th>حالة التوثيق</th>
                            <th>إجراء الإدارة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($org->name); ?></strong>
                                    <?php if($org->website): ?>
                                        <div style="font-size:12px;"><a href="<?php echo e($org->website); ?>" target="_blank" style="color:var(--green);"><?php echo e($org->website); ?></a></div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($org->city); ?></td>
                                <td>
                                    <?php echo e($org->user?->name); ?>

                                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($org->user?->email); ?></div>
                                </td>
                                <td><strong><?php echo e($org->opportunities_count); ?></strong> فرصة</td>
                                <td>
                                    <?php if($org->verified): ?>
                                        <span class="badge badge-accepted">موثقة ومعتمدة</span>
                                    <?php else: ?>
                                        <span class="badge badge-pending">غير موثقة</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.organizations.verify', $org)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php if($org->verified): ?>
                                            <button type="submit" class="btn btn-danger btn-sm">إلغاء التوثيق</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-green btn-sm">توثيق واعتماد</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--text-muted);">لا توجد مؤسسات مسجلة بعد.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <h2>أحدث الفرص التطوعية المنشورة</h2>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>عنوان الفرصة</th>
                            <th>المؤسسة</th>
                            <th>القطاع</th>
                            <th>الموقع</th>
                            <th>الساعات المطلوبة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentOpportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><a href="<?php echo e(route('opportunities.show', $opp)); ?>" style="color:var(--navy);font-weight:700;"><?php echo e($opp->title); ?></a></td>
                                <td><?php echo e($opp->organization?->name); ?></td>
                                <td><?php echo e($opp->category?->name); ?></td>
                                <td><?php echo e($opp->location); ?></td>
                                <td><?php echo e($opp->required_hours); ?> ساعة</td>
                                <td>
                                    <span class="badge badge-<?php echo e($opp->status); ?>">
                                        <?php echo e(match ($opp->status) { 'open' => 'متاحة', 'closed' => 'مغلقة', 'completed' => 'منتهية' }); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--text-muted);">لا توجد فرص منشورة.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\jusoor\jusoor\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>