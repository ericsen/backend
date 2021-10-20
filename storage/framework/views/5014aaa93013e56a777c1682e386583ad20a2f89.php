<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<style>
    .layui-table td {
        padding: 5px 15px;
    }

    .layui-table td,
    .layui-table th {
        padding: 5px 15px;
    }

    .layui-card-header {
        padding: 5px 15px;
        height: auto;
        line-height: 24px;
    }

    .layui-card-body {
        padding: 5px 15px;
    }

    .layui-card {
        background-color: transparent;
    }
</style>

<div class="layui-fluid">

    <div class="searchTable">
        <div class="layui-row">
            <div class="layui-col-xs10">
                <?php echo Form::open(['url' => '/admin/customer_report', 'name'=>'form_search', 'id'=>'form_search',
                'method'=>'get', 'class'=>'layui-form']); ?>

                <?php echo Form::hidden('page', setEmptyDef($search['page'], 1), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>

                <?php echo Form::hidden('per_page', setEmptyDef($search['per_page'], 20), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>


                <?php echo e(trans('admin.agent_list.account')); ?>：
                <div class="layui-input-inline" style="width:120px;">
                    <?php echo Form::select('agent_id', ["-1"=>trans('admin.all')]+$agent_list,
                    setEmptyDef($search['agent_id']), ['lay-filter' => 'agent_id',
                    'lay-verify' => "", 'lay-search' => '']); ?>

                </div>

                <?php echo e(trans('admin.date')); ?>：
                <div class="layui-input-inline">
                    <?php echo Form::text('date_start', $search['date_start'], ['id'=>'date_start', 'class'=>'layui-input',
                    'autocomplete'=>'off', 'style'=>'width:110px;']); ?>

                </div>
                ～
                <div class="layui-input-inline">
                    <?php echo Form::text('date_end', $search['date_end'], ['id'=>'date_end', 'class'=>'layui-input',
                    'autocomplete'=>'off', 'style'=>'width:110px;']); ?>

                </div>

                <?php echo e(trans('admin.sort')); ?>：
                <div class="layui-input-inline" style="width:100px;">
                    <?php echo Form::select('sort', $sortList, setEmptyDef($search['sortList'])); ?>

                </div>
                <div class="layui-input-inline" style="width:100px;">
                    <?php echo Form::select('desc_asc', $desc_asc, setEmptyDef($search['desc_asc'])); ?>

                </div>

                <?php echo e(Form::button(trans('button.search'),['id'=>'btn_search', 'name'=>'btn_search', 'type'=>'submit',
                'class'=>'layui-btn btn-color-black-1', 'lay-submit', 'lay-filter' => 'go'])); ?>

                <?php echo Form::close(); ?>

            </div>
            <div class="layui-col-xs2">
                <div style="float:right;">

                </div>
            </div>
        </div>
    </div>

    <table class="layui-table" lay-even>
        <!-- <colgroup>
            <col>
        </colgroup> -->
        <thead>
            <tr>
                <th rowspan=2><?php echo e(trans('admin.id')); ?></th>
                <th rowspan=2><?php echo e(trans('admin.agent_list.account')); ?></th>

                <th rowspan=2><?php echo e(trans('admin.account')); ?></th>
                <th><?php echo e(trans('admin.customer.money')); ?></th>
                <th><?php echo e(trans('admin.customer_report.deposit')); ?></th>
                <th><?php echo e(trans('admin.customer_report.withdraw')); ?></th>
                <th><?php echo e(trans('admin.customer_report.give_money')); ?></th>
                <th><?php echo e(trans('admin.customer_report.give_point')); ?></th>
                <th><?php echo e(trans('admin.customer_report.total_bet')); ?></th>


                <th rowspan=2><?php echo e(trans('admin.customer_report.profit_loss')); ?></th>
                <th rowspan=2><?php echo e(trans('admin.customer.status')); ?></th>
            </tr>
            <tr>
                <th><?php echo e(trans('admin.customer.point')); ?></th>
                <th><?php echo e(trans('admin.customer_report.deposit_times')); ?></th>
                <th><?php echo e(trans('admin.customer_report.withdraw_times')); ?></th>
                <th><?php echo e(trans('admin.customer_report.recover_money')); ?></th>
                <th><?php echo e(trans('admin.customer_report.recover_point')); ?></th>
                <th><?php echo e(trans('admin.customer_report.total_issue')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['id']); ?></td>
                <td><?php echo e($row['agent_account']); ?></td>
                <td>
                    <?php echo e($row['account']); ?>

                    <br>
                    <?php echo e($row['customer_level_name']); ?>

                    <br>
                    <?php echo e($row['nickname']); ?>

                </td>
                <td style="padding:0px;">
                    <div class="layui-card">
                        <div class="layui-card-header"><?php echo e(NFormat($row['money'])); ?></div>
                        <div class="layui-card-body">
                            <?php echo e(NFormat($row['point'])); ?>

                        </div>
                    </div>
                </td>
                <td style="padding:0px;">
                    <div class="layui-card">
                        <div class="layui-card-header"><?php echo e(NFormat($row['apply_money_1'])); ?></div>
                        <div class="layui-card-body">
                            <?php echo e($row['apply_money_cnt_1']); ?>

                        </div>
                    </div>
                </td>
                <td style="padding:0px;">
                    <div class="layui-card">
                        <div class="layui-card-header"><?php echo e(NFormat($row['apply_money_0'])); ?></div>
                        <div class="layui-card-body">
                            <?php echo e($row['apply_money_cnt_0']); ?>

                        </div>
                    </div>
                </td>
                <td style="padding:0px;">
                    <div class="layui-card">
                        <div class="layui-card-header"><?php echo e(NFormat($row['give_money'])); ?></div>
                        <div class="layui-card-body">
                            <?php echo e(NFormat($row['recover_money'])); ?>

                        </div>
                    </div>
                </td>
                <td style="padding:0px;">
                    <div class="layui-card">
                        <div class="layui-card-header"><?php echo e(NFormat($row['give_point'])); ?></div>
                        <div class="layui-card-body">
                            <?php echo e(NFormat($row['recover_point'])); ?>

                        </div>
                    </div>
                </td>
                <td style="padding:0px;">
                    <div class="layui-card">
                        <div class="layui-card-header"><?php echo e(NFormat($row['total_bet'])); ?></div>
                        <div class="layui-card-body">
                            <?php echo e(NFormat($row['total_issue'])); ?>

                        </div>
                    </div>
                </td>
                <td><?php echo NFormat($row['profit_loss'],0,1); ?></td>
                <td><?php echo e($row['status_name']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo $__env->make('admin.pagination', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<script>
    layui.use(['form','laydate'], function(){
        var form = layui.form;
        var laydate = layui.laydate;

        form.on('submit(go)', function(data){
            $("input[name='page']").val(1);
        });

        laydate.render({
            elem: '#date_start'
            ,lang: 'en'
        });
        laydate.render({
            elem: '#date_end'
            ,lang: 'en'
        });
    });

    function doSearch()
    {
        $('#form_search').submit();
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>