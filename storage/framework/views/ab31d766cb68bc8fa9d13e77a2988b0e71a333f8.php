<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<style>
    .layui-form-pane .layui-form-label {
        width: 130px;
    }

    .layui-form-pane .layui-input-block {
        margin-left: 130px;
    }

    .layui-form-item .layui-input-inline {
        width: 371px;
        margin-right: 0px;
    }
</style>
<div class="layui-formbody" style="width:1000px;">
    <?php echo Form::open(['url' => '/admin/customer/doEdit/'.$data['id'],'name'=>'form_edit', 'class'=>'layui-form
    layui-form-pane']); ?>

    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.agent_list.account')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::select('agent_id', $agent_list, $data['agent_id']); ?>

        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer.type')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $type_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::radio('customer_type', $key, ($key==$data['customer_type'])?true:false, ['title'=>$name, 'disabled'])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer.level')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $level_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::radio('customer_level', $key, ($key==$data['customer_level'])?true:false, ['title'=>$name])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.account')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::text('account', $data['account'], ['placeholder'=>trans('admin.required'), 'class'=>'layui-input',
            'autocomplete'=>'off', 'disabled']); ?>

        </div>
        <label class="layui-form-label"><?php echo e(trans('admin.user_name')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::text('user_name', $data['user_name'], ['placeholder'=>trans('admin.required'),
            'class'=>'layui-input', 'autocomplete'=>'off']); ?>

        </div>

    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.pwd')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::password('password', ['placeholder'=>(!empty($data['password']))?'******':'',
            'class'=>'layui-input', 'autocomplete'=>'off', 'readonly'=>true,
            'onfocus'=>"this.removeAttribute('readonly');"]); ?>

        </div>
        <label class="layui-form-label"><?php echo e(trans('admin.nickname')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::text('nickname', $data['nickname'], ['placeholder'=>trans('admin.required'),
            'class'=>'layui-input', 'autocomplete'=>'off']); ?>

        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.mobile_operator')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::select('mobile_operator_id', [0=>'　']+$mobile_operator_list, $data['mobile_operator_id']); ?>

        </div>
        <label class="layui-form-label"><?php echo e(trans('admin.email')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::text('email', $data['email'], ['class'=>'layui-input', 'autocomplete'=>'off']); ?>

        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo e(trans('admin.mobile')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::text('mobile', $data['mobile'], ['class'=>'layui-input', 'autocomplete'=>'off']); ?>

        </div>
        <label class="layui-form-label"><?php echo e(trans('admin.maxclient')); ?></label>
        <div class="layui-input-inline">
            <?php echo Form::text('max_client', $data['max_client'], ['class'=>'layui-input', 'autocomplete'=>'off','min'=>'3', 'step'=>'1']); ?>

        </div>

    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label" style="height:172px;padding-top:75px;"><?php echo e(trans('admin.bank_info')); ?></label>
        <div class="layui-input-inline" style="width:500px;margin:10px 30px;">
            <table style="margin-left:120px;">
                <!-- <tr>
                    <td><?php echo e(trans('admin.bank_name')); ?>　</td>
                    <td><?php echo Form::select('bank_id', [0=>'　']+$bank_list, $data['bank_id']); ?></td>
                </tr> -->
                <tr>
                    <td><?php echo e(trans('admin.bank_name')); ?>　</td>
                    <td><?php echo Form::text('bank_name', $data['bank_name'], ['class'=>'layui-input',
                        'autocomplete'=>'off']); ?></td>
                </tr>
                <tr>
                    <td><?php echo e(trans('admin.bank_account_name')); ?>　</td>
                    <td><?php echo Form::text('bank_account_name', $data['bank_account_name'], ['class'=>'layui-input',
                        'autocomplete'=>'off']); ?></td>
                </tr>
                <tr>
                    <td><?php echo e(trans('admin.bank_account')); ?>　</td>
                    <td><?php echo Form::text('bank_account', $data['bank_account'], ['class'=>'layui-input',
                        'autocomplete'=>'off']); ?></td>
                </tr>
                
            </table>
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.status')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $status_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::radio('status', $key, ($key==$data['status'])?true:false, ['title'=>$name])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label"><?php echo e(trans('admin.customer.game_stop')); ?></label>
        <div class="layui-input-block" style="text-align:left;">
            <?php $__currentLoopData = $game_stop_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::checkbox('game_stop['.$key.']', $key, (in_array($key,setEmptyDef($data['game_stop_arr'], [])))?true:false, ['title'=>$name])); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="layui-form-item" style="margin-top:30px;">
        <?php echo e(Form::button(trans('button.save'),['type'=>'submit', 'class'=>'layui-btn btn-color-black-1'])); ?>

        <?php echo e(Form::button(trans('button.cancel'),['onclick'=>'doCancel()', 'class'=>'layui-btn btn-color-black-1'])); ?>

    </div>
    <?php echo Form::close(); ?>

</div>
<script>
    var form;
    var old_game_stop = JSON.parse('<?php echo json_encode(old('game_stop')); ?>');
    layui.use('form', function(){
        var form = layui.form;

        <?php if(old('customer_level') != ""): ?>
            $("input[name=customer_level][value=<?php echo e(old('customer_level')); ?>]").prop("checked","true");
        <?php endif; ?>
        <?php if(old('status') != ""): ?>
            $("input[name=status][value=<?php echo e(old('status')); ?>]").prop("checked","true");
        <?php endif; ?>

        for(var key in old_game_stop){
            // console.log(key);
            $("input[name='game_stop["+key+"]'").prop("checked","true");
        }

        form.render();
    });

    function doCancel()
    {
        location.href = "<?php echo e(url('admin/customer')); ?>";
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>