<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<div class="layui-fluid">

    <div class="searchTable" style="margin-bottom:20px;">
        <div class="layui-row">
            <div class="layui-col-xs10">
                <?php echo Form::open(['url' => '/admin/agent_list/'.$pid, 'name'=>'form_search', 'id'=>'form_search',
                'method'=>'get', 'class'=>'layui-form']); ?>

                <?php echo Form::hidden('page', setEmptyDef($search['page'],1), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>

                <?php echo Form::hidden('per_page', setEmptyDef($search['per_page'],20), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>

                <?php echo e(trans('admin.account')); ?>：
                <div class="layui-inline">
                    <?php echo Form::text('account', setEmptyDef($search['account']), ['class'=>'layui-input',
                    'autocomplete'=>'off']); ?>

                </div>
                <?php echo e(Form::button(trans('button.search'),['id'=>'btn_search', 'name'=>'btn_search', 'type'=>'submit',
                'class'=>'layui-btn btn-color-black-1', 'lay-submit', 'lay-filter' => 'go'])); ?>

                <?php echo Form::close(); ?>

            </div>
            <div class="layui-col-xs2">
                <div style="float:right;">
                    <?php if($isAL  > -1): ?>
                    <?php echo e(Form::button(trans('button.add'),['id'=>'btn_add', 'name'=>'btn_add', 'onclick'=>'add()',
                    'class'=>'layui-btn btn-color-black-1'])); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('admin.agent_list.agent_breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <table class="layui-table" style="margin-top:0px;" lay-even>
        <!-- <colgroup>
            <col>
        </colgroup> -->
        <thead>
            <tr>
                <th><?php echo e(trans('admin.agent_list.p_agent_name')); ?></th>
                <th><?php echo e(trans('admin.agent_list.agent_name')); ?></th>
                <th><?php echo e(trans('admin.account')); ?></th>
                <th><?php echo e(trans('admin.agent_list.user_name')); ?></th>
                <th><?php echo e(trans('admin.tel')); ?></th>
                <th><?php echo e(trans('admin.agent_list.fee_type')); ?></th>
                <th><?php echo e(trans('admin.agent_list.invitation_code')); ?></th>
                <th><?php echo e(trans('admin.agent_list.status')); ?></th>
                <?php if($isAL > 0): ?>
                <th><?php echo e(trans('admin.agent_list.s_agent_name')); ?></th>
                <?php endif; ?>
                <?php if($isAL == 0): ?>
                <th><?php echo e(trans('admin.agent_list.s_agent_name')); ?></th>
                <th><?php echo e(trans('admin.agent_list.sub_account')); ?></th>
                <th><?php echo e(trans('admin.agent_list.promote_domin')); ?></th>
                <?php endif; ?>
                <th><?php echo e(trans('admin.created_at')); ?></th>
                <?php if($isAL > -1): ?>
                <th><?php echo e(trans('admin.operate')); ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['p_agent_name']); ?></td>
                <td><?php echo e($row['agent_name']); ?></td>
                <?php if($isAL > 0 and isset($show)): ?>
                <td>
                    <div class="layui-table-cell laytable-cell-1-0-0">
                        <a href="javascript:go_agent(this, '<?php echo e($row['id']); ?>');"
                            class="layui-table-link"><?php echo e($row['account']); ?></a>
                    </div>
                </td>
                <?php else: ?>
                <td>
                    <div class="layui-table-cell laytable-cell-1-0-0">
                        <a href="javascript:go_agent(this, '<?php echo e($row['id']); ?>');"
                            class="layui-table-link"><?php echo e($row['account']); ?></a>
                    </div>
                </td>
                <?php endif; ?>
                <td><?php echo e($row['user_name']); ?></td>
                <td><?php echo e($row['tel']); ?></td>
                <td><?php echo e($row['fee_type_name']); ?></td>
                <td><?php echo e($row['invitation_code']); ?></td>
                <td><?php echo e($row['status_name']); ?></td>
                <td>
                    <div class="layui-table-cell laytable-cell-1-0-1">
                        <span><?php echo e($row['cnt_sub_agent']); ?></span>
                    </div>
                </td>
                <?php if($isAL == 0): ?>
                <td>
                    <div class="layui-table-cell laytable-cell-1-0-1 layui-table-link">
                        <a href="javascript:subaccount(this, '<?php echo e($row['id']); ?>');"
                            class="layui-table-link"><?php echo e($row['cnt_sub_account']); ?></a>
                    </div>
                </td>
                <td>
                    <div class="layui-table-cell laytable-cell-1-0-1 layui-table-link">
                        <a href="javascript:promote_domin(this, '<?php echo e($row['id']); ?>');"
                            class="layui-table-link"><?php echo e($row['cnt_domain']); ?></a>
                    </div>
                </td>
                <?php endif; ?>
                <td><?php echo e($row['created_at']); ?></td>
                <?php if($isAL > -1): ?>
                <td>
                    <?php echo Form::button(trans('button.edit'),['id'=>"btn_edit_".$row['id'], 'name'=>"btn_edit_".$row['id'],
                    'class'=>'layui-btn layui-btn-primary layui-btn-sm', 'onclick'=>"edit(this,$row[id]);"]); ?>

                    <?php echo Form::button(trans('button.editfee'),['id'=>"btn_editfee_".$row['id'], 'name'=>"btn_editfee_".$row['id'],
                    'class'=>'layui-btn layui-btn-primary layui-btn-sm', 'onclick'=>"editfee(this,$row[id]);"]); ?>

                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo $__env->make('admin.pagination', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<script>
    layui.use('form', function(){
        var form = layui.form;

        form.on('submit(go)', function(data){
            $("input[name='page']").val(1);
        });
    });

    function doSearch()
    {
        $('#form_search').submit();
    }

    function add()
    {
        location.href = "<?php echo e(url('admin/agent_list/add')); ?>/<?php echo e($pid); ?>";
    }
    function edit(obj, id)
    {
        location.href = "<?php echo e(url('admin/agent_list/edit')); ?>/" + id;
    }
    function editfee()
    {
        location.href = "<?php echo e(url('admin/game_limit')); ?>";
    }
    
    
    function go_agent(obj, id)
    {
        location.href = "<?php echo e(url('admin/agent_list')); ?>/"+id;
    }

    function subaccount(obj, id)
    {
        location.href = "<?php echo e(url('admin/agent_list/subaccount')); ?>/"+id;
    }

    function promote_domin(obj, id)
    {
        location.href = "<?php echo e(url('admin/agent_list/domain')); ?>/"+id;
    }

    function go_agent_all(obj, id)
    {
        location.href = "<?php echo e(url('admin/agent_list/all')); ?>/"+id;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>