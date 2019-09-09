<?php
include \think\facade\App::getBasePath() .'/view/common/header.php';
?>
<div class="nymain">
    <?php
    include \think\facade\App::getBasePath() .'/view/common/left.php';
    ?>
    <div class="right">
        <div class="righttitle">
<!--            <div class="site"><a href="/">首页</a>&nbsp;&nbsp;»&nbsp;&nbsp;<a href="?Info1444.html" title="共享汽车">共享汽车</a>&nbsp;&nbsp;»&nbsp;&nbsp;<a href="/?Product1445/Index.html" title="轿车">轿车</a>&nbsp;&nbsp;»&nbsp;&nbsp;<a href="/?Product1482/Index.html" title="奥迪系列">奥迪系列</a></div>-->
            <h3><?php echo $data['category_name']; ?></h3>
        </div>
        <div class="nycenter">
            <h1><?php echo $data['title']; ?></h1>
            <div class="fbtime">发布时间：<?php echo $data['created_at']; ?>　点击量：<span id="Click"><?php echo $data['click_count']; ?></span></div>
            <div class="content"><?php echo $data['content']; ?></div>

            <div class="clear"></div>
<!--            <div class="page">上一条：<a href="#">无上一篇</a> | 下一条：<a href="#">无下一篇</a></div>-->
        </div>
    </div>
</div>
<?php
include \think\facade\App::getBasePath() .'/view/common/footer.php';
?>