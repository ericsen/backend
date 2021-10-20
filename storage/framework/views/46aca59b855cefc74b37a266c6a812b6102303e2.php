<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
	<script type="text/javascript" src="<?php echo asset('js/jquery-3.4.1.min.js'); ?>"></script>
	<link href="<?php echo asset('css/AdminLTE-3.0.0-rc.4/plugins/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet"
		type="text/css" />

	<style>
		body {
			/* background-color: #353535; */
			background-color: #343A40;
			font-size: 16px;
		}

		/* 設定還沒瀏覽過的連結樣式 */
		a:link {
			color: #BFBFBF;
			text-decoration: none;
		}

		/* 設定已經瀏覽過的連結樣式 */
		a:visited {
			color: #BFBFBF;
			text-decoration: none;
		}

		/* 設定滑鼠移到連結上的樣式 */
		a:hover {
			color: #FFFFFF;
		}

		/* 設定正在被點選的連結樣式 */
		a:active {
			color: #FFFFFF;
		}

		#divTitle {
			display: inline;
			float: left;
		}

		#topTitle li {
			display: inline;
			color: #BFBFBF;
			font-size: 30px;
		}

		#divMsg {
			float: right;
		}

		#topMsg {
			/* margin-top: 30px; */
		}

		#topMsg li {
			display: inline;
			color: #BFBFBF;
			margin-top: 10px;
			margin-right: 25px;
			/* font-size: 18px; */
			float: right;
		}

		#divCnt {
			float: left;
			margin-left: 40px;
			margin-top: 30px;
		}

		#divCnt ul {
			margin: 0px;
			padding: 0px;
		}

		#divCnt li {
			display: inline;
			/* color: #BFBFBF; */
			/* margin-top:10px; */
			/* margin-right:25px; */
			/* font-size: 18px; */
			/* float:right; */
			margin: 0px;
			padding: 5px;
			text-align: center;
			/* float:left; */
			height: 20px;
			/* width:70px; */
			border: 1px solid #BFBFBF;
			background-color: #ffffff;
			color: #000000
		}

		#divCnt li a {
			color: #000000
		}

		.cntRadius {
			position: relative;
			display: inline-block;
			padding: 2px 8px;
			font-size: 14px;
			text-align: center;
			background-color: #FF5722;
			color: #fff;
			border-radius: 10px;
		}

		.cntRadius-bg-blue {
			background-color: #1E9FFF;
		}


		/* #divCnt {width:auto;height:auto;} */
		/* #divCnt ul {margin:0px;padding:0px;} */
		/* #divCnt li {margin:0px;padding:5px;text-align:center;float:left;height:20px;width:70px;border:1px solid #BFBFBF;background-color:#ffffff;color:#000000} */
		/* #divCnt li a {color:#000000} */
	</style>
</head>

<body>

	<div id="divTitle">
		<ul id="topTitle">
			<li><a href="/admin/home" target="_parent"><?php echo e(trans('admin.sys_title')); ?></a></li>
		</ul>
	</div>
	<?php if($isAL == 0): ?>
	<div id="divCnt">
		<ul>
			
			<li style="background-color:#BCBCBC;">
				<?php echo e(trans('admin.home_cnt.deposit_apply')); ?>

			</li>
			<li>
				<a href="javascript:do_ApplyDepositCnt();">
					<?php echo e(trans('admin.home_cnt.apply')); ?>

					<span id="ApplyDepositCnt" class="cntRadius">0</span>
				</a>
			</li>
			<li>
				<a href="javascript:do_ApplyDepositApproveCnt();">
					<?php echo e(trans('admin.home_cnt.finish')); ?>

					<span id="ApplyDepositApproveCnt" class="cntRadius cntRadius-bg-blue">0</span>
				</a>
			</li>

			
			<li style="background-color:#BCBCBC;">
				<?php echo e(trans('admin.home_cnt.withdraw_apply')); ?>

			</li>
			<li>
				<a href="javascript:do_ApplyWithdrawCnt();">
					<?php echo e(trans('admin.home_cnt.apply')); ?>

					<span id="ApplyWithdrawCnt" class="cntRadius">0</span>
				</a>
			</li>
			<li>
				<a href="javascript:do_ApplyWithdrawApproveCnt();">
					<?php echo e(trans('admin.home_cnt.finish')); ?>

					<span id="ApplyWithdrawApproveCnt" class="cntRadius cntRadius-bg-blue">0</span>
				</a>
			</li>

			
			<li style="background-color:#BCBCBC;">
				<?php echo e(trans('admin.home_cnt.customer_apply')); ?>

			</li>
			<li>
				<a href="javascript:do_CustomerApplyCnt();">
					<?php echo e(trans('admin.home_cnt.apply')); ?>

					<span id="CustomerApplyCnt" class="cntRadius">0</span>
				</a>
			</li>
			<li>
				<a href="javascript:do_CustomerApplyApproveCnt();">
					<?php echo e(trans('admin.home_cnt.finish')); ?>

					<span id="CustomerApplyApproveCnt" class="cntRadius cntRadius-bg-blue">0</span>
				</a>
			</li>

			
			<li style="background-color:#BCBCBC;">
				<?php echo e(trans('admin.home_cnt.customer_question')); ?>

			</li>
			<li>
				<a href="javascript:do_CustomerQuestionCnt();">
					<?php echo e(trans('admin.home_cnt.apply')); ?>

					<span id="CustomerQuestionCnt" class="cntRadius">0</span>
				</a>
			</li>
			<li>
				<a href="javascript:do_CustomerQuestionReplyCnt();">
					<?php echo e(trans('admin.home_cnt.finish')); ?>

					<span id="CustomerQuestionReplyCnt" class="cntRadius cntRadius-bg-blue">0</span>
				</a>
			</li>

			
			<li style="background-color:#BCBCBC;">
				<?php echo e(trans('admin.home_cnt.customer_online')); ?>

			</li>
			<li>
				<a href="javascript:do_CustomerOnlineCnt();">
					<span id="CustomerOnlineCnt" class="cntRadius">0</span>
				</a>
			</li>
		</ul>
	</div>
	<?php endif; ?>
	<div id="divMsg">
		<ul id="topMsg">
			<li>
				<a href="/admin/logout" target="_parent"><?php echo e(trans('button.logout')); ?></a>
			</li>
			<li>
				<i class="nav-icon fas fa-user"></i>
				<?php echo e($admin_name); ?>

			</li>
		</ul>
	</div>

