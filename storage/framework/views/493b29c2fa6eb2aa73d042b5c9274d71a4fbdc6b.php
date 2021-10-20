<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<div class="layui-formbody" style="width:600px;">
    <?php echo Form::open(['url' => '/admin/game_limit/doEdit','name'=>'form_edit', 'class'=>'layui-form
    layui-form-pane']); ?>

    <div class="layui-inline" style="text-align: center;">
        <?php echo e(trans("game.game_info.self_sports_basic")); ?>

    </div>
    <table class="layui-table" lay-even>
        <!-- <colgroup>
                <col>
            </colgroup> -->
        <thead>
            <tr>
                <th></th>
                <th><?php echo e(trans("game.game_limit.max_odds")); ?></th>
                <th><?php echo e(trans("game.game_limit.min_parlays")); ?></th>
                <th><?php echo e(trans("game.game_limit.max_parlays")); ?></th>
                <th><?php echo e(trans("game.game_limit.max_table_money")); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php echo e(trans("admin.customer.name")); ?>

                    <?php echo e(trans("admin.customer.lv_{$row['customer_level']}")); ?>

                </td>
                <td>
                    <?php echo Form::number("max_odds_{$row['id']}", $row['max_odds'], ['class'=>'layui-input',
                    'autocomplete'=>'off', 'min'=>'100', 'step'=>'100']); ?>

                </td>
                <td>
                    <?php echo Form::number("min_parlays_{$row['id']}", $row['min_parlays'], ['class'=>'layui-input',
                    'autocomplete'=>'off', 'min'=>'100', 'step'=>'100']); ?>

                </td>
                <td>
                    <?php echo Form::number("max_parlays_{$row['id']}", $row['max_parlays'], ['class'=>'layui-input',
                    'autocomplete'=>'off', 'min'=>'100', 'step'=>'100']); ?>

                </td>
                <td>
                    <?php echo Form::number("max_table_money_{$row['id']}", $row['max_table_money'], ['class'=>'layui-input',
                    'autocomplete'=>'off', 'min'=>'100', 'step'=>'100']); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="layui-form-item">
        <?php echo e(Form::button(trans('button.save'),['type'=>'submit', 'class'=>'layui-btn btn-color-black-1'])); ?>

    </div>
    <?php echo Form::close(); ?>

</div>
<script>
    layui.use(['form'], function(){
        var form = layui.form;
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>