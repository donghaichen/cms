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
                <h3><?php echo $data['title']; ?></h3>
            </div>
            <div class="nycenter">
                <div class="content"><?php echo $data['content']; ?></div>

                <div class="clear"></div>
                <!--            <div class="page">上一条：<a href="#">无上一篇</a> | 下一条：<a href="#">无下一篇</a></div>-->
            </div>
        </div>
    </div>
<?php
include \think\facade\App::getBasePath() .'/view/common/footer.php';
?>