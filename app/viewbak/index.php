<?php
include \think\facade\App::getBasePath() .'/view/common/header.php';
?>
    <include file="common:header" />
<div class="rental">
    <p><span>共享汽车</span><br>车型随您选&nbsp;&nbsp;您用得舒心&nbsp;&nbsp;我们才开心</p>
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
                <div><p class=\"cartitle\"><span></span>&nbsp;&nbsp;&nbsp;&nbsp;$title</p><p class=\"carprice\">¥ <em>$price</em> / 天</p></div>
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

<div class="center" style="overflow: hidden;">
    <div class="partner">
        <h3 class="partner-title">合作伙伴</h3>
        <ul>

            <li><a href="/page/22" title="BENZ"><img src="/static/img/20170214180145064506.jpg" alt="BENZ" width="160" height="80"></a></li>

            <li><a href="/page/22" title="TOYOTA"><img src="/static/img/20170214175882248224.jpg" alt="TOYOTA" width="160" height="80"></a></li>

            <li><a href="/page/22" title="一汽大众"><img src="/static/img/20170214175794149414.jpg" alt="一汽大众" width="160" height="80"></a></li>

            <li><a href="/page/22" title="长安福特"><img src="/static/img/20170214175745594559.jpg" alt="长安福特" width="160" height="80"></a></li>

            <li><a href="/page/22" title="PORSCHE"><img src="/static/img/20170214175661806180.jpg" alt="PORSCHE" width="160" height="80"></a></li>

            <li><a href="/page/22" title="BENTLEY"><img src="/static/img/20170214175686738673.jpg" alt="BENTLEY" width="160" height="80"></a></li>

            <li><a href="/page/22" title="MASERATI"><img src="/static/img/20170214175564956495.jpg" alt="MASERATI" width="160" height="80"></a></li>

            <li><a href="/page/22" title="奥迪"><img src="/static/img/20170214175516081608.jpg" alt="奥迪" width="160" height="80"></a></li>

        </ul>
        <p class="partner-more"><a href="/page/22">查看更多</a></p>
    </div>
    <div class="online">
        <h3 class="partner-title">在线留言</h3>
        <form id="msg" method="post" onsubmit="return false">
            <input type="text" name="nickname" id="" value="" placeholder="姓名：">
            <input type="text" name="contact" id="" value="" placeholder="电话：">
            <textarea name="content" rows="" cols="">留言：</textarea>
            <input type="submit" id="msgSubmit" value="提交">
        </form>
    </div>
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
                <p class="name"><span>新闻资讯</span><br />news</p>
                <p class="line"></p>
                <p class="more"><a href="/news/1">&gt;</a></p>
            </div>
            <p class="newssubtitle"><a href="/news/19" class="hover">企业动态</a><a href="/news/18">行业资讯</a></p>
            <div class="newsbox">
                <ul>
                    <?php
                    foreach ($data['enterpriseDynamics'] as $k => $v)
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
            <div class="newsbox" style="display: none;">
                <ul>
                    <?php
                    foreach ($data['industryNews'] as $k => $v)
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
        <div class="newsright">
            <div class="newstitle">
                <p class="name"><span>常见问题</span><br />faq</p>
                <p class="line"></p>
                <p class="more"><a href="/news/25">&gt;</a></p>
            </div>
            <div class="faq">
                <ul>

                    <?php
                    foreach ($data['answer'] as $k => $v)
                    {
                        $id = $v['id'];
                        $title = $v['title'];
                        $content = $v['content'];
                        $image = $v['image'];
                        $month = date('m-d', strtotime($v['created_at']));
                        $year = date('Y', strtotime($v['created_at']));
                        echo " <li>
                        <div class=\"faqtitle\"><i>问</i><a href=\"/news/view/$id\">$title</a></div>
                        <div class=\"faqtext\"><i>答</i>$content</div>
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