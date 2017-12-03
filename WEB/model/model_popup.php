<?php if(!isset($db)) die(); ?>
<div id="actionMenu" class="modal hide" aria-hidden="true" style="display: none;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><?=UP(SELECTACTION);?></h3>
    </div>
    <div class="modal-body">


        <div class="widget-content">
            <ul class="quick-actions">
                <div class="row-fluid">
                    <li class="bg bg_lo span4"> <a onclick="OpenSecondMenuOpen('<?=UP(BAN);?>')" href="#banMenu" data-toggle="modal" data-dismiss="modal"> <i class="icon-ban-circle"></i><?=UP(BAN).' '.PLAYER;?></a> </li>
                    <li class="bg bg_lb span4"> <a onclick="OpenSecondMenuOpen('<?=UP(KICK);?>')" href="#kickMenu" data-toggle="modal" data-dismiss="modal"> <i class="icon-share-alt"></i><?=UP(KICK).' '.PLAYER;?></a> </li>
                    <li class="bg bg_lg span4"> <a onclick="OpenSecondMenuOpen('<?=UP(MUTE)."/".GAG;?>')" href="#mutegagMenu" data-toggle="modal" data-dismiss="modal"> <i class="icon-volume-off"></i><?=UP(MUTE).'/'.GAG;?></a> </li>
                </div>
            </ul>
            <br style="clear:both">
        </div>


    </div>
</div>




<div id="banMenu" class="modal hide" aria-hidden="true" style="display: none;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><?=UP(BAN).' '.PLAYER;?></h3>
    </div>
    <div class="modal-body">

        <h5 id="alert-username" class="text-center"></h5>

        <hr style="margin-bottom: 5px">
        <form action="#" method="get" id="banPlayer" class="form-horizontal">

            <div class="control-group" hidden><input type="text" name="steamid" id="steamID-val"></div>
            <div class="control-group" hidden><input type="text" name="serverName" id="server-val"></div>

            <div class="control-group">
                <label class="control-label"><?=UP(REASON);?> :</label>
                <div class="controls">
                    <input type="text" name="reason" placeholder="...">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(DAYS);?> :</label>
                <div class="controls">
                    <input type="number" min="0" name="days" value="0">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(HOURS);?> :</label>
                <div class="controls">
                    <input type="number" min="0" name="hours" value="0">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(MINUTES);?> :</label>
                <div class="controls">
                    <input type="number" min="0" name="minutes" value="0">
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <a href="#actionMenu" data-toggle="modal" data-dismiss="modal" class="btn btn-inverse"><?=UP(CANCEL);?></a>
        <button type="submit" onclick="BanPlayer();" name="submit" class="btn btn-success"><?=UP(BAN).' '.PLAYER;?></button>
    </div>

</div>


<div id="kickMenu" class="modal hide" aria-hidden="true" style="display: none;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><?=UP(KICK).' '.PLAYER;?></h3>
    </div>
    <div class="modal-body">

        <h5 id="alert-username" class="text-center"></h5>

        <hr style="margin-bottom: 5px">
        <form action="#" method="get" id="kickPlayer" class="form-horizontal">
            <div class="control-group" hidden><input type="text" name="steamid" id="steamID-val"></div>
            <div class="control-group" hidden><input type="text" name="serverName" id="server-val"></div>
            <div class="control-group">
                <label class="control-label"><?=UP(REASON);?> :</label>
                <div class="controls">
                    <input type="text" name="reason" autocomplete="off" id="kick-reason" placeholder="...">
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <a href="#actionMenu" data-toggle="modal" data-dismiss="modal" class="btn btn-inverse"><?=UP(CANCEL);?></a>
        <button type="submit" onclick="KickPlayer()" data-dismiss="modal" name="submit" class="btn btn-success"><?=UP(KICK).' '.PLAYER;?></button>
    </div>

</div>





<div id="mutegagMenu" class="modal hide" aria-hidden="true" style="display: none;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><?=UP(MUTE).' '.GAG.' '.PLAYER;?></h3>
    </div>
    <div class="modal-body">

        <h5 id="alert-username" class="text-center"></h5>

        <hr style="margin-bottom: 5px">
        <form action="#" method="get" id="mutegagPlayer" class="form-horizontal">

            <div class="control-group" hidden><input type="text" name="steamid" id="steamID-val"></div>
            <div class="control-group" hidden><input type="text" name="serverName" id="server-val"></div>

            <div class="control-group">
                <label class="control-label"><?=UP(REASON);?> :</label>
                <div class="controls">
                    <input type="text" name="reason" placeholder="...">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(ACTION);?></label>
                <div class="controls">
                    <label>
                        <input type="radio" value="0" name="mgtype" <?=(!isset($_POST['mgtype'])) ? 'checked' : '';?>
                            <?=(isset($_POST['mgtype']) && $_POST['mgtype'] == 0) ? 'checked' : '';?>/>
                        <?=UP(MUTE);?></label>
                    <label>
                        <input type="radio" value="1" name="mgtype" <?=(isset($_POST['mgtype']) && $_POST['mgtype'] == 1) ? 'checked' : '';?>/>
                        <?=UP(GAG);?></label>
                    <label>
                        <input type="radio" value="2" name="mgtype" <?=(isset($_POST['mgtype']) && $_POST['mgtype'] == 2) ? 'checked' : '';?>/>
                        <?=UP(SILENCE);?></label>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(DAYS);?> :</label>
                <div class="controls">
                    <input type="number" min="0" name="days" value="0">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(HOURS);?> :</label>
                <div class="controls">
                    <input type="number" min="0" name="hours" value="0">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?=UP(MINUTES);?> :</label>
                <div class="controls">
                    <input type="number" min="0" name="minutes" value="0">
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <a href="#actionMenu" data-toggle="modal" data-dismiss="modal" class="btn btn-inverse"><?=UP(CANCEL);?></a>
        <button type="submit" onclick="MuteGagPlayer()" data-dismiss="modal" name="submit" class="btn btn-success"><?=UP(MUTE).'/'.GAG.' '.PLAYER;?></button>
    </div>

</div>