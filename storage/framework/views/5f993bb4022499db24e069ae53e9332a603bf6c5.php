<div id="mypaginate" style="float:right;"></div>
<script>
    layui.use(['laypage', 'layer'], function(){
        
        var laypage = layui.laypage
        ,layer = layui.layer
        ,page = $("input[name='page']").val();

        laypage.render({
            elem: 'mypaginate'
            ,count: "<?php echo e(($count<1)?1:$count); ?>" // 總筆數
            ,limit: "<?php echo e($search['per_page']); ?>" // per page
            ,curr: page // 第x頁
            ,prev: "<?php echo e(trans('pagination.previous')); ?>"
            ,next: "<?php echo e(trans('pagination.next')); ?>"
            ,theme: "#343A40"
            ,jump: function(obj, first){
                if(!first){
                    $("input[name='page']").val(obj.curr);
                    doSearch();
                }
            }
        });
    });
</script>