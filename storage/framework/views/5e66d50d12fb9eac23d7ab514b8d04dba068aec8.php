<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link href="<?php echo asset('css/login.css'); ?>" rel="stylesheet" type="text/css" />
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
    
</head>

<body>
<div class="layui-fluid">
<div class="searchTable">
<div class="layui-row">
    <div class="layui-col-xs11">
    &nbsp;
    </div>
    <div class="layui-col-xs1">
        <?php echo Form::open(['url' => '/admin/changeLang', 'name'=>'form_search', 'id'=>'form_search', 'method'=>'get', 'class'=>'layui-form']); ?>

            <div class="layui-input-inline" style="width:100px;">
            <?php echo Form::select('lang', $language_list, $search['lang'], ['lay-filter'=>'lang']); ?>

            </div>
        <?php echo Form::close(); ?>

    </div>
</div>
</div>
</div>
<div class="login-page" style="width:100%;">
    <div class="form" style="width:270px;">
    <?php echo Form::open(['url' => '/admin/login','name'=>'form_login' ,'class'=>'login-form','style'=>'width:270px;']); ?>

        <?php echo Form::text('account', '', ['placeholder'=>trans('admin.account')]); ?>

        <?php echo Form::password('password', ['placeholder'=>trans('admin.password')]); ?>

        <?php echo e(Form::button(trans('button.login'),['type'=>'submit'])); ?>

    <?php echo Form::close(); ?>

    </div>

</div>
</body>
</html>
<script>
layui.use('form', function(){
    var form = layui.form;

    form.on('select(lang)', function(data){
        // console.log(data.value);
        doSearch();
    });
});
function doSearch()
{
    $('#form_search').submit();
}
<?php if(\Session::has('myMessage')): ?>
    layer_alert("<?php echo MsgToString(Session::get('myMessage')); ?>");
<?php endif; ?>

function layer_alert(msg)
{
    layer.alert(msg, {title: "<?php echo e(trans('admin.message')); ?>", btn: false, offset: '150px', shadeClose: true});
}
</script>
