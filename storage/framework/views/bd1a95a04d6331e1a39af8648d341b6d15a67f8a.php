<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
    <title><?php echo $__env->yieldContent('title'); ?></title>

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

    <?php echo $__env->yieldContent('head'); ?>
    <style>
        .layui-formbody {
            padding: 10px 18px;
            margin: 0 auto;
            text-align: center;
        }

        .layui-form-item {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <?php echo $__env->yieldContent('content'); ?>
</body>

</html>
<script>
    <?php if(\Session::has('myMessage')): ?>
        // layer.alert("<?php echo MsgToString(Session::get('myMessage')); ?>", {title: "<?php echo e(trans('admin.message')); ?>", btn: false});
        layer_alert("<?php echo MsgToString(Session::get('myMessage')); ?>");
    <?php endif; ?>

    function layer_alert(msg)
    {
        layer.alert(msg, {title: "<?php echo e(trans('admin.message')); ?>", btn: false, offset: '50px', shadeClose: true});
    }

    function layer_alert_search(msg)
    {
        layer.alert(msg, {title: "<?php echo e(trans('admin.message')); ?>", btn: false, offset: '50px', shadeClose: true, end:function(){doSearch();}});
    }
</script>
<?php echo $__env->yieldContent('javascript'); ?>