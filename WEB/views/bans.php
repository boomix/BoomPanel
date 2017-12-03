<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">

        <div class="span6">
            <?php include "model/alert-messages.php"; ?>
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?=UP(ADD).' '.(($b) ? BAN : MUTE.' '.GAG);?></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="POST" class="form-horizontal">

                        <div class="control-group">
                            <label class="control-label"><?=UP(PLAYER);?> :</label>
                            <div class="controls">
                                <input type="text" name="player" autocomplete="off" class="span11" placeholder="..." value="<?=isset($_POST['player']) ? htmlspecialchars($_POST['player']) : ((isset($_GET['banplayer'])) ? htmlspecialchars($_GET['banplayer']) : '') ;?>">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?=UP(REASON);?> :</label>
                            <div class="controls">
                                <input type="text" name="reason" autocomplete="off" class="span11" placeholder="..." value="<?=isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : '';?>">
                            </div>
                        </div>

                        <?php if(!$b) { ?>
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
                        <?php } ?>

                        <div class="control-group">
                            <label class="control-label"><?=UP(SERVER);?> </label>
                            <div class="controls">
                                <select name="server">
                                    <?php foreach ((array)$GetAllServers as $server) { ?>
                                    <option <?=(isset($_POST['server']) && $_POST['server'] == $server['name']) ? 'selected="selected"' : '';?>><?=$server['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?=UP(TIME);?></label>
                            <div class="controls">
                                <div class="input-prepend span4"> <span class="add-on"><?=DAYS[0];?></span>
                                    <input type="number" min="0" name="days" autocomplete="off" class="span5" value="<?=isset($_POST['days']) ? intval($_POST['days']) : 0;?>">
                                </div>
                                <div class="input-prepend span4"> <span class="add-on"><?=HOURS[0];?></span>
                                    <input type="number" min="0" name="hours" autocomplete="off" class="span5" value="<?=isset($_POST['hours']) ? intval($_POST['hours']) : 0;?>">
                                </div>
                                <div class="input-prepend span4"> <span class="add-on"><?=MINUTES[0];?></span>
                                    <input type="number" min="0" name="minutes" autocomplete="off" value="<?=isset($_POST['minutes']) ? intval($_POST['minutes']) : 0;?>" class="span5">
                                </div>
                                <br style="clear:both;">
                            </div>
                        </div>

                        <?php $editing = (isset($match['params']['action']) && $match['params']['action'] == 'edit') ? true : false; ?>
                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn btn-success"><?=($editing) ?  UP(EDIT) : UP(ADD);?></button>
                            <?php if($editing) { ?>
                                <a href="<?=$CurrentURL;?>unban/<?=intval($match['params']['id']);?>/" class="btn btn-warning pull-right"><?=(isset($ban) && $ban['unbanned'] == 0) ? UP(UNBAN) : UP(RESTORE).' '.BAN;?></a>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-search"></i> </span>
                    <h5><?=UP(SEARCH).' '.(($b) ? BAN : MUTE.' '.GAG);?></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="GET" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label"><?=UP(PLAYER);?> :</label>
                            <div class="controls">
                                <input name="player" type="text" class="span11" placeholder="..." value="<?=isset($_GET['player']) ? htmlspecialchars($_GET['player']) : '';?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?=UP(ADMIN);?> :</label>
                            <div class="controls">
                                <input type="text" name="admin" class="span11" placeholder="..." value="<?=isset($_GET['admin']) ? htmlspecialchars($_GET['admin']) : '';?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?=UP(REASON);?> :</label>
                            <div class="controls">
                                <input type="text" name="reason" class="span11" placeholder="..." value="<?=isset($_GET['reason']) ? htmlspecialchars($_GET['reason']) : '';?>">
                            </div>
                        </div>
                        <?php if(!$b) { ?>
                        <div class="controls">
                            <label><input type="checkbox" name="mgtype[]" <?=(isset($_GET['mgtype']) && in_array(0, $_GET['mgtype'])) ? 'checked' : ''; ?> value="0"/><?=UP(MUTE);?></label>
                            <label><input type="checkbox" name="mgtype[]" <?=(isset($_GET['mgtype']) && in_array(1, $_GET['mgtype'])) ? 'checked' : ''; ?> value="1"/><?=UP(GAG);?></label>
                            <label><input type="checkbox" name="mgtype[]" <?=(isset($_GET['mgtype']) && in_array(2, $_GET['mgtype'])) ? 'checked' : ''; ?> value="2"/><?=UP(SILENCE);?></label>
                        </div>
                        <?php } ?>
                        <div class="control-group">
                            <label class="control-label"><?=UP(SERVER);?> </label>
                            <div class="controls">
                                <select name="server">
                                    <option><?=ANYSERVER;?></option>
                                    <?php foreach ((array)$GetAllServers as $server) {
                                        if($server['name'] == 'all servers')
                                            $server['name'] = ALL.' '.NAV_SERVERS;
                                    ?>
                                        <option <?=(isset($_GET['server']) && $_GET['server'] == $server['id']) ? 'selected="selected" ' : '';?> value="<?=$server['id'];?>"><?=$server['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="search" class="btn btn-success"><?=UP(SEARCH);?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <div class="row-fluid">
        <div class="span12">

            <hr>

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-ban-circle"></i> </span>
                    <h5><?=UP(LATEST).' '.(($b) ? BANS : MUTE.' '.GAG);?></h5>
                </div>
                <div class="widget-content">

                    <?php $results = $limit = ($CurrentPage == 1) ? '1 - '.ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.' - '.$CurrentPage * ITEMSPERPAGE; ;?>
                    <p class="totalresults"><?=UP(TOTAL).' '.RESULTS;?>: <b><?=$CountAllBans['count'];?></b> | <?=SHOWINGRESULTS;?> <b><?=$results;?></b></p>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?=UP(PLAYER);?></th>
                            <th><?=UP(SERVER);?></th>
                            <?=(!$b) ? '<th>'.UP(TYPE).'</th>' : ''; ?>
                            <th>SteamID</th>
                            <th><?=(($b) ? UP(BAN) : '').' '.(($b) ? LENGTH : UP(LENGTH));?></th>
                            <th><?=UP(REASON);?></th>
                            <th><?=UP(ADMIN);?></th>
                            <th><?=UP(BANNED).' '.AT;?></th>
                            <th><?=UP(ENDS);?></th>
                            <th><?=UP(EDIT);?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                            //Load all player avatars with 1 request to steam
                            $Players = array();
                            foreach ((array)$GetAllBans as $ban)
                                if($ban['player_steamid'] != 0 && !in_array($ban['player_steamid'], $Players))
                                    array_push($Players, $ban['player_steamid']);
                            $avatars = GetPlayersAvatars($Players);

                        ?>


                        <?php foreach ((array)$GetAllBans as $ban) {

                            if(empty($ban['steamid']))
                                return;

                            $starttime  = date(TIMEFORMAT, strtotime($ban['time']));
                            $endtime    = date(TIMEFORMAT, strtotime($ban['time'] . "+".$ban['length']." minutes"));
                            $steamid    = $ban['player_steamid'];
                            $username   = (!empty($ban['player_username'])) ? htmlspecialchars($ban['player_username']) : htmlspecialchars($avatars['username-'.$steamid]);
                            if($ban['unbanned'] > 0) $ban['banstatus'] = UNBANNED;

                        ?>

                            <tr class="odd gradeX">
                                <td class="coutryrow"><img src="<?=WEBSITE;?>/img/flag/<?=$ban['country_name'];?>.png" title="<?=$ban['country_name'];?>" class="flag"></td>
                                <td><a target="_blank" href="http://steamcommunity.com/profiles/<?=$steamid;?>"><img src="<?=$avatars[$steamid]?>" class="player-avatar"> <?=$username;?></a></td>
                                <td class="centerrow"><?=$ban['name'];?></td>
                                <?php if(!$b) { ?><td class="centerrow"><?=($ban['mgtype'] == 0) ? MUTE : (($ban['mgtype'] == 1) ? GAG : SILENCE);?></td><?php } ?>
                                <td class="steamidrow"><?=toSteamID($steamid);?> <a data-clipboard-text="<?=toSteamID($steamid);?>" title="<?=COPY;?>" class="clipboard tip-right"><i class="icon-copy"></i></a></td>
                                <td class="centerrow"><?=convertToHoursMinsBans(intval($ban['length']));?></td>
                                <td><?=htmlspecialchars($ban['reason']);?></td>
                                <td class="centerrow"><a target="_blank" href="http://steamcommunity.com/profiles/<?=$ban['admin_steamid'];?>"><?=htmlspecialchars($ban['admin_username']);?></a></td>
                                <td class="centerrow"><?=$starttime;?></td>
                                <td class="centerrow"><?=$endtime;?>  <span class="label label-<?=($ban['banstatus'] == 'active' || ($ban['length'] == 0 && $ban['banstatus'] != UNBANNED)) ? 'success' : (($ban['banstatus'] == UNBANNED) ? 'warning' : 'important');?>"><?=($ban['length'] == 0 && $ban['banstatus'] != UNBANNED) ? PERMANENET : $ban['banstatus'];?></span></td>
                                <td class="morerow"><a href="<?=$CurrentURL;?>edit/<?=intval($ban['bid']);?>/" class="btn btn-info btn-mini"><span class="icon"> <i class="icooo-on-edit"></i> </span><?=EDIT;?></a></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>

                    <?php

                        /* PAGINATION:
                           Just set
                              * $CurrentPage = ?
                              * $MaxPages    = ?
                           and it should work fine, hope you will understand the code :D
                        */

                        //Allowed - true or false
                        $prev2 = ($CurrentPage - 2 >= 1) ? true : false;
                        $prev1 = ($CurrentPage - 1 >= 1) ? true : false;
                        $next1 = ($CurrentPage + 1 <= $MaxPages) ? true : false;
                        $next2 = ($CurrentPage + 2 <= $MaxPages) ? true : false;

                        //Numbers
                        $prev2Num = ($prev2) ? $CurrentPage - 2 : '-';
                        $prev1Num = ($prev1) ? $CurrentPage - 1 : '-';
                        $next1Num = ($next1) ? $CurrentPage + 1 : '-';
                        $next2Num = ($next2) ? $CurrentPage + 2 : '-';

                        //URLs
                        $prev2url = ($prev2) ? ($CurrentURL.'page/'.$prev2Num.'/?'.http_build_query($_GET)) : '#';
                        $prev1url = ($prev1) ? ($CurrentURL.'page/'.$prev1Num.'/?'.http_build_query($_GET)) : '#';
                        $next1url = ($next1) ? ($CurrentURL.'page/'.$next1Num.'/?'.http_build_query($_GET)) : '#';
                        $next2url = ($next2) ? ($CurrentURL.'page/'.$next2Num.'/?'.http_build_query($_GET)) : '#';

                        //LI class
                        $disabled = ' class="disabled"';
                        $prev2class = (!$prev2) ? $disabled : '';
                        $prev1class = (!$prev1) ? $disabled : '';
                        $next1class = (!$next1) ? $disabled : '';
                        $next2class = (!$next2) ? $disabled : '';


                    ?>


                    <div class="pagination">
                        <ul>

                            <li<?=$prev1class;?>><a class="paginationtext" href="<?=$prev1url;?>"><?=UP(PREVIUS);?></a></li>
                            <li<?=$prev2class;?>><a href="<?=$prev2url;?>"><?=$prev2Num;?></a></li>
                            <li<?=$prev1class;?>><a href="<?=$prev1url;?>"><?=$prev1Num;?></a></li>
                            <li class="active"><a href="#"><?=$CurrentPage;?></a></li>
                            <li<?=$next1class;?>><a href="<?=$next1url;?>"><?=$next1Num;?></a></li>
                            <li<?=$next2class;?>><a href="<?=$next2url;?>"><?=$next2Num;?></a></li>
                            <li<?=$next1class;?>><a class="paginationtext" href="<?=$next1url;?>"><?=UP(NEXT);?></a></li>

                        </ul>
                    </div>

                </div>
            </div>



        </div>
    </div>
</div>

