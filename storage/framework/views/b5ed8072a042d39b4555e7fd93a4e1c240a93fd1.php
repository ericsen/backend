<?php
    $navBreadcrumbs = !empty(Session::get('adminInfo')['navBreadcrumbs'])?Session::get('adminInfo')['navBreadcrumbs']:[];
?>
<div class="place">
    <ul class="placeul">
        <li><?php echo e(trans('navigation.home')); ?></li>
        <?php $__currentLoopData = $navBreadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($row['name']); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>