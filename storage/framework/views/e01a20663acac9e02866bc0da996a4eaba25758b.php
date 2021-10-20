<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<!-- jQuery -->
	<script src="<?php echo asset('css/AdminLTE-3.0.0-rc.4/plugins/jquery/jquery.min.js'); ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo asset('css/AdminLTE-3.0.0-rc.4/dist/js/adminlte.js'); ?>"></script>
	<script src="<?php echo asset('css/AdminLTE-3.0.0-rc.4/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>">
	</script>

	<link href="<?php echo asset('css/AdminLTE-3.0.0-rc.4/plugins/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet"
		type="text/css" />
	<link href="<?php echo asset('css/AdminLTE-3.0.0-rc.4/dist/css/adminlte.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo asset('css/AdminLTE-3.0.0-rc.4/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>"
		rel="stylesheet" type="text/css" />

	<!-- layer -->
	<script src="<?php echo asset('layui/layui.js'); ?>" type="text/javascript"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php if(1): ?>
		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4" style="width:250px;">
			<div style="padding: 0 0.5rem">
				<span style="color:#C2C7D0;">
					<?php echo e(trans('admin.home_cnt.total_money')); ?>

					<span id="CustomerTotalMoney" style="float:right;color:#1E9FFF;">
						0<?php echo e(trans('admin.won')); ?>

					</span>
				</span>
				<br>
				<span style="color:#C2C7D0;">
					<?php echo e(trans('admin.home_cnt.total_point')); ?>

					<span id="CustomerTotalPoint" style="float:right;color:#2BCF5C;">
						0<?php echo e(trans('admin.won')); ?>

					</span>
				</span>
				<hr style="margin-top:5px;margin-bottom:3px;border: 0.5px solid #737171;">
				<span style="color:#C2C7D0;">
					<?php echo e(trans('admin.home_cnt.total_deposit')); ?>

					<span id="CustomerTotalDeposit" style="float:right;color:#E28A4D;">
						0<?php echo e(trans('admin.won')); ?>

					</span>
				</span>
				<br>
				<span style="color:#C2C7D0;">
					<?php echo e(trans('admin.home_cnt.total_withdraw')); ?>

					<span id="CustomerTotalWithdraw" style="float:right;color:#D85E62;">
						0<?php echo e(trans('admin.won')); ?>

					</span>
				</span>
				<hr style="margin-top:5px;margin-bottom:3px;border: 0.5px solid #737171;">
				<span style="color:#C2C7D0;">
					<?php echo e(trans('admin.home_cnt.total_income')); ?>

					<span id="CustomerTotalIncome" style="float:right;color:#2BCF5C;">
						0<?php echo e(trans('admin.won')); ?>

					</span>
				</span>
			</div>
			<br>
			<!-- Sidebar -->
			<div class="sidebar" style="height:72%;">
				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul id="menu_content" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"
						role="menu" data-accordion="true">
						<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

						<?php foreach($nav as $row){ ?>
						<li class="nav-item has-treeview" id="top-link_<?php echo e($row['id']); ?>">
							<a class="nav-link"
								href="<?php echo empty($row['url'])?'javascript:viod(0);':$row['url'];?>"
								id="nav-link_<?=$row['id']?>" target="rightFrame">
								<i class="nav-icon fas fa-th"></i>
								<p>
									<?=$row['name']?>
									<?php if(!empty($row['sub'])): ?> <i class="right fas fa-angle-left"></i> <?php endif; ?>
								</p>
							</a>
							<?php if(!empty($row['sub'])): ?>
							<ul class="nav nav-treeview">
								<?php foreach($row['sub'] as $sRow){ ?>
								<li class="nav-item">
									<a class="nav-link"
										href="<?php echo empty($sRow['url'])?'javascript:viod(0);':$sRow['url'];?>"
										target="rightFrame" id="nav-link_<?php echo "{$row['id']}_{$sRow['id']}"; ?>">
										<i class="far fa-circle nav-icon cus-sub-icon-b" style="font-size:10px"></i>
										<p><?=$sRow['name']?></p>
									</a>
								</li>
								<?php } ?>
							</ul>
							<?php endif; ?>
						</li>
						<?php } ?>
						<span style="height:200px"></span>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>
		<?php endif; ?>
	</div>
	<!-- ./wrapper -->


