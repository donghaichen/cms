<?php //var_dump($data['id']);?>
<div class="left">
    <div class="prolist">
        <h3><?php echo $data['categoryName']?></h3>
        <ul>
            <?php
            $module = $data['module'];
            foreach ($data['categoryList'] as $k => $v)
            {
                $id = $v['id'];
                $name = $v['name'];
                echo "<li><a href=\"/$module/$id\" class=\"hover\"><em></em>$name</a></li>";
            }
            ?>

        </ul>
    </div>
    <div class="contact">
        <div class="contacttitle"><div class="more"></div><h3>联系我们</h3></div>
        <div class="contactmain"><p style="font-size:13px;font-weight:bold;color:#2f4864;">
                <strong><?php echo $setting['company_name']?>&nbsp;</strong>
            </p>
            <p>
                客服电话：<?php echo $setting['contact_tel']?>
            </p>

            <p>
                <?php echo $setting['company_address']?>
            </p>
            <p>
                微信关注我们了解更多
            </p>
            <p>
                <img style="height:177px;width:190px;" alt="" src="<?php echo $setting['qrcode']?>" width="257" height="258">
            </p>
        </div>
    </div>
</div>