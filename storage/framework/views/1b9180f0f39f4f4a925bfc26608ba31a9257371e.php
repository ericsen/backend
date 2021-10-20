
<?php
    $arrayKeys = array_keys($agentBDB);
    $lastArrayKey = array_pop($arrayKeys);
?>
<div class="bdb">
    <ul class="bdb-ul">
        <li><a href="/admin/agent_list/0" class="bdb-link">Home</a></li>
        <?php $__currentLoopData = $agentBDB; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <?php if($k == $lastArrayKey): ?>
            <?php echo e($v['name']); ?>

            <?php else: ?>
            <a href="/admin/agent_list/<?php echo e($v['id']); ?>" class="bdb-link"><?php echo e($v['name']); ?></a>
            <?php endif; ?>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>