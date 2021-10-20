<?php echo $__env->make('admin.breadcrumbs', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->startSection('content'); ?>
<div class="layui-fluid">

    <div class="searchTable">
        <div class="layui-row">
            <div class="layui-col-xs10">
                <?php echo Form::open(['url' => '/admin/sport_market', 'name'=>'form_search', 'id'=>'form_search',
                'method'=>'get', 'class'=>'layui-form']); ?>

                <?php echo Form::hidden('page', setEmptyDef($search['page'], 1), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>

                <?php echo Form::hidden('per_page', setEmptyDef($search['per_page'], 20), ['class'=>'layui-input',
                'autocomplete'=>'off']); ?>


                <?php echo e(trans('admin.sport_market.kind')); ?>：
                <div class="layui-input-inline" style="width:100px;">
                    <?php echo Form::select('kind_id', [""=>trans('admin.all')] + $kind_list,
                    setEmptyDef($search['kind_id']), ['lay-filter' => 'kind_id']); ?>

                </div>
                <?php echo e(trans('admin.sport_market.league')); ?>：
                <div class="layui-input-inline">
                    <?php echo Form::select('league_id', [""=>trans('admin.all')] + $league_list,
                    setEmptyDef($search['league_id']), ['lay-filter' => 'league_id', 'lay-search' => '']); ?>

                </div>

                <?php echo e(trans('admin.sport_odds.home_team')); ?>：
                <div class="layui-input-inline">
                    <div class="layui-input-inline" style="width:150px;">
                        <?php echo Form::text('home_team', setEmptyDef($search['home_team'], ''), ['id' =>
                        'home_team', 'class' => 'layui-input', 'autocomplete' => 'off', 'style' => 'width:150px;']); ?>

                    </div>
                </div>

                <?php echo e(trans('admin.date')); ?>：
                <div class="layui-input-inline">
                    <div class="layui-input-inline" style="width:110px;">
                        <?php echo Form::text('playing_time', setEmptyDef($search['playing_time'], ''), ['id' =>
                        'playing_time', 'class' => 'layui-input', 'autocomplete' => 'off', 'style' => 'width:110px;']); ?>

                    </div>
                </div>

                <?php echo e(trans('admin.sort')); ?>：
                <div class="layui-input-inline" style="width:100px">
                    <?php echo Form::select('sort',[""=>'']+$sort_list,setEmptyDef($search['sort_list'])); ?>

                    
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
                <?php if($isAL == 0): ?>
                    <?php echo e(Form::button(trans('admin.sport_market.manual').trans('button.odds_setting').'('.trans('admin.all').')',['id'=>'manual_odds_setting_all', 'name'=>'manual_odds_setting_all', 'onclick'=>'manual_odds_setting_all()',
                    'class'=>'layui-btn btn-color-black-1'])); ?>

                    <?php echo e(Form::button(trans('button.add'),['id'=>'btn_add', 'name'=>'btn_add', 'onclick'=>'add()',
                    'class'=>'layui-btn btn-color-black-1'])); ?>

                <?php endif; ?>
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
                <th><?php echo e(trans('admin.sport_market.kind')); ?></th>
                <th><?php echo e(trans('admin.sport_market.league')); ?></th>
                <th><?php echo e(trans('admin.sport_market.playing_time')); ?></th>
                <th><?php echo e(trans('admin.sport_market.home_team')); ?></th>
                <th><?php echo e(trans('admin.sport_market.away_team')); ?></th>
                <th><?php echo e(trans('admin.sport_market.score')); ?></th>
                <th><?php echo e(trans('admin.sport_market.status')); ?></th>
                <th><?php echo e(trans('admin.sport_market.game_status')); ?></th>
                <th><?php echo e(trans('admin.operate')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['id']); ?></td>
                <td><?php echo e($row['kind_name']); ?></td>
                <td><?php echo e($row['league_name']); ?></td>
                <td><?php echo e($row['playing_time']); ?></td>
                <td><?php echo e($row['home_team_name']); ?></td>
                <td><?php echo e($row['away_team_name']); ?></td>
                <td>
                    <?php if($row['game_status'] == 0): ?>
                    <?php echo e($row['home_score'] . '：' . $row['away_score']); ?>

                    <?php endif; ?>
                </td>
                <td><?php echo e($row['status_name']); ?></td>
                <td><?php echo e($row['game_status_name']); ?></td>
                <td>
                <?php if($isAL == 0): ?>
                    <?php if($row['status'] == 1): ?>
                    <?php echo Form::button(trans('button.cancel_market'),['id'=>"btn_cancel_bet_".$row['id'],
                    'name'=>"btn_cancel_bet_".$row['id'], 'class'=>'layui-btn layui-btn-danger layui-btn-sm',
                    'onclick'=>"cancel_bet(this,$row[id]);"]); ?>

                    <?php endif; ?>
                    <?php if($row['game_status'] == 1): ?>
                    <?php echo Form::button(trans('button.manual_award'),['id'=>"btn_award_".$row['id'],
                    'name'=>"btn_award_".$row['id'], 'class'=>'layui-btn layui-btn-primary layui-btn-sm',
                    'onclick'=>"award(this,$row[id]);"]); ?>

                    <?php else: ?>
                    <?php echo Form::button(trans('button.supplement_award'),['id'=>"btn_supplement_award_".$row['id'],
                    'name'=>"btn_supplement_award_".$row['id'], 'class'=>'layui-btn layui-btn-primary layui-btn-sm',
                    'onclick'=>"supplement_award(this,$row[id]);"]); ?>

                    <?php endif; ?>
                    <?php if($row['status'] == 1): ?>
                    <?php echo Form::button(trans('admin.sport_market.odds_add'),['id'=>"odds_add_".$row['id'],
                    'name'=>"odds_add_".$row['id'], 'class'=>'layui-btn layui-btn-primary layui-btn-sm',
                    'onclick'=>"odds_add(this,$row[id]);"]); ?>

                    <?php echo Form::button(trans('admin.sport_market.manual').trans('button.odds_setting'),['id'=>"odds_setting_".$row['id'],
                    'name'=>"odds_setting_".$row['id'], 'class'=>'layui-btn layui-btn-primary layui-btn-sm',
                    'onclick'=>"odds_setting(this,$row[id]);"]); ?>

                    <?php echo Form::button(trans('admin.sport_market.auto').trans('button.odds_setting'),['id'=>"odds_setting_".$row['id'],
                    'name'=>"odds_setting_".$row['id'], 'class'=>'layui-btn layui-btn-primary layui-btn-sm',
                    'onclick'=>"auto_odds_setting(this,$row[id]);"]); ?>

                    <?php endif; ?>
                <?php endif; ?>
                </td>
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
            elem: '#playing_time'
            ,lang: 'en'
        });

        form.on('select(kind_id)', function(data){
            $.ajax({
                type: "GET",
                url: 'sport_market/getLeagueList',
                data: {
                    'kind_id':data.value
                },
                dataType:'json',
                success: function(data){
                    var html = '';
                    html += '<option value="">' + "<?php echo e(trans('admin.all')); ?>" + '</option>';
                    for (let key in data) {
                        html += '<option value="' + key + '">' + data[key] + '</option>';
                    }
                    $("select[name='league_id']").empty();
                    $("select[name='league_id']").html(html);
                    form.render('select');
                }
            });
        });

        form.on('submit(go)', function(data){
            $("input[name='page']").val(1);
        });
    });

    function doSearch()
    {
        $('#form_search').submit();
    }

    function award(obj, id = '')
    {
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '20px',
            area: ['1000px', '800px'],
            content: "<?php echo e(url('admin/sport_market/award')); ?>/" + id,
        });
    }

    function supplement_award(obj, id)
    {
        var Msg = "<?php echo e(trans('button.are_you_sure_supplement_award')); ?>？";

        layer.confirm(Msg, {
            icon: 3, title:"<?php echo e(trans('admin.message')); ?>", offset: '100px',
            btn : [ '<?php echo e(trans("button.confirm")); ?>', '<?php echo e(trans("button.cancel")); ?>' ]
        }, function(index){
            $.ajax({
                type: "POST",
                url: "<?php echo e(url('admin/sport_market/doSupplementAward')); ?>",
                data: {"_token":"<?php echo e(csrf_token()); ?>", "id":id},
                cache:false,
                dataType:"json",
                // async:false,
                success: function(r){
                    // console.log(r);
                    if(r.code != 200){
                        layer_alert(r.message);
                        return;
                    }
                    layer_alert_search(r.message);
                }
            });
            layer.close(index);
        });
    }
    function cancel_bet(obj, id)
    {
        var Msg = "<?php echo e(trans('button.are_you_sure_cancel')); ?>？";

        layer.confirm(Msg, {
            icon: 3, title:"<?php echo e(trans('admin.message')); ?>", offset: '100px',
            btn : [ '<?php echo e(trans("button.confirm")); ?>', '<?php echo e(trans("button.cancel")); ?>' ]
        }, function(index){
            $.ajax({
                type: "POST",
                url: "<?php echo e(url('admin/sport_market/doCancelMarket')); ?>",
                data: {"_token":"<?php echo e(csrf_token()); ?>", "id":id},
                cache:false,
                dataType:"json",
                // async:false,
                success: function(r){
                    // console.log(r);
                    if(r.code != 200){
                        layer_alert(r.message);
                        return;
                    }
                    layer_alert_search(r.message);
                }
            });
            layer.close(index);
        });
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
            content: "<?php echo e(url('admin/sport_market/add')); ?>",
        });
    }

    function odds_add(obj, id)
    {
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '15px',
            area: ['800px', '650px'],
            content: "<?php echo e(url('admin/sport_market/odds_add')); ?>/" + id,
        });
    }
    function odds_setting(obj, id = '')
    {
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '20px',
            area: ['1000px', '800px'],
            content: "<?php echo e(url('admin/sport_market/odds_setting')); ?>/" + id,
        });
    }
    function auto_odds_setting(obj, id = '')
    {
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '20px',
            area: ['1000px', '800px'],
            content: "<?php echo e(url('admin/sport_market/auto_odds_setting')); ?>/" + id,
        });
    }
    function manual_odds_setting_all(obj, id = '')
    {
        layer.open({
            type: 2,
            title: '',
            shadeClose: false,
            shade: 0.8,
            offset: '20px',
            area: ['1000px', '800px'],
            content: "<?php echo e(url('admin/sport_market/manual_odds_setting_all')); ?>/" + id,
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>