</body>

</html>
<script>
	function do_ApplyDepositCnt()
	{
		parent.window.frames["leftFrame"].menu_click('nav-link_2_24');
	}
	function do_ApplyDepositApproveCnt(){
		parent.window.frames["leftFrame"].menu_href('2_24', "<?php echo e(url('admin/customer_deposit')); ?>?status=1&admin_updated_at=<?php echo e(date('Y-m-d')); ?>");
	}
	function do_ApplyWithdrawCnt()
	{
		parent.window.frames["leftFrame"].menu_click('nav-link_2_25');
	}
	function do_ApplyWithdrawApproveCnt(){
		parent.window.frames["leftFrame"].menu_href('2_25', "<?php echo e(url('admin/customer_withdraw')); ?>?status=1&admin_updated_at=<?php echo e(date('Y-m-d')); ?>");
	}
	function do_CustomerApplyCnt()
	{
		parent.window.frames["leftFrame"].menu_click('nav-link_9_21');
	}
	function do_CustomerApplyApproveCnt(){
		parent.window.frames["leftFrame"].menu_href('9_29', "<?php echo e(url('admin/customer')); ?>?admin_approve_at=<?php echo e(date('Y-m-d')); ?>");
	}
	function do_CustomerQuestionCnt()
	{
		parent.window.frames["leftFrame"].menu_click('nav-link_8_38');
	}
	function do_CustomerQuestionReplyCnt(){
		parent.window.frames["leftFrame"].menu_href('8_38', "<?php echo e(url('admin/customer_question')); ?>?status=1&answer_time=<?php echo e(date('Y-m-d')); ?>");
	}
	function do_CustomerOnlineCnt(){
		parent.window.frames["leftFrame"].menu_href('9_29', "<?php echo e(url('admin/customer')); ?>?is_online=1&type=1");
	}

	getCntData();
	ref = setInterval(function(){
		getCntData();
	},10*1000);

	function getCntData() {
		$.ajax({
			type: "POST",
			url: "<?php echo e(url('admin/home/getCntData')); ?>",
			data: {"_token":"<?php echo e(csrf_token()); ?>", "agent_id":"<?php echo e($agent_id); ?>"},
			cache:false,
			dataType:"json",
			// async:false,
			success: function(r){
				if(r.code != 200){
					console.log(r);
					// layer_alert(r.message);
					return;
				}
				<?php if($isAL == 0): ?>
				// 入款申請
				if ($("#ApplyDepositCnt").text() != r.data['ApplyDepositCnt']) {
					parent.window.frames["leftFrame"].menu_notice('info', "<?php echo e(trans('admin.layui_notice.ApplyDepositCnt')); ?>", '2_24');
				}
				$("#ApplyDepositCnt").text(r.data['ApplyDepositCnt']);
				$("#ApplyDepositApproveCnt").text(r.data['ApplyDepositApproveCnt']);

				// 出款申請
				if ($("#ApplyWithdrawCnt").text() != r.data['ApplyWithdrawCnt']) {
					parent.window.frames["leftFrame"].menu_notice('info', "<?php echo e(trans('admin.layui_notice.ApplyWithdrawCnt')); ?>", '2_25');
				}
				$("#ApplyWithdrawCnt").text(r.data['ApplyWithdrawCnt']);
				$("#ApplyWithdrawApproveCnt").text(r.data['ApplyWithdrawApproveCnt']);

				// 會員申請
				if ($("#CustomerApplyCnt").text() != r.data['CustomerApplyCnt']) {
					parent.window.frames["leftFrame"].menu_notice('info', "<?php echo e(trans('admin.layui_notice.CustomerApplyCnt')); ?>", '9_21');
				}
				$("#CustomerApplyCnt").text(r.data['CustomerApplyCnt']);
				$("#CustomerApplyApproveCnt").text(r.data['CustomerApplyApproveCnt']);

				// 客服詢問
				if ($("#CustomerQuestionCnt").text() != r.data['CustomerQuestionCnt']) {
					parent.window.frames["leftFrame"].menu_notice('info', "<?php echo e(trans('admin.layui_notice.CustomerQuestionCnt')); ?>", '8_38');
				}
				$("#CustomerQuestionCnt").text(r.data['CustomerQuestionCnt']);
				$("#CustomerQuestionReplyCnt").text(r.data['CustomerQuestionReplyCnt']);

				// 在線人數
				$("#CustomerOnlineCnt").text(r.data['CustomerOnlineCnt']);
				<?php endif; ?>

				// 現金總和、帳上點數總和、總入款、總出款、損益
				parent.window.frames["leftFrame"].$("#CustomerTotalMoney").text(r.data['CustomerTotalMoney']+"<?php echo e(trans('admin.won')); ?>");
				parent.window.frames["leftFrame"].$("#CustomerTotalPoint").text(r.data['CustomerTotalPoint']+"<?php echo e(trans('admin.won')); ?>");
				parent.window.frames["leftFrame"].$("#CustomerTotalDeposit").text(r.data['CustomerTotalDeposit']+"<?php echo e(trans('admin.won')); ?>");
				parent.window.frames["leftFrame"].$("#CustomerTotalWithdraw").text(r.data['CustomerTotalWithdraw']+"<?php echo e(trans('admin.won')); ?>");
				parent.window.frames["leftFrame"].$("#CustomerTotalIncome").text(r.data['CustomerTotalIncome']+"<?php echo e(trans('admin.won')); ?>");

				// 1天內漏開期數
				if (r.data['MissQishuPowerLadder'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_power_ladder').trans('admin.layui_notice.MissQishu')); ?>", '42_43', "<?php echo e(url('admin/gr_power_ladder')); ?>?status=2&bet_status=1&notice=1");
				}
				if (r.data['MissQishuKenoLadder'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_keno_ladder').trans('admin.layui_notice.MissQishu')); ?>", '42_44', "<?php echo e(url('admin/gr_keno_ladder')); ?>?status=2&bet_status=1&notice=1");
				}
				if (r.data['MissQishuPowerBall'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_power_ball').trans('admin.layui_notice.MissQishu')); ?>", '42_45', "<?php echo e(url('admin/gr_power_ball')); ?>?status=2&bet_status=1&notice=1");
				}
				// if (r.data['MissQishuLotusOddEven'] == 1) {
				// 	parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.lotus_odd_even').trans('admin.layui_notice.MissQishu')); ?>", '42_52', "<?php echo e(url('admin/gr_lotus_odd_even')); ?>?status=2&bet_status=1");
				// }
				// if (r.data['MissQishuLotusBaccarat1'] == 1) {
				// 	parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.lotus_bc1').trans('admin.layui_notice.MissQishu')); ?>", '42_55', "<?php echo e(url('admin/gr_lotus_baccarat/bc1')); ?>?status=2&bet_status=1");
				// }
				// if (r.data['MissQishuLotusBaccarat2'] == 1) {
				// 	parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.lotus_bc2').trans('admin.layui_notice.MissQishu')); ?>", '42_56', "<?php echo e(url('admin/gr_lotus_baccarat/bc2')); ?>?status=2&bet_status=1");
				// }
				if (r.data['MissQishuEsbLadder'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_esb_ladder').trans('admin.layui_notice.MissQishu')); ?>", '42_63', "<?php echo e(url('admin/gr_esb_ladder')); ?>?status=2&bet_status=1&notice=1");
				}
				if (r.data['MissQishuEsbPanda'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_esb_panda').trans('admin.layui_notice.MissQishu')); ?>", '42_64', "<?php echo e(url('admin/gr_esb_panda')); ?>?status=2&bet_status=1&notice=1");
				}
				if (r.data['MissQishuLadder1006'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_ladder_1006').trans('admin.layui_notice.MissQishu')); ?>", '42_67', "<?php echo e(url('admin/gr_ladder_1006')); ?>?status=2&bet_status=1&notice=1");
				}
				if (r.data['MissQishuLadder1007'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_ladder_1007').trans('admin.layui_notice.MissQishu')); ?>", '42_68', "<?php echo e(url('admin/gr_ladder_1007')); ?>?status=2&bet_status=1&notice=1");
				}
				if (r.data['MissQishuEsbBbhl'] == 1) {
					parent.window.frames["leftFrame"].menu_notice('error', "<?php echo e(trans('game.game_info.self_esb_bbhl').trans('admin.layui_notice.MissQishu')); ?>", '42_69', "<?php echo e(url('admin/gr_esb_bbhl')); ?>?status=2&bet_status=1&notice=1");
				}

				console.log(r.data);
			},
			error:function(r){
				if(r.status == 419){
					// console.log("419");
					parent.window.location.href='/admin/logout';
				}
			}
		});
	}
</script>