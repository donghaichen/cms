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
                <h3><?php echo $data['categoryName']; ?></h3>
            </div>
            <div class="nycenter">
                <ul class="ArticleCategory">
                    <?php
                    foreach ($data["list"]['data'] as $k =>$v)
                    {
                        $id = $v['id'];
                        $title = $v['title'];
                        $created_at = $v['created_at'];
                        $recommend = $v['is_recommend'] == 1 ? '<sup class="ivu-badge-count">荐</sup>' : '';
                        echo "<li>$recommend<a href=\"/news/view/$id\"><em>$created_at</em>•&nbsp;&nbsp;$title</a></li>";
                    }

                    ?>
                    <div class="clear"></div>
                </ul>
                <div class="page">
                    <?php
                    echo $data['page']
                    ?>
                    <div class="clear"></div>
                </div>

            </div>
        </div>
    </div>
<?php
include \think\facade\App::getBasePath() .'/view/common/footer.php';
?>