<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<style>
    .layui-form-pane .layui-form-label {
        width: 300px;
    }

    .layui-form-pane .layui-input-block {
        margin-left: 300px;
    }

    .layui-form-item .layui-input-inline {
        width: 371px;
        margin-right: 0px;
    }
</style>
<div class="layui-formbody" style="width:500px;">
    <?php echo Form::open(['url' => '/admin/sport_parlays_discount/doEdit/'.$data['id'],'name'=>'form_edit',
    'class'=>'layui-form layui-form-pane']); ?>

    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans("admin.sport_parlays_discount.discount_odds")); ?></label>
        <div class="layui-input-block">
            <?php echo Form::number("discount_odds", $data['discount_odds'], ['class'=>'layui-input', 'autocomplete'=>'off',
            'min'=>'1.01', 'step'=>'0.01']); ?>

        </div>
        <!-- <div class="layui-form-mid layui-word-aux">必填</div> -->
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans("admin.sport_parlays_discount.parlays")); ?></label>
        <div class="layui-input-block">
            <?php echo Form::number("parlays", $data['parlays'], ['class'=>'layui-input', 'autocomplete'=>'off', 'min'=>'2',
            'step'=>'1']); ?>

        </div>
        <!-- <div class="layui-form-mid layui-word-aux">必填</div> -->
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans("admin.sport_parlays_discount.parlays_odds")); ?></label>
        <div class="layui-input-block">
            <?php echo Form::number("parlays_odds", $data['parlays_odds'], ['class'=>'layui-input', 'autocomplete'=>'off',
            'min'=>'0.01', 'step'=>'0.01']); ?>

        </div>
        <!-- <div class="layui-form-mid layui-word-aux">必填</div> -->
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.sport_parlays_discount.status')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php echo Form::select('status', $status_list, $data['status']); ?>

        </div>
        <!-- <div class="layui-form-mid layui-word-aux">&nbsp;111</div> -->
    </div>

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