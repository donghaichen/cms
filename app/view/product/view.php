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
            <div class="detail clearfix">
                <div class="album exzoom" id="exzoom">
                    <div class="exzoom_img_box">
                        <ul class='exzoom_img_ul'>
                            <?php

                            foreach (json_decode($data['album'], true) as $k => $v)
                            {
                                $src = $v['src'];
                                echo "<li><img src=\"$src\" /></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="exzoom_nav"></div>
                    <p class="exzoom_btn"> <a href="javascript:void(0);" class="exzoom_prev_btn"> &lt; </a> <a href="javascript:void(0);" class="exzoom_next_btn"> &gt; </a> </p>
                </div>
                <div class="meta">
                    <h1><?php echo $data['title']; ?></h1>
                    <div class="panel promo-price"><dt class="label">促销价</dt><dt class="yen">¥</dt><dt class="price"><?php echo $data['price']; ?><dt></div>
                    <div class="desc">
                        <?php
                        foreach (json_decode($data['attribute'], true) as $k => $v)
                        {
                            $name = $v['name'];
                            $value = empty($v['value']) ? '无详细数据' : $v['value'];
                            echo "<div class=\"panel\"><dt class=\"label\">$name</dt><dt>$value</dt></div>";
                        }
                        ?>
                        <div class="panel"><dt class="label">点击量</dt><dt><?php echo $data['click_count']; ?></dt></div>
                    </div>

                    <div class="panel">
                        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $setting['qq']?>&site=qq&menu=yes" target="_blank" class="btn">立即咨询</a>
                        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $setting['qq']?>&site=qq&menu=yes" target="_blank" class="btn buy">立即购买</a>
                    </div>

                </div>
            </div>

            <div class="tab">产品详情
            </div>
            <div class="content">
                <?php if (!is_null($data['video'])){
                    $video = $data['video'];
                    echo "<video width=\"100%\" height=\"auto\" controls=\"\">
                <source src=\"$video\" type=\"video/mp4\">
            </video>";
                }
                ?>
                <?php echo $data['content']; ?>
            </div>


            <div class="clear"></div>
<!--            <div class="page">上一条：<a href="#">无上一篇</a> | 下一条：<a href="#">无下一篇</a></div>-->
        </div>
    </div>
</div>
<?php
include \think\facade\App::getBasePath() .'/view/common/footer.php';
?>
<script>

  </scrpit>
