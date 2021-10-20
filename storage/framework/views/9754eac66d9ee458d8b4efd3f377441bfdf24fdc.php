<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<div class="layui-fluid">

    <div class="searchTable">
        <div class="layui-row">
            <div class="layui-col-xs10">
                <?php echo Form::open(['url' => '/admin/sport_league', 'name'=>'form_search', 'id'=>'form_search',
                'method'=>'get', 'class'=>'layui-form']); ?>

                <?php echo Form::hidden('page', setEmptyDef($search['page'], 1), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>

                <?php echo Form::hidden('per_page', setEmptyDef($search['per_page'], 20), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>


                <?php echo e(trans('admin.sport_league.kind')); ?>：
                <div class="layui-input-inline" style="width:100px;">
                    <?php echo Form::select('kind_id', [""=>trans('admin.all')] + $kind_list,
                    setEmptyDef($search['kind_id']), ['lay-filter' => 'kind_id']); ?>

                </div>

                <?php echo e(trans('admin.sport_league.league')); ?>：
                <div class="layui-input-inline">
                    <div class="layui-input-inline" style="width:150px;">
                        <?php echo Form::text('league_name', setEmptyDef($search['league_name'], ''), ['id' =>
                        'league_name', 'class' => 'layui-input', 'autocomplete' => 'off', 'style' => 'width:150px;']); ?>

                    </div>
                </div>

                <?php echo e(trans('admin.sport_league.status')); ?>：
                <div class="layui-input-inline">
                    <div class="layui-input-inline" style="width:100px;">
                    <?php echo Form::select('status', [""=>trans('admin.all')] + $status_list,
                    setEmptyDef($search['status']), ['lay-filter' => 'status']); ?>

                    </div>
                </div>

                <?php echo e(Form::button(trans('button.search'),['id'=>'btn_search', 'name'=>'btn_search', 'type'=>'submit',
                'class'=>'layui-btn btn-color-black-1', 'lay-submit', 'lay-filter' => 'go'])); ?>

                <?php echo Form::close(); ?>

            </div>
            <div class="layui-col-xs2">
                <div style="float:right;">
                    <?php echo e(Form::button(trans('button.add'),['id'=>'btn_add', 'name'=>'btn_add', 'onclick'=>'add()',
                    'class'=>'layui-btn btn-color-black-1'])); ?>

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
                <th><?php echo e(trans('admin.id')); ?></th>
                <th><?php echo e(trans('admin.sport_league.kind')); ?></th>
                <th><?php echo e(trans('admin.sport_league.league')); ?></th>
                <th><?php echo e(trans('admin.sport_league.league')); ?>(en)</th>
                <th><?php echo e(trans('admin.sport_league.status')); ?></th>
                <?php if($isAL == 0): ?>
                <th><?php echo e(trans('admin.operate')); ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['id']); ?></td>
                <td><?php echo e($row['kind_name']); ?></td>
                <td><?php echo e($row['league_name']); ?></td>
                <td><?php echo e($row['league_name_en']); ?></td>
                <td><?php echo e($row['status_name']); ?></td>
                <?php if($isAL == 0): ?>
                <td><?php echo Form::button(trans('button.edit'),['id'=>"btn_edit_".$row['id'], 'name'=>"btn_edit_".$row['id'],
                    'class'=>'layui-btn layui-btn-primary layui-btn-sm', 'onclick'=>"edit(this,$row[id]);"]); ?>

                    <?php echo Form::button(trans('button.del'),['id'=>"btn_edit_".$row['id'], 'name'=>"btn_edit_".$row['id'],
                    'class'=>'layui-btn layui-btn-danger layui-btn-sm', 'onclick'=>"del(this,$row[id]);"]); ?>

                    </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo $__env->make('admin.pagination', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<script>
    layui.use(['form'], function(){
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
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '15px',
            area: ['800px', '650px'],
            content: "<?php echo e(url('admin/sport_league/add')); ?>",
        });
    }

    function edit(obj, id)
    {
        // location.href = "<?php echo e(url('admin/sport_league/edit')); ?>/" + id;

        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '20px',
            area: ['750px', '500px'],
            content: "<?php echo e(url('admin/sport_league/edit')); ?>/" + id,
        });
    }

    function del(obj, id)
    {
        layer.confirm("<?php echo e(trans('button.del_confirm')); ?>？", {
            icon: 3, title:"<?php echo e(trans('admin.message')); ?>", offset: '100px',
            btn : [ '<?php echo e(trans("button.confirm")); ?>', '<?php echo e(trans("button.cancel")); ?>' ]
        }, function(index){
            $.ajax({
                type: "POST",
                url: "<?php echo e(url('admin/sport_league/doDel')); ?>/" + id,
                data: {"_token":"<?php echo e(csrf_token()); ?>"},
                cache:false,
                dataType:"json",
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
            });
            layer.close(index);
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>