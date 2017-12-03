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
            <strong><?=UP(ERROR);?>!</strong> <?=$e->getMessage( );?>
        </div>

    <?php } finally {

        $Query->Disconnect( );

        if(empty($json) && !isset($e)) { $error = true; ?>

            <div class="alert alert-error alert-block" style="font-size: 13px">
                <button class="close" data-dismiss="alert">×</button>
                <strong><?=UP(ERROR);?>!</strong> <?=SERVER_ERROR;?> <br><b><?=UP(DATA);?>:</b> <?=(isset($data) && !empty($data)) ? $data : '-';?>
            </div>

    <?php
        }
    }
    ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="map-image" style="background-image: url('http://i.imgur.com/O0wBACS.jpg')">
                <div class="overlay">
                    <?=(!$error) ? $stats->map : '-';?>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span3">
            <div class="metric">
                <span class="icon"><i class="icon-user infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=(!$error) ? $stats->online : '-';?></span></span>
                    <span class="title"><?=NOW.' '.ONLINE;?></span>
                </p>
            </div>
        </div>
        <div class="span3">
            <div class="metric">
                <span class="icon"><i class="icon-calendar infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, $serverName, 1);?></span></span>
                    <span class="title"><?=PASTDAY.' '.ONLINE;?></span>
                </p>
            </div>
        </div>
        <div class="span3">
            <div class="metric">
                <span class="icon"><i class="icon-calendar infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, $serverName, 30);?></span></span>
                    <span class="title"><?=THISMONTH.' '.ONLINE;?></span>
                </p>
            </div>
        </div>
        <div class="span3">
            <div class="metric">
                <span class="icon"><i class="icon-globe infoicon"></i></span>
                <p>
                    <span class="number"><span id="onlineUsers"><?=CountOnlinePast($db, $serverName, -1);?></span></span>
                    <span class="title"><?=ALL.' '.TIME.' '.ONLINE;?></span>
                </p>
            </div>
        </div>
    </div>

    <hr>

    <div class="row-fluid">
        <div class="span12">

            <button onclick="UpdateOnline()" class="btn btn-info pull-right" style="margin-bottom: 10px;"><?=UP(REFRESH);?></button>
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                    <h5><?=UP(ONLINE).' '.PLAYERS;?> </h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:27px">#</th>
                            <th style="min-width: 100px;"><?=UP(PLAYER);?></th>
                            <th>SteamID</th>
                            <th>IP</th>
                            <th style="min-width: 80px;"><?=UP(ONLINE);?></th>
                            <th style="min-width: 80px;"><?=UP(TOTAL).' '.ONLINE;?></th>
                            <th><?=UP(KILLS);?></th>
                            <th><?=UP(DEATHS);?></th>
                            <th><?=UP(ACTION);?></th>
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
                var split = data.split('||', 3);
                $('#OnlinePlayers').html(data.replace(split[0]+'||'+split[1]+'||', ""));
                $('.overlay').html(split[0]);
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
                new Noty({type: 'error', progressBar: true, timeout: 3000, text: '<i class="icon-remove alerticon"></i><?=UP(PLAYER)." ".NOTFOUND;?>' }).show();
                UpdateOnline();
            },
            success: function(data){
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i><?=UP(PLAYER)." ".KICKED;?>' }).show();
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
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i><?=UP(PLAYER)." ".BANNED;?>' }).show();
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
                new Noty({ type: 'success', progressBar: true, timeout: 3000, text: '<i class="icon-ok alerticon"></i><?=UP(SUCCESS);?>' }).show();
                UpdateOnline();
            },
            dataType: 'html'
        });

    }



    <?php } ?>

</script>
