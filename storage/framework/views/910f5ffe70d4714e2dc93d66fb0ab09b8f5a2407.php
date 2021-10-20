<?php $__env->startSection('customer_content'); ?>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend><?php echo e(trans('admin.customer_detail.customerDetail')); ?></legend>
</fieldset>


<div class="layui-form">
        <table class="layui-table">
            <colgroup>
            <col width="150">
            <col width="150">
            <col width="200">
            <col>
            </colgroup>
                <thead>
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                      google.charts.setOnLoadCallback(drawChart);

                       function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                              ['Task', 'Hours per Month'],
                              ['ICG',     11],
                              ['BBIN',      2],
                              ['CQ9',  2],
                              ['MG', 2],
                              ['PT',    7]
                         ]);

                            var options = {
                            title: '<?php echo e(trans('admin.customer_detail.charttitle')); ?>',
                            is3D: true,
                             };

                           var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                        chart.draw(data, options);
                      }
                  </script>
                  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                  <script type="text/javascript">
                    google.charts.load("current", {packages:["corechart"]});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                      var data = google.visualization.arrayToDataTable([
                        ['Task', 'Hours per Day'],
                        ['Work',     11],
                        ['Eat',      2],
                        ['Commute',  2],
                        ['Watch TV', 2],
                        ['Sleep',    7]
                      ]);
              
                      var options = {
                        title: 'My Daily Activities',
                        pieHole: 0.4,
                      };
              
                      var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                      chart.draw(data, options);
                    }
                  </script>
                <tr>
                    <div id="piechart" style="width: 900px; height: 500px;"></div>
                    
                    
                </tr>
                <tr>
                    <th><?php echo e(trans('admin.customer_detail.agent')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.level')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.profit')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.deposit_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.issue_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.money')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.give_cash_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.give_point_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.convert_point')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.bet_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.wait_cash')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.reg_time')); ?></th>
                </tr> 
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($result['agent_id']); ?></td>
                        <td><?php echo e($result['customer_level']); ?></td>
                        <td><?php echo $result['profit']; ?></td>
                        <td><?php echo e($result['depositTotal']); ?></td>
                        <td><?php echo e($result['issueTotal']); ?></td>
                        <td><?php echo e($result['money']); ?></td>
                        <td><?php echo e($result['giveCashTotal']); ?></td>
                        <td><?php echo e($result['givePointTotal']); ?></td>
                        <td><?php echo e($result['convertPoint']); ?></td>
                        <td><?php echo e($result['betTotal']); ?>(<?php echo e($result['betCount']); ?>)</td>
                        <td><?php echo e($result['waitCash']); ?></td>
                        <td><?php echo e($result['created_at']); ?></td>
                    </tr>
                </tbody>

                <thead>
                <tr>
                    <th><?php echo e(trans('admin.customer_detail.customer_code')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.status')); ?></th>
                    <!-- <th colspan="3"><?php echo e(trans('admin.customer_detail.customer_code')); ?></th> -->
                    <!-- <th><?php echo e(trans('admin.customer_detail.customer_code')); ?></th> -->
                    <!-- <th><?php echo e(trans('admin.customer_detail.promise')); ?></th> -->
                    <th><?php echo e(trans('admin.customer_detail.discount')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.withdrawal_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.lose_total')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.point')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.recycle_cash')); ?></th>
                    <th colspan="2"><?php echo e(trans('admin.customer_detail.recycle_point')); ?></th>
                    <!-- <th><?php echo e(trans('admin.customer_detail.recycle_point')); ?></th> -->
                    <th><?php echo e(trans('admin.customer_detail.issue_total2')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.expected_issue')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.login_time')); ?></th>
                </tr> 
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($result['invitation_code']); ?></td>
                        <td><?php echo e($result['status_name']); ?></td>
                        <!-- <td colspan="3"><?php echo e($result['invitation_code']); ?></td> -->
                        <!-- <td><?php echo e($result['customerCode']); ?></td> -->
                        <!-- <td><?php echo e($result['promise']); ?></td> -->
                        <td><?php echo e($result['discountUseTotal']); ?>/ <?php echo e($result['discountTotal']); ?></td>
                        <td><?php echo e($result['withdrawalTotal']); ?></td>
                        <td><?php echo e($result['loseTotal']); ?></td>
                        <td><?php echo e($result['point']); ?></td>
                        <td><?php echo e($result['recycleCash']); ?></td>
                        <td colspan="2"><?php echo e($result['recyclePoint']); ?></td>
                        <!-- <td><?php echo e($result['recyclePoint']); ?></td> -->
                        <td><?php echo e($result['issueTotal']); ?>(<?php echo e($result['issueTotalCount']); ?>)</td>
                        <td><?php echo e($result['expectedIssue']); ?></td>
                        <td><?php echo e($result['loginTime']); ?></td>
                    </tr>
                </tbody>
        </table>
    </div>
    <table class="layui-table" width="100%">
    <thead>
    <tr>
        <th width="50%"><?php echo e(trans('admin.customer_detail.login_list')); ?></th>
        <?php if($isAL == 0): ?>
        <th width="50%"><?php echo e(trans('admin.customer_detail.memo')); ?></th>
        <?php else: ?>
        <th width="50%"></th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <td>
        <table class="layui-table" width="100%">
            <thead>
                <tr>
                    <th><?php echo e(trans('admin.customer_detail.login_time')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.login_url')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.login_ip')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.login_agent')); ?></th>
                </tr> 
            </thead>
            <tbody>
                <?php $__currentLoopData = $result['loginList']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row['loginTime']); ?></td>
                    <td><?php echo e($row['loginUrl']); ?></td>
                    <td><?php echo e($row['loginIp']); ?></td>
                    <td><?php echo e($row['loginAgent']); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </div>
        </table>
    </td>
    <td>
        <?php if($isAL == 0): ?>
        <textarea id="memo" required lay-verify="required" class="layui-textarea" style="height: 100%;"><?php echo e($result['memo']); ?></textarea>
        <button type="button" class="layui-btn" onclick="doUpMemo()"><?php echo e(trans('admin.customer_detail.save')); ?></button>
        <?php endif; ?>
    </td>
    </tbody>
    </table>

    <table class="layui-table" width="100%">
    <thead>
    <tr>
        <th width="50%"><?php echo e(trans('admin.customer_detail.deposit_list')); ?></th>
        <th width="50%"><?php echo e(trans('admin.customer_detail.withdrawal_list')); ?></th>
    </tr>
    </thead>
    <tbody>
    <td>
        <table class="layui-table" width="100%">
            <thead>
                <tr>
                    <th><?php echo e(trans('admin.customer_detail.apply_time')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.apply_money')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.customer_money')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.status')); ?></th>
                </tr> 
            </thead>
            <tbody>
                <?php $__currentLoopData = $result['depositList']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row['apply_time']); ?></td>
                    <td><?php echo e($row['apply_money']); ?></td>
                    <td><?php echo e($row['customer_money']); ?></td>
                    <td><?php echo e($row['status']); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </div>
        </table>
    </td>
    <td>
    <table class="layui-table" width="100%">
            <thead>
                <tr>
                    <th><?php echo e(trans('admin.customer_detail.apply_time')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.apply_money')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.customer_money')); ?></th>
                    <th><?php echo e(trans('admin.customer_detail.status')); ?></th>
                </tr> 
            </thead>
            <tbody>
                <?php $__currentLoopData = $result['withdrawaList']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row['apply_time']); ?></td>
                    <td><?php echo e($row['apply_money']); ?></td>
                    <td><?php echo e($row['customer_money']); ?></td>
                    <td><?php echo e($row['status']); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </div>
        </table>
    </td>
    </tbody>
    </table>
</div>
<script>
    
var id = "<?php echo e($id); ?>";
var account = "<?php echo e($account); ?>";

function doUpMemo(){
    var memo = $('#memo').val();
    console.log(memo);
    $.ajax({
        type: "POST",
        url: "<?php echo e(url('admin/customer/doUpMemo')); ?>",
        data: {
            "_token":"<?php echo e(csrf_token()); ?>",
            "id": id,
            "account": account,
            "memo": memo,
            },
        cache:false,
        dataType:"json",
        async:false,
        success: function(r){
            layer_alert("<?php echo e(trans('admin.success')); ?>")
            return
        }
    });
}
function layer_alert(msg)
    {
        layer.alert(msg, {title: "<?php echo e(trans('admin.message')); ?>", btn: false, offset: '50px', shadeClose: true});
    }
    
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.customer.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>