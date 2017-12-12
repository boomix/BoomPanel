<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                    <h5>Error 404</h5>
                </div>
                <div class="widget-content">
                    <div class="error_ex">
                        <h1>404</h1>
                        <h3><?= _("Opps, You're lost."); ?></h3>
                        <p><?= _("We could not find the page you're looking for."); ?></p>
                        <a class="btn btn-warning btn-big"  href="<?=WEBSITE;?>"><?= _("Back to Home") ?></a> </div>
                </div>
            </div>
        </div>
    </div>
</div>
