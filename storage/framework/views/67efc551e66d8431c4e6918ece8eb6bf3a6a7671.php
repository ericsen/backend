<!-- jquery -->
<script type="text/javascript" src="<?php echo asset('js/jquery-3.4.1.min.js'); ?>"></script>
<!-- layer -->
<script src="<?php echo asset('layer/layer.js'); ?>" type="text/javascript"></script>
<!-- layui -->
<link href="<?php echo asset('layui/css/layui.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo asset('layui/layui.js'); ?>" type="text/javascript"></script>
<!-- custom -->
<script type="text/javascript" src="<?php echo asset('js/common.js'); ?>"></script>
<link href="<?php echo asset('css/style.css'); ?>" rel="stylesheet" type="text/css" />

<div class="layui-tab" lay-filter="side">
    <ul class="layui-tab-title">
        <li lay-id="customerDetail"><?php echo e(trans('admin.customer_detail.customerDetail')); ?></li>
        <li lay-id="dayStatistics"><?php echo e(trans('admin.customer_detail.dayStatistics')); ?></li>
        <li lay-id="monthStatistics"><?php echo e(trans('admin.customer_detail.monthStatistics')); ?></li>
        <li lay-id="cashRecord"><?php echo e(trans('admin.customer_detail.cashRecord')); ?></li>
        <li lay-id="pointRecord"><?php echo e(trans('admin.customer_detail.pointRecord')); ?></li>
        <li lay-id="betRecord"><?php echo e(trans('admin.customer_detail.betRecord')); ?></li>
        <?php if(!Session::has('adminInfo.info.is_sub')): ?>
        <li lay-id="issueRecover2"><?php echo e(trans('admin.customer_issue.name')); ?></li>
        <li lay-id="add2"><?php echo e(trans('admin.site_message.name')); ?></li>
        <li lay-id="question"><?php echo e(trans('admin.customer_detail.question')); ?></li>
        <li lay-id="moneyCheck"><?php echo e(trans('admin.customer_detail.moneyCheck')); ?></li>
        <?php endif; ?>
    </ul>
</div>

<div class="layui-tab-content">
    <i class="layui-icon layui-icon-username" style="font-size: 30px; color: #c2c2c2;"><?php echo e(Request::get('account')); ?></i>   
    <!-- <span><?php echo e(Request::get('account')); ?></span> -->
    <?php echo $__env->yieldContent('customer_content'); ?>
</div>


<script>
    var id = "<?php echo e($id); ?>";
    var account = "<?php echo e($account); ?>";
    layui.use(['element'], function(){
        var element = layui.element;
        element.on('tab(side)', function(data){
            if(this.getAttribute('lay-id') == "cashRecord"){
                location.href = "<?php echo e(url('admin/customer/cashRecord')); ?>?id="+id + "&account="+account;
                return;
            }else if(this.getAttribute('lay-id') == "customerDetail"){
                location.href = "<?php echo e(url('admin/customer/customerDetail')); ?>?id="+id + "&account="+account;
                return;
            }else if(this.getAttribute('lay-id') == "pointRecord"){
                location.href = "<?php echo e(url('admin/customer/pointRecord')); ?>?id="+id + "&account="+account;
                return;
            }else if(this.getAttribute('lay-id') == "dayStatistics"){
                location.href = "<?php echo e(url('admin/customer/dayStatistics')); ?>?id="+id + "&account="+account;
            }else if(this.getAttribute('lay-id') == "monthStatistics"){
                location.href = "<?php echo e(url('admin/customer/monthStatistics')); ?>?id="+id + "&account="+account;
            }else if(this.getAttribute('lay-id') == "betRecord"){
                location.href = "<?php echo e(url('admin/customer/betRecord')); ?>?id="+id + "&account="+account;
            }else if(this.getAttribute('lay-id') == "issueRecover2"){
                location.href = "<?php echo e(url('admin/customer/issueRecover2')); ?>?id="+id + "&account="+account;
            }else if(this.getAttribute('lay-id') == "add2"){
                location.href = "<?php echo e(url('admin/customer/site_message_add')); ?>?id="+id + "&account="+account;
            }else if(this.getAttribute('lay-id') == "moneyCheck"){
                location.href = "<?php echo e(url('admin/customer/moneyCheck')); ?>?id="+id + "&account="+account;
            }else if(this.getAttribute('lay-id') == "question"){
                location.href = "<?php echo e(url('admin/customer/questionList')); ?>?id="+id + "&account="+account;
            }
        });
    });

    $(".layui-tab-title").children("li").each(function () {
        if ("<?php echo e(substr(Route::currentRouteAction(), (strpos(Route::currentRouteAction(), '@') + 1))); ?>" == $(this).attr("lay-id")) {
            $(this).attr('class','layui-this');
        }
    });
</script>