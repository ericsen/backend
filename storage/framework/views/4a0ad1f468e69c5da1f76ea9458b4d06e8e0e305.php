<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
	<title><?php echo e(trans('admin.sys_title')); ?></title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="<?php echo asset('css/login.css'); ?>" rel="stylesheet" type="text/css" />

	<frameset rows="88,*" cols="*" frameborder="0" border="0" framespacing="0">
		<frame src="/admin/top" name="topFrame" scrolling="no" noresize="noresize" id="topFrame" title="topFrame" />
		<frameset cols="250,*" frameborder="0" border="0" framespacing="0">
			<frame src="/admin/left" name="leftFrame" scrolling="auto" noresize="noresize" id="leftFrame"
				title="leftFrame" />
			<frame src="/admin/main" name="rightFrame" id="rightFrame" title="rightFrame" />
		</frameset>
	</frameset>
</head>

<noframes>

	<body>
	</body>

</noframes>

</html>