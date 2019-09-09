<?php
include \think\facade\App::getBasePath() .'/view/common/header.php';
?>
    <include file="common:header" />
<div class="rental">
    <p><span>旭升壁炉</span><br>您用得舒心&nbsp;&nbsp;我们才开心</p>
</div>

<div class="carlist center">
    <ul>
        <?php
        foreach ($data['product'] as $k => $v)
        {
            $id = $v['id'];
            $title = $v['title'];
            $image = $v['image'];
            $price = $v['price'];
            echo "<li>
            <a href=\"/product/view/$id\" title=\"$title\">
                <h3>$title</h3>
                <p class=\"carpic\"><img src=\"$image\" alt=\"$title\"></p>
                <div><p class=\"cartitle\"><span></span>&nbsp;&nbsp;&nbsp;&nbsp;$title</p><p class=\"carprice\">¥ <em>$price</em> </p></div>
                <h4>+</h4>
            </a>
        </li>";
        }
        ?>



    </ul>
</div>

<div class="rental">
    <p><span>五大优势</span><br>给您满意的选择</p>
</div>
<div class="youshi center">
    <ul>
        <li class="no1"><a href="#"><p><span class="icon iconfont icon-diqiu"></span></p><h3>网点多</h3><h4>01</h4></a></li>
        <li class="no2"><a href="#"><p><span class="icon iconfont icon-moshi"></span></p><h3>流程简单</h3><h4>02</h4></a></li>
        <li class="no3"><a href="#"><p> <span class="icon iconfont icon-ziyuan "></span></p><h3>口碑良好</h3><h4>03</h4></a></li>
        <li class="no4"><a href="#"><p><span class="icon iconfont icon-fuwu"></span></p><h3>资费透明</h3><h4>04</h4></a></li>
        <li class="no5"><a href="#"><p><span class="icon iconfont icon-xuanchuantuiguang"></span></p><h3>全方位技术支持</h3><h4>05</h4></a></li>
    </ul>
</div>


<div class="screenabout">
    <div class="about center">
        <div class="aboutpic">
            <a href="/page/1" >
                <img alt="" src="<?php echo $data['about']['image']?>">
            </a>
        </div>
        <div class="aboutbox">
            <h3><?php echo $setting['company_name']?></h3>
            <div class="abouttext"><?php echo $data['about']['content']?></div>
            <p class="aboutmore"><a href="/page/1">查看更多</a></p>
        </div>
    </div>

</div>

    <div class="news center">
        <div class="newsleft">
            <div class="newstitle">
                <p class="name"><span>新闻资讯</span></p>
                <p class="line"></p>
            </div>
            <div class="newsbox">
                <ul>
                    <?php
                    foreach ($data['news'] as $k => $v)
                    {
                        $id = $v['id'];
                        $title = $v['title'];
                        $image = $v['image'];
                        $content = $v['content'];
                        $month = date('m-d', strtotime($v['created_at']));
                        $year = date('Y', strtotime($v['created_at']));
                        echo '<li>';
                        if ($k == 0)
                        {
                            echo "<div class=\"newspic\"><a href=\"/news/view/$id\" ><img src=\"$image\" width=\"300\" height=\"225\" /></a></div>";
                        }
                        echo "
                        <a href=\"/news/view/$id\">
                            <dl>
                                <dt><span>$month</span><br /><em>$year</em></dt>
                                <dd>
                                    <p class=\"newsbiaoti\">$title</p>
                                    <div class=\"newstext\">$content</div>
                                </dd>
                            </dl>
                        </a>
                    </li>";
                    }
                    ?>

                </ul>
            </div>

        </div>
    </div>


<?php
include \think\facade\App::getBasePath() .'/view/common/footer.php';
?>