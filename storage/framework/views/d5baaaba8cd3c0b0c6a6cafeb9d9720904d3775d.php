<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<div class="layui-fluid">

    <div class="searchTable">
        <div class="layui-row">
            <div class="layui-col-xs10" style="width: 80%;">
                <?php echo Form::open(['url' => '/admin/customer', 'name'=>'form_search', 'id'=>'form_search', 'method'=>'get',
                'class'=>'layui-form']); ?>

                <?php echo Form::hidden('page', setEmptyDef($search['page'], 1), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>

                <?php echo Form::hidden('per_page', setEmptyDef($search['per_page'], 20), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>


                <?php echo e(trans('admin.customer.type')); ?>：
                <div class="layui-input-inline" style="width:100px">
                    <?php echo Form::select('type', [-1=>trans('admin.all')]+$type_list,
                    setEmptyDef($search['type'])); ?>

                </div>

                <?php echo e(trans('admin.agent_list.account')); ?>：
                <div class="layui-input-inline" style="width:120px;">
                    <?php echo Form::select('agent_id', ["-1"=>trans('admin.all')]+$agent_list,
                    setEmptyDef($search['agent_id']), ['lay-filter' => 'agent_id',
                    'lay-verify' => "", 'lay-search' => '']); ?>

                </div>

                <div class="layui-input-inline" style="width:100px">
                    <?php echo Form::select('kind', $kind_list, setEmptyDef($search['kind'])); ?>

                </div>：
                <div class="layui-input-inline">
                    <?php echo Form::text('kind_search', setEmptyDef($search['kind_search']), ['class'=>'layui-input',
                    'autocomplete'=>'off']); ?>

                </div>
                <br><br>
                <?php echo e(trans('admin.customer.is_online')); ?>：
                <div class="layui-input-inline" style="width:100px">
                    <?php echo Form::select('is_online', [-1=>trans('admin.all')]+$is_online_list,
                    setEmptyDef($search['is_online'])); ?>

                </div>

                <?php echo e(trans('admin.customer.status_1').trans('admin.date')); ?>：
                <div class="layui-input-inline">
                    <div class="layui-input-inline" style="width:110px;">
                        <?php echo Form::text('admin_approve_at', setEmptyDef($search['admin_approve_at'], ''), ['id' =>
                        'admin_approve_at', 'class' => 'layui-input', 'autocomplete' => 'off', 'style' =>
                        'width:110px;']); ?>

                    </div>
                </div>

                <?php echo e(trans('admin.sort')); ?>：
                <div class="layui-input-inline" style="width:100px">
                    <?php echo Form::select('sort',$sort_list,setEmptyDef($search['sort_list'])); ?>

                </div>
                <div class="layui-input-inline" style="width:100px">
                    <?php echo Form::select('desc_asc',$desc_asc,setEmptyDef($search['desc_asc'])); ?>

                </div>

                <?php echo e(Form::button(trans('button.search'),['id'=>'btn_search', 'name'=>'btn_search', 'type'=>'submit',
                'class'=>'layui-btn btn-color-black-1', 'lay-submit', 'lay-filter' => 'go'])); ?>

                <!-- <?php echo e(Form::button(trans('button.export'),['id'=>'btn_search', 'name'=>'btn_search', 'type'=>'button',
                'class'=>'layui-btn btn-color-black-1', 'lay-submit', 'lay-filter' => 'export'])); ?>

                <?php echo Form::close(); ?> -->
            </div>
            <?php if($isAL > -1): ?>
            <div class="layui-col-xs2" style="width: 20%;">
                <div style="float:right;">
                    <?php echo e(Form::button(trans('button.add').trans('admin.customer.name'),['id'=>'btn_add',
                    'name'=>'btn_add', 'onclick'=>'add()', 'class'=>'layui-btn btn-color-black-1'])); ?>

                    <?php echo e(Form::button(trans('button.issue').trans('button.recover'),['id'=>'btn_issue_recover',
                    'name'=>'btn_issue_recover', 'onclick'=>'issue_recover()', 'class'=>'layui-btn btn-color-black-1'])); ?>

                    <?php echo e(Form::button(trans('button.export'),['id'=>'btn_export', 'name'=>'btn_search', 'type'=>'submit',
                    'class'=>'layui-btn btn-color-black-1', 'lay-submit', 'lay-filter' => 'go', 'value' => 'export'])); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <table class="layui-table" lay-even>
        <!-- <colgroup>
            <col>
        </colgroup> -->
        <thead>
            <tr>
                <th><?php echo e(trans('admin.agent_list.account')); ?></th>
                <th><?php echo e(trans('admin.account')); ?></th>
                <th><?php echo e(trans('admin.user_name')); ?></th>
                <th><?php echo e(trans('admin.customer.level')); ?></th>
                <th><?php echo e(trans('admin.customer.profit_loss')); ?></th>
                <th><?php echo e(trans('admin.customer.deposit_info')); ?></th>
                <th><?php echo e(trans('admin.customer.withdraw_info')); ?></th>
                <th><?php echo e(trans('admin.customer.money')); ?></th>
                <th><?php echo e(trans('admin.customer.point')); ?></th>
                <?php if($isAL == 0): ?>
                <th><?php echo e(trans('admin.bank_info2')); ?></th>
                <?php endif; ?>
                <th><?php echo e(trans('admin.customer.created_at')); ?></th>
                <th><?php echo e(trans('admin.customer.status')); ?></th>
                <?php if($isAL > -1): ?>
                <th><?php echo e(trans('admin.operate')); ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['agent_account']); ?></td>
                <td>
                    <!-- <?php echo e($row['account']); ?> -->
                    <a class="layui-blue" href=""
                        onclick="user_detail('<?php echo e($row['id']); ?>', '<?php echo e($row['account']); ?>'); return false;">
                        <span style="color:#1E9FFF;"><?php echo e($row['account']); ?></span>
                    </a>
                    <br>
                    <?php echo e($row['customer_level_name']); ?>

                    <br>
                    <?php echo e($row['nickname']); ?>

                </td>
                <td><?php echo e($row['user_name']); ?></td>
                <td><?php echo e($row['customer_level']); ?></td>
                <td><?php echo NFormat($row['deposit_sum']-$row['withdraw_sum'],0 ,1); ?></td>
                <td><?php echo e(NFormat($row['deposit_sum'])); ?>(<?php echo e($row['deposit_cnt']); ?>)</td>
                <td><?php echo e(NFormat($row['withdraw_sum'])); ?>(<?php echo e($row['withdraw_cnt']); ?>)</td>
                <td><?php echo e(NFormat($row['money'])); ?></td>
                <td><?php echo e(NFormat($row['point'])); ?></td>
                <?php if($isAL ==0): ?>
                <td><?php echo e($row['bank_name']); ?><br><?php echo e($row['bank_account_name']); ?><br><?php echo e($row['bank_account']); ?></td>
                <?php endif; ?>
                <td><?php echo e($row['created_at']); ?></td>
                <td><?php echo e($row['status_name']); ?></td>
                <?php if($isAL > -1): ?>
                <td>
                    <?php echo Form::button(trans('button.edit'),['id'=>"btn_edit_".$row['id'], 'name'=>"btn_edit_".$row['id'],
                    'class'=>'layui-btn layui-btn-primary layui-btn-sm', 'onclick'=>"edit(this,$row[id]);"]); ?>

                    <?php echo e(Form::button(trans('button.issue').trans('button.recover'),['id'=>'btn_issue_recover',
                    'name'=>'btn_issue_recover', 'class'=>'layui-btn layui-btn-primary layui-btn-sm',
                    'onclick'=>"issue_recover('".$row['account']."')"])); ?>

                </td>
                <?php endif; ?>
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

        laydate.render({
            elem: '#admin_approve_at'
            ,lang: 'en'
        });

        form.on('submit(go)', function(data){
            // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
            // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
            // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
            // return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
            $("input[name='page']").val(1);
        });
        form.on('submit(export)', function(data){
            // console.log(data)
            // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
            // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
            // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
            // return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
            // $("input[name='page']").val(1);
            // data.field._token = "<?php echo e(csrf_token()); ?>"
            // console.log(data.field)
            location.href = "<?php echo e(url('admin/export_file/customer')); ?>?agent_id="+data.field.agent_id+"&kind="+data.field.kind+"&kind_search="+data.field.kind_search+"&is_online="+data.field.is_online+"&admin_approve_at="+data.field.admin_approve_at;
            /*$.ajax({
                type: "get",
                url: "<?php echo e(url('admin/customer/export')); ?>/",
                data: data.field,
                cache:false,
                // dataType:"json",
                // async:false,
                success: function(r){
                    // console.log(r);
                    if(r.code != 200){
                        layer_alert(r.message);
                        return;
                    }
                    doSearch();
                },
                beforeSend:function(){
                    // var loadding = layer.load();
                },
                error:function(){
                    // var loadding = layer.load();
                }
            });*/
        });
    });

    function doSearch()
    {
        $('#form_search').submit();
    }

    function add()
    {
        location.href = "<?php echo e(url('admin/customer/add')); ?>";
    }

    function edit(obj, id)
    {
        // alert(obj.id + ">>" + id);
        location.href = "<?php echo e(url('admin/customer/edit')); ?>/" + id;
    }

    function issue_recover(account = '')
    {
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '20px',
            area: ['1000px', '500px'],
            content: "<?php echo e(url('admin/customer/issueRecover')); ?>?account="+account,
        });
    }

    function user_detail(id = '', account = '')
    {
        window.open("<?php echo e(url('admin/customer/customerDetail')); ?>?id="+id+"&account="+account, target="_blank");
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>