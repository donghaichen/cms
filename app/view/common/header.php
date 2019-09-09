<?php
//var_dump($data);
//var_dump($config);
//var_dump($setting);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="data-spm" content="a2e15" />
    <title><?php echo $setting['seo_title']?></title>
    <meta name="keywords" content="<?php echo $setting['keywords']?>">
    <meta name="description" content="<?php echo $setting['description']?>">
    <meta content="IE=7" http-equiv="X-UA-Compatible">
    <base href="/">
    <link href="//at.alicdn.com/t/font_1348747_o00azrkwje.css" rel="stylesheet" type="text/css">
    <link href="/static/css/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
<body>

<div class="head">
    <div class="center">
        <div class="fl">
            <a href="/" class="logo"><img alt="<?php echo $setting['title']?>" src="<?php echo $setting['logo']?>"></a>
        </div>
        <div class="fr">
            <div class="tel">
                <span>服务热线：<b><?php echo $setting['contact_tel']?></b></span>
                <em></em>
            </div>
            <div class="mid">
                <span class="icon iconfont icon-weixin-copy"></span>
                <span class="icon iconfont icon-weibo"></span>
            </div>
        </div>
    </div>
    <!--<div class="hpic icon"></div>-->
</div>
<div class="menu">
    <div class="menuname"><a href="/" style="color: #fff3b2;text-decoration: none;"><?php echo $setting['company_name']?></a></div>
    <div class="center">
        <ul>
            <li><a href="/" class="hover">网站首页</a></li>
            <li><a href="/page/1" class="">关于我们</a>
                <ul style="">
                    <?php
                    foreach ($nav[0] as $k => $v)
                    {
                        $id = $v['id'];
                        $title = $v['title'];
                        echo " <li><a href=\"/page/$id\">$title</a></li>";
                    }
                    ?>
                </ul>
            </li>
            <li>
                <a href="/news/1" class="">新闻中心</a>
                <ul style="">
                    <?php
                    foreach ($nav[1] as $k => $v)
                    {
                        $id = $v['id'];
                        $title = $v['name'];
                        echo " <li><a href=\"/news/$id\">$title</a></li>";
                    }
                    ?>
                </ul>
            </li>

            <li>
                <a href="/product/2" class="">产品中心</a>
                <ul style="">
                    <?php
                    foreach ($nav[2] as $k => $v)
                    {
                        $id = $v['id'];
                        $title = $v['name'];
                        echo " <li><a href=\"/product/$id\">$title</a></li>";
                    }
                    ?>
                </ul>
            </li>

            <?php
            foreach ($nav[4] as $k => $v)
            {
                $id = $v['id'];
                $title = $v['title'];
                echo " <li><a href=\"/page/$id\">$title</a></li>";
            }
            ?>


        </ul>
        <div class="menulihover" style="left: 26px; display: block;"></div>
    </div>
</div>



<div class="banner">
    <div class="b-img">
        <?php
        foreach ($ads as $k => $v)
        {
            if ($v['type'] == 'banner')
            {
                $src = $v['src'];
                $href = $v['href'];
                echo "<a href=\"$href\"><img src='$src'> </a>";
            }
        }
        ?>
    </div>
    <div class="b-list"></div>
    <a class="bar-left"><em></em></a><a class="bar-right"><em></em></a> </div>