</body>

</html>
<script>
	$(".nav-link").each(function(){
		this.addEventListener("click",function(e){
			tag = this.id.split("_");
			if(tag.length <= 2){
				if(this.href == 'javascript:viod(0);'){ // 有第二層
					return;
				}
				$('.active').removeClass('active');
				// 父類別 - 為連結
				$("#nav-link_"+tag[1]).addClass("active");
			}else{
				$('.active').removeClass('active');
				// 父類別
				$("#nav-link_"+tag[1]).addClass("active");
				// 子類別
				$(this).addClass("active");
			}
		});
	});

	function menu_click(id)
	{
		menu_reload();

		var arr= new Array();
		arr = String(id).split("_");
		var arr_len = arr.length;
		var id_str = arr[0];
		for(var i=1; i < arr_len; i++){
			id_str += "_"+arr[i];
			// console.log(id_str);
			$('#'+id_str)[0].click();
		}
	}

	function menu_reload()
	{
		$(".nav-item").each(function(){
			if($(this).hasClass("menu-open")){
				top_id = this.id;
				nav_id = this.id.replace('top','nav');

				$("#"+top_id).removeClass("menu-open");
				$("#"+top_id+" > ul").css("display", "none");

				// if(top_id == 'top-link_2'){
				// 	$("#"+top_id).removeClass("menu-open");
				// 	$("#"+top_id+" > ul").css("display", "none");
				// }else{
				// 	$("#"+nav_id)[0].click();
				// }
			}
		});
		$(".nav-link").each(function(){
			$('.active').removeClass('active');
		});
		// console.log(menu_reload);
	}

	layui.config({
		base: '../layui_exts/' //配置 layui 第三方扩展组件存放的基础目录
	}).extend({
		notice: 'notice/notice' //以 notice 组件为例，定义该组件模块名
	}).use(['notice'], function(){
		var notice = layui.notice; // 允许别名
		// 初始化配置，同一样式只需要配置一次，非必须初始化，有默认配置
		notice.options = {
			closeButton:false,//显示关闭按钮
			debug:false,//启用debug
			positionClass:"toast-bottom-full-width",//弹出的位置,
			showDuration:"300",//显示的时间
			hideDuration:"1000",//消失的时间
			timeOut:8*1000,//停留的时间
			extendedTimeOut:"1000",//控制时间
			showEasing:"swing",//显示时的动画缓冲方式
			hideEasing:"linear",//消失时的动画缓冲方式
			iconClass: 'toast-info', // 自定义图标，有内置，如不需要则传空 支持layui内置图标/自定义iconfont类名
			onclick: null, // 点击关闭回调
		};
	});

	/**
	* 側邊攔提示
	* @param  type 提示類型：warning、info、error、success
	* @param  str 提示文字
	* @param  menu_id 左側選單ID
	* @param  url 跳轉網址
	*/
	function menu_notice(type, str, menu_id = '', url = '') {
		layui.use(['notice'], function(){
			if (menu_id != '') {
				layui.notice.options.onclick = function(){
					menu_href(menu_id, url);
				}
			}
			layui.notice[type](str); // 允许别名
		});
	}

	/**
	* 頁面跳轉
	* @param  menu_id 左側選單ID
	* @param  url 跳轉網址
	*/
	function menu_href(menu_id, url = '') {
		if (url != '') {
			menu_id_arr = menu_id.split('_');
			menu_reload();
			$('#nav-link_' + menu_id_arr[0]).click();
			$('#nav-link_' + menu_id_arr[0]).addClass("active");
			$('#nav-link_' + menu_id).addClass("active");
			parent.window.frames["rightFrame"].location.href = url;
		} else {
			menu_click('nav-link_' + menu_id);
		}
	}
</script>