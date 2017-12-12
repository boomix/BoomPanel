<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
                    <h5>About BoomPanel</h5>
                </div>
                <div class="widget-content"><?= ("This is a BETA version of BoomPanel, please report all bugs in our community "); ?> <a style="font-weight: bold;color:#8c9eff;text-decoration: underline" href="https://discord.gg/vHj964a">Discord</a><br><?=_("The home page is not finished, so for now use other pages!");?></div>
            </div>

            <div class="row-fluid">
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-user infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlineNow($db);?></span></span>
                            <span class="title"><?= _("Now Online");?></span>
                        </p>
                    </div>
                </div>
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-calendar infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, 1);?></span></span>
                            <span class="title"><?= _("Past day online");?></span>
                        </p>
                    </div>
                </div>
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-calendar infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, 30);?></span></span>
                            <span class="title"><?= _("This month online");?></span>
                        </p>
                    </div>
                </div>
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-globe infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, -1);?></span></span>
                            <span class="title"><?= _("All time online");?></span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


