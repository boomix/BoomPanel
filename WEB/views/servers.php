<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <?php

    $error = false;

    $GetServerDate = $db->selectOne("SELECT `ip`, `port`, `rcon_pw` FROM bp_servers WHERE name = :serverName", array("serverName" => $serverName));

    try {
        $Query->Connect($GetServerDate['ip'], $GetServerDate['port'], 1, 1 );
        $Query->SetRconPassword($GetServerDate['rcon_pw']);
        $data = $Query->Rcon('sm_BPstatus');

        preg_match('~\{(?:[^{}]|(?R))*\}~', $data, $match);
        $json = (array)json_decode($match[0]);
        echo_dev($match[0]);
        $stats      = ($json) ? $json['stats'] : ' ';
        $players    = ($json) ? $json['players'] : ' ';

    }
    catch( Exception $e ) { $error = true;?>

        <div class="alert alert-error alert-block" style="font-size: 13px">
            <button class="close" data-dismiss="alert">×</button>
            <strong><?= _("Error");?>!</strong> <?=$e->getMessage( );?>
        </div>

    <?php } finally {

        $Query->Disconnect( );

        if(empty($json) && !isset($e)) { $error = true; ?>

            <div class="alert alert-error alert-block" style="font-size: 13px">
                <button class="close" data-dismiss="alert">×</button>
                <strong><?= _("Error");?>!</strong> <?= _("failed to decode JSON data from server");?> <br><b><?= _("Data");?>:</b> <?=(isset($data) && !empty($data)) ? $data : '-';?>
            </div>

    <?php
        }
    }

    $mapimage = (!$error && file_exists("img/maps/".$stats->map.".jpg")) ? WEBSITE."/"."img/maps/".$stats->map.".jpg" : DEFAULT_MAP;
    ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="map-image" style="background-image: url('<?=$mapimage;?>')">
                <div class="overlay">
                    <?=(!$error) ? $stats->map : '-';?>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span2">
            <div class="metric">
                <span class="icon"><i class="icon-user infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=(!$error) ? $stats->online : '-';?></span></span>
                    <span class="title"><?= _("Now online");?></span>
                </p>
            </div>
        </div>
        <div class="span2 col-half-offset">
            <div class="metric">
                <span class="icon"><i class="icon-calendar infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, $serverName, 1);?></span></span>
                    <span class="title"><?= _("Past day");?></span>
                </p>
            </div>
        </div>
        <div class="span2 col-half-offset">
            <div class="metric">
                <span class="icon"><i class="icon-calendar infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, $serverName, 30);?></span></span>
                    <span class="title"><?= _("This month");?></span>
                </p>
            </div>
        </div>
        <div class="span2 col-half-offset">
            <div class="metric">
                <span class="icon"><i class="icon-globe infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, $serverName, -1);?></span></span>
                    <span class="title"><?= _("All time");?></span>
                </p>
            </div>
        </div>
        <div class="span2 col-half-offset">
            <div class="metric">
                <span class="icon"><i class="icon-globe infoicon"></i></span>
                <p>
                    <span class="number"><span id="timeleft"> <?=(!$error) ? $stats->tl : '-';?></span></span>
                    <span class="title"><?= _('Time left');?></span>
                </p>
            </div>
        </div>
        <div class="span2 col-half-offset">
            <div class="metric">
                <span class="icon"><i class="icon-globe infoicon"></i></span>
                <p>
                    <span class="number"><span> <?=(!$error) ? '<span style="color:#d0b311" id="score1">' .$stats->s1. '</span>/<span style="color:#2461db" id="score2">' .$stats->s2.'</span>' : '-';?></span></span>
                    <span class="title"><?= _("Score");?></span>
                </p>
            </div>
        </div>
    </div>

    <hr>
        <div class="row-fluid">
            <div class="span12">

                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                        <h5><?= _("Send command to server");?> </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <div id="response"></div>
                        <div class="controls">
                            <form action="#" method="POST" id="SendServerCommand" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label"><?= _("Command");?> :</label>
                                    <div class="controls">
                                        <input type="text" name="commnad" id="ServerCmd" class="span11" placeholder="..." />
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" onclick="SendCommand();" name="submit" class="btn btn-success pull-right"><?= _("Send");?></button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <hr>

    <div class="row-fluid" style="margin-bottom: 15rem">
        <div class="span12">

            <button onclick="UpdateOnline()" class="btn btn-info pull-right" style="margin-bottom: 10px;"><?= _("Refresh");?></button>
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                    <h5><?= _("Online servers");?> </h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:27px">#</th>
                            <th style="min-width: 100px;"><?= _("Player");?></th>
                            <th>SteamID</th>
                            <th>IP</th>
                            <th style="min-width: 80px;"><?= _("Online");?></th>
                            <th style="min-width: 80px;"><?= _("Total time");?></th>
                            <th><?= _("Kills");?></th>
                            <th><?= _("Deaths");?></th>
                            <th><?= _("Action");?></th>
                        </tr>
                        </thead>
                        <tbody id="OnlinePlayers">


                        <?php /*if(!$error) { foreach ((array)$players as $player) { ?>
                            <tr class="empty" id="<?=$player->steamid;?>">
                                <?php $class = null; if($player->team == 2) $class = "T"; else if($player->team == 3) $class = "CT"; else $class = "SPEC"; ?>

                                <td class="coutryrow <?=$class;?>"><img src="http://84.245.195.110/boompanel/img/flag/Latvia.png" class="flag"></td>
                                <td>
                                    <a target="_blank" href="http://steamcommunity.com/profiles/<?=$player->steamid;?>">
                                        <img src="<?=GetPlayerAvatar($player->steamid);?>" class="player-avatar">
                                        <span id="username"><?=htmlspecialchars($player->name);?></span>
                                    </a>
                                </td>
                                <td class="steamidrow"><?=toSteamID($player->steamid);?> <a data-clipboard-text="<?=toSteamID($player->steamid);?>" title="Copy to clipboard" class="clipboard tip-bottom"><i class="icon-copy"></i></a></td>
                                <td class="centerrow"><?=convertToHoursMinsBans(intval($player->online));?></td>
                                <td class="centerrow"><?=convertToHoursMinsBans(GetTotalOnlineTime($db, $player->steamid, $serverName) + intval($player->online));?></td>
                                <td class="centerrow" style="width:50px"><?=intval($player->kills);?></td>
                                <td class="centerrow" style="width:50px"><?=intval($player->deaths);?></td>
                                <td class="morerow"><button onclick="OpenActionMenu('<?=$player->steamid;?>')" href="#actionMenu" data-toggle="modal" class="btn btn-info btn-mini"><span class="icon"> <i class="icon-edit"></i></span></button></td>
                            </tr>
                        <?php } } */ ?>

                        </tbody>
                    </table>
                </div>
            </div

        </div>
    </div>


