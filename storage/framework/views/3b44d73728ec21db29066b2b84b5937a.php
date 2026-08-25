<?php $__env->startSection('title', 'الإشعارات والتنبيهات — منصة جسور'); ?>

<?php $__env->startSection('content'); ?>
<section class="section" style="padding-top:35px;">
    <div class="container" style="max-width:820px;">
        <div class="dash-head">
            <div>
                <h1>الإشعارات والتنبيهات</h1>
                <p style="color:var(--text-muted);font-size:14px;margin-top:2px;">سجل التنبيهات الخاصة بطلباتك وساعات التطوع المعتمدة</p>
            </div>
            <?php if($notifications->isNotEmpty() && $unreadCount > 0): ?>
                <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-ghost btn-sm">تحديد الكل كمقروء</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if($notifications->isNotEmpty()): ?>
            <div>
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="notif-card <?php echo e($notif->read_at ? '' : 'unread'); ?>">
                        <div class="notif-icon">
                            <?php echo e(match ($notif->type) { 'application' => 'طلب', 'application_response' => 'قرار', 'hours' => 'ساعات', default => 'تنبيه' }); ?>

                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
                                <h4><?php echo e($notif->title); ?></h4>
                                <?php if(! $notif->read_at): ?>
                                    <form method="POST" action="<?php echo e(route('notifications.read', $notif)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-ghost btn-sm" style="font-size:12px;padding:3px 10px;">تعليم كمقروء</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p style="margin-top:4px;"><?php echo e($notif->body); ?></p>
                            <div class="notif-time"><?php echo e($notif->created_at->diffForHumans()); ?></div>
                            <?php if($notif->action_url): ?>
                                <div style="margin-top:8px;">
                                    <a href="<?php echo e($notif->action_url); ?>" class="btn btn-outline btn-sm">الانتقال للتفاصيل</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="panel empty">
                <p>لا توجد إشعارات أو تنبيهات في سجلك حالياً.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\jusoor\jusoor\resources\views/notifications.blade.php ENDPATH**/ ?>