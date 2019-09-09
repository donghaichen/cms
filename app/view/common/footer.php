<div class="footer">
    <div class="center">
        <div class="foottext">
            <p>
                公司名称：<?php echo $setting['company_name']?>&nbsp;
            </p>
            <p>
                公司地址：<?php echo $setting['company_address']?>&nbsp;
            </p>
            <p>
                联系电话：<?php echo $setting['contact_tel']?>&nbsp;
            </p>

        </div>
        <div class="footclick">
            <p>Copyright © <?php echo date('Y')?> All rights reserved <?php echo $setting['company_name']?></p>
            <p>备案号：<a href="http://www.miitbeian.gov.cn/" target="_blank"><?php echo $setting['icp']?></a></p>
        </div>
        <div class="footpic">
            <p class="ewmname">微信关注我们了解更多</p>
            <p>
                &nbsp;<img style="height:177px;width:190px;" alt="" src="<?php echo $setting['qrcode']?>" width="257" height="258">
            </p>
        </div>
        <div class="link">友情链接：
        <?php
        foreach ($ads as $k => $v)
        {
            if ($v['type'] == 'link')
            {
                $link = $v['href'];
                $title = $v['title'];
                echo "<a href=\"$link\" target=\"_blank\">$title</a>";
            }
        }
        ?>
        </div>

    </div>
    <script src="/static/js/layer/layer.js"></script>
    <script src="/static/js/app.js"></script>
    <?php echo $setting['code'] ?>
</div>
</body>
</html>