</div>

<?php include "model/model_popup.php"; ?>

<script>
    var serverName = "<?=$serverName;?>";

    <?php if(!$error) { ?>
    //Update online page
    setTimeout(function(){
        UpdateOnline();
    }, 100);

    function SendCommand() {

        event.preventDefault();

        var data = $("#SendServerCommand").serialize() + '&serverName='+ serverName;
        $("#ServerCmd").val('');

        $.ajax({
            type: 'POST',
            url: '<?=WEBSITE;?>/ajax/SendCommand.php',
            data: data,
            error: function(xhr, status, error) {
                new Noty({type: 'error', progressBar: true, timeout: 3000, text: '<i class="icon-remove alerticon"></i>'+status }).show();
                UpdateOnline();
            },
            success: function(data){
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i>'+data }).show();
                UpdateOnline();
            },
            dataType: 'html'
        });

    }

    function checkImageExists(imageUrl, callBack) {
        var imageData = new Image();
        imageData.onload = function() {
            callBack(true);
        };
        imageData.onerror = function() {
            callBack(false);
        };
        imageData.src = imageUrl;
    }

    function UpdateOnline()
    {
        $('#OnlinePlayers').prepend('<tr><th style="width:18px"><img src="<?=WEBSITE;?>/img/loading.gif" class="loader"></th><tr>');
        $.ajax({
            type: 'POST',
            url: '<?=WEBSITE;?>/ajax/OnlineUpdate.php',
            data: 'serverName='+ serverName,
            error: function(xhr, status, error) {
                $('#error').html(xhr.responseText);
            },
            success: function(data){
                var split = data.split('||', 6);

                //$('#OnlinePlayers').html(data.replace(split[0]+'||'+split[1]+'||', ""));
                $('.overlay').html(split[0]);
                $('#timeleft').html(split[2]);
                $('#score1').html(split[3]);
                $('#score2').html(split[4]);
                $('#OnlinePlayers').html(split[5]);

                var mapurl = '<?=WEBSITE;?>/img/maps/'+split[0]+'.jpg';

                checkImageExists(mapurl, function(existsImage) {
                    if(existsImage == true)
                        $('.map-image').css('background-image', 'url('+mapurl+')');
                    else
                        $('.map-image').css('background-image', 'url(<?=DEFAULT_MAP;?>)');
                });


                $('#onlineUsers').html(split[1]);
            },
            dataType: 'html'
        });
    }

    var lastSteamID = null;
    function OpenActionMenu(steamid)
    {
        lastSteamID = steamid;
    }

    function OpenSecondMenuOpen(type)
    {
        if(lastSteamID != null) {
            var username = $('#' + lastSteamID).find('#username').text();
            $('[id="steamID-val"]').val(lastSteamID);
            $('[id="server-val"]').val(serverName);
            $('[id="alert-username"]').html(type + ': ' + username);
        }
    }

    function KickPlayer() {

        var data = $("#kickPlayer").serialize();

        $.ajax({
            type: 'POST',
            url: '<?=WEBSITE;?>/ajax/KickPlayer.php',
            data: data,
            error: function(xhr, status, error) {
                new Noty({type: 'error', progressBar: true, timeout: 3000, text: '<i class="icon-remove alerticon"></i><?= _("Player not found");?>' }).show();
                UpdateOnline();
            },
            success: function(data){
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i><?= _("Player kicked"ß);?>' }).show();
                UpdateOnline();
            },
            dataType: 'html'
        });

    }

    function BanPlayer() {

        var data = $("#banPlayer").serialize();

        $.ajax({
            type: 'POST',
            url: '<?=WEBSITE;?>/ajax/BanPlayer.php',
            data: data,
            error: function(xhr, status, error) {
                new Noty({type: 'error', progressBar: true, timeout: 3000, text: '<i class="icon-remove alerticon"></i>'+xhr.responseText }).show();
                UpdateOnline();
            },
            success: function(data){
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i><?= _("Player banned");?>' }).show();
                UpdateOnline();
            },
            dataType: 'html'
        });

    }

    function MuteGagPlayer() {

        var data = $("#mutegagPlayer").serialize();

        $.ajax({
            type: 'POST',
            url: '<?=WEBSITE;?>/ajax/MuteGagPlayer.php',
            data: data,
            error: function(xhr, status, error) {
                new Noty({type: 'error', progressBar: true, timeout: 3000, text: '<i class="icon-remove alerticon"></i>'+xhr.responseText }).show();
                UpdateOnline();
            },
            success: function(data){
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i><?= _("Success");?>' }).show();
                UpdateOnline();
            },
            dataType: 'html'
        });

    }

    setInterval(function(){
        var val = parseInt($('#timeleft').html());
        if(val != -1)
            $('#timeleft').html(val - 1);
    }, 1000);



    <?php } ?>

</script>
