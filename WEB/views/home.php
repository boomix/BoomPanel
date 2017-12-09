<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
                    <h5>About BoomPanel</h5>
                </div>
                <div class="widget-content"> This is ALPHA version of boompanel, please report all bugs in <a style="font-weight: bold;color:#8c9eff;text-decoration: underline" href="https://discord.gg/vHj964a">discord</a><br>The home
                page is not finished, so for now use other pages!</div>
            </div>

            <div class="row-fluid">
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-user infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlineNow($db);?></span></span>
                            <span class="title"><?=NOW.' '.ONLINE;?></span>
                        </p>
                    </div>
                </div>
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-calendar infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, 1);?></span></span>
                            <span class="title"><?=PASTDAY.' '.ONLINE;?></span>
                        </p>
                    </div>
                </div>
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-calendar infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, 30);?></span></span>
                            <span class="title"><?=THISMONTH.' '.ONLINE;?></span>
                        </p>
                    </div>
                </div>
                <div class="span3">
                    <div class="metric">
                        <span class="icon"><i class="icon-globe infoicon"></i></span>
                        <p>
                            <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, -1);?></span></span>
                            <span class="title"><?=ALL.' '.TIME.' '.ONLINE;?></span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


