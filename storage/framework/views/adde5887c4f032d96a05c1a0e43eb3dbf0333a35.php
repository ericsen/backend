<?php $__env->startSection('content'); ?>
<style>
    .layui-form-pane .layui-form-label {
        width: 150px;
    }

    .layui-form-pane .layui-input-block {
        margin-left: 150px;
    }

    .layui-form-pane .layui-form-item[pane] .layui-input-inline {
        margin-left: 150px;
    }
</style>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend><?php echo e(trans('admin.customer_issue.name')); ?></legend>
</fieldset>
<div class="layui-formbody" style="max-width:800px;">
    <?php echo Form::open(['url' => '/admin/customer/doIssueRecover','name'=>'form_add', 'class'=>'layui-form
    layui-form-pane']); ?>


    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer_issue.issue_kind')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $issue_kind_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::radio('issue_kind', $key, ($key==1)?true:false, ['title'=>$name, 'lay-filter'=>'issue_kind'])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item" id="i_account" style="display:none;">
        <label class="layui-form-label"><?php echo e(trans('admin.account')); ?></label>
        <div class="layui-input-block">
            <?php echo Form::text('account', $account, ['placeholder'=>trans('admin.required'), 'class'=>'layui-input',
            'autocomplete'=>'off']); ?>

        </div>
    </div>
    <div class="layui-form-item" id="i_customer_level" style="display:none;" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer.level')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $level_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::checkbox("customer_level[$key]", 1, false, ['title'=>$name, 'lay-skin'=>'primary'])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item" id="i_customer_status" style="display:none;" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer.status')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $status_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::checkbox("customer_status[$key]", 1, false, ['title'=>$name, 'lay-skin'=>'primary'])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer_issue.item')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $issue_type_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::radio('issue_type', $key, ($key==20)?true:false, ['title'=>$name])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.customer_issue.amount')); ?></label>
        <div class="layui-input-block">
            <?php echo Form::number('amount', '', ['placeholder'=>trans('admin.required'), 'class'=>'layui-input',
            'autocomplete'=>'off']); ?>

        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.customer_issue.remark')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php echo Form::text('remark', '', ['class'=>'layui-input', 'autocomplete'=>'off']); ?>

        </div>
    </div>
    <div class="layui-form-item" style="margin-top:20px;">
        <?php echo e(Form::button(trans('button.save'),['type'=>'submit', 'class'=>'layui-btn btn-color-black-1', 'id'=>"click_submit"])); ?>

        <?php echo e(Form::button(trans('button.cancel'),['onclick'=>'doCancel()', 'class'=>'layui-btn btn-color-black-1'])); ?>

    </div>
    <?php echo Form::close(); ?>

</div>
<script>
    var old_customer_level = JSON.parse('<?php echo json_encode(old('customer_level')); ?>');
    var old_customer_status = JSON.parse('<?php echo json_encode(old('customer_status')); ?>');

    layui.use(['form','laydate'], function(){
        var form = layui.form;
        var laydate = layui.laydate;

        <?php if(old('issue_kind') != ""): ?>
            $("input[name=issue_kind][value=<?php echo e(old('issue_kind')); ?>]").prop("checked","true");
        <?php endif; ?>
        <?php if(old('issue_type') != ""): ?>
            $("input[name=issue_type][value=<?php echo e(old('issue_type')); ?>]").prop("checked","true");
        <?php endif; ?>

        for(var key in old_customer_level){
            $("input[name='customer_level["+key+"]'").prop("checked","true");
        }
        for(var key in old_customer_status){
            $("input[name='customer_status["+key+"]'").prop("checked","true");
        }

        issue_kind = $('input[name*=issue_kind]:checked').val();
        changeItem(issue_kind);

        form.on('radio(issue_kind)', function(data){
            // console.log(data.value);
            changeItem(data.value);
        });

        form.render();
    });

    function changeItem(issue_kind)
    {
        if(issue_kind == 1){
            $("#i_account").show();
            $("#i_customer_level").hide();
            $("#i_customer_status").hide();
        }else{
            $("#i_account").hide();
            $("#i_customer_level").show();
            $("#i_customer_status").show();
        }
    }

    function doCancel()
    {
        parent.layer.closeAll();
    }

    // enter event
    $("input[name='amount']").keypress(function(e){
        code = e.keyCode ? e.keyCode : e.which;
        if(code == 13){
            e.preventDefault();
            return false;
        }
    });
    $("input[name='amount']").keyup(function(e){
        code = e.keyCode ? e.keyCode : e.which;
        if(code == 13){
            $("#click_submit").click();
            $("input[name='amount']").val('');
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>