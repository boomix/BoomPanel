<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">

        <div class="span6">
            <?php include "model/alert-messages.php"; ?>
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?=_("Add").' '.(($b) ? _("ban") : _("mute gag"));?></h5>
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
                            <label class="control-label"><?= _("Reason");?> :</label>
                            <div class="controls">
                                <input type="text" name="reason" autocomplete="off" class="span11" placeholder="..." value="<?=isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : '';?>">
                            </div>
                        </div>

                        <?php if(!$b) { ?>
                        <div class="control-group">
                            <label class="control-label"><?= _("Action");?></label>
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
                            <label class="control-label"><?= _("Server");?> </label>
                            <div class="controls">
                                <select name="server">
                                    <?php foreach ((array)$GetAllServers as $server) { ?>
                                    <option <?=(isset($_POST['server']) && $_POST['server'] == $server['name']) ? 'selected="selected"' : '';?>><?=$server['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?= _("Time");?></label>
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
                            <button type="submit" name="submit" class="btn btn-success"><?=($editing) ?  _("Edit") : _("Add");?></button>
                            <?php if($editing) { ?>
                                <a href="<?=$CurrentURL;?>delete/<?=intval($match['params']['id']);?>/" onclick="return confirm('Are you sure you want to delete it?')" class="btn btn-danger pull-right" style="margin-left: 0.5rem"><?=_("Delete");?></a>
                                <a href="<?=$CurrentURL;?>unban/<?=intval($match['params']['id']);?>/" class="btn btn-warning pull-right"><?=(isset($ban) && $ban['unbanned'] == 0) ? _("Unban") : _("Restore ban");?></a>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-search"></i> </span>
                    <h5><?=_("Search").' '.(($b) ? _("ban") : _("mute gag"));?></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="GET" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label"><?= _("Player");?> :</label>
                            <div class="controls">
                                <input name="player" type="text" class="span11" placeholder="..." value="<?=isset($_GET['player']) ? htmlspecialchars($_GET['player']) : '';?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?= _("Admin");?> :</label>
                            <div class="controls">
                                <input type="text" name="admin" class="span11" placeholder="..." value="<?=isset($_GET['admin']) ? htmlspecialchars($_GET['admin']) : '';?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?= _("Reason");?> :</label>
                            <div class="controls">
                                <input type="text" name="reason" class="span11" placeholder="..." value="<?=isset($_GET['reason']) ? htmlspecialchars($_GET['reason']) : '';?>">
                            </div>
                        </div>
                        <?php if(!$b) { ?>
                        <div class="controls">
                            <label><input type="checkbox" name="mgtype[]" <?=(isset($_GET['mgtype']) && in_array(0, $_GET['mgtype'])) ? 'checked' : ''; ?> value="0"/><?= _("Mute");?></label>
                            <label><input type="checkbox" name="mgtype[]" <?=(isset($_GET['mgtype']) && in_array(1, $_GET['mgtype'])) ? 'checked' : ''; ?> value="1"/><?= _("Gag");?></label>
                            <label><input type="checkbox" name="mgtype[]" <?=(isset($_GET['mgtype']) && in_array(2, $_GET['mgtype'])) ? 'checked' : ''; ?> value="2"/><?= _("Silence");?></label>
                        </div>
                        <?php } ?>
                        <div class="control-group">
                            <label class="control-label"><?= _("Server");?> </label>
                            <div class="controls">
                                <select name="server">
                                    <option><?= _("Any Server");?></option>
                                    <?php foreach ((array)$GetAllServers as $server) {
                                        if($server['name'] == 'all servers')
                                            $server['name'] = _("All servers");
                                    ?>
                                        <option <?=(isset($_GET['server']) && $_GET['server'] == $server['id']) ? 'selected="selected" ' : '';?> value="<?=$server['id'];?>"><?=$server['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="search" class="btn btn-success"><?= _("Search");?></button>
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
                    <h5><?= _("Latest").' '.(($b) ? _("bans") : _("mute gag"));?></h5>
                </div>
                <div class="widget-content">

                    <div class="pagination">
                        <ul>

                            <li<?=$prev1class;?>><a class="paginationtext" href="<?=$prev1url;?>"><?=_("Prev");?></a></li>

                            <?php if($CurrentPage > 3) { ?>
                                <li<?=$prev2class;?>><a href="<?=$firstpageurl;?>">1</a></li>
                            <?php } ?>

                            <li<?=$prev2class;?>><a href="<?=$prev2url;?>"><?=$prev2Num;?></a></li>
                            <li<?=$prev1class;?>><a href="<?=$prev1url;?>"><?=$prev1Num;?></a></li>
                            <li class="active"><a href="#"><?=$CurrentPage;?></a></li>
                            <li<?=$next1class;?>><a href="<?=$next1url;?>"><?=$next1Num;?></a></li>
                            <li<?=$next2class;?>><a href="<?=$next2url;?>"><?=$next2Num;?></a></li>

                            <?php if($MaxPages > 3 && $CurrentPage + 3 <= $MaxPages) { ?>
                            <li<?=$next2class;?>><a href="<?=$maxpageurl;?>"><?=$MaxPages;?></a></li>
                            <?php } ?>

                            <li<?=$next1class;?>><a class="paginationtext" href="<?=$next1url;?>"><?=_("Next");?></a></li>

                        </ul>
                    </div>

                    <?php $results = $limit = ($CurrentPage == 1) ? '1 - '.ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.' - '.$CurrentPage * ITEMSPERPAGE; ;?>
                    <p class="totalresults"><?= _("Total results:");?> <b><?=$CountAllBans['count'];?></b> | <?= _("Showing results from");?> <b><?=$results;?></b></p>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= _("Player");?></th>
                            <th><?= _("Server");?></th>
                            <?=(!$b) ? '<th>'._("Type").'</th>' : ''; ?>
                            <th>SteamID</th>
                            <th><?=(($b) ? _("Ban") : '').' '.(($b) ? _("length") : _("Length"));?></th>
                            <th><?= _("Reason");?></th>
                            <th><?= _("Admin");?></th>
                            <th><?= _("Banned at");?></th>
                            <th><?= _("Ends");?></th>
                            <th><?= _("Edit");?></th>
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

                            if(empty($ban['player_steamid']))
                                return;

                            $starttime  = date(TIMEFORMAT, strtotime($ban['time']));
                            $endtime    = date(TIMEFORMAT, strtotime($ban['time'] . "+".$ban['length']." minutes"));
                            $steamid    = $ban['player_steamid'];
                            $username   = (!empty($ban['player_username'])) ? htmlspecialchars($ban['player_username']) : htmlspecialchars($avatars['username-'.$steamid]);
                            if($ban['unbanned'] > 0) $ban['banstatus'] = _('unbanned');

                            $trstyle = (isset($match['params']['action']) && $match['params']['action'] == 'edit' && intval($match['params']['id']) == $ban['bid']) ? 'style="outline:solid 1px #6d6d6d !important"' : '';

                        ?>

                            <tr class="odd gradeX" <?=$trstyle;?>>
                                <td class="coutryrow"><img src="<?=WEBSITE;?>/img/flag/<?=$ban['country_name'];?>.png" title="<?=$ban['country_name'];?>" class="flag"></td>
                                <td><a target="_blank" href="http://steamcommunity.com/profiles/<?=$steamid;?>"><img src="<?=$avatars[$steamid]?>" class="player-avatar lazy"> <?=$username;?></a></td>
                                <td class="centerrow"><?=$ban['name'];?></td>
                                <?php if(!$b) { ?><td class="centerrow"><?=($ban['mgtype'] == 0) ? _('mute') : (($ban['mgtype'] == 1) ? _('gag') : _('silence'));?></td><?php } ?>
                                <td class="steamidrow"><?=toSteamID($steamid);?> <a data-clipboard-text="<?=toSteamID($steamid);?>" title="<?=COPY;?>" class="clipboard tip-right"><i class="icon-copy"></i></a></td>
                                <td class="centerrow"><?=convertToHoursMinsBans(intval($ban['length']));?></td>
                                <td><?=htmlspecialchars($ban['reason']);?></td>
                                <td class="centerrow"><a target="_blank" href="http://steamcommunity.com/profiles/<?=$ban['admin_steamid'];?>"><?=htmlspecialchars($ban['admin_username']);?></a></td>
                                <td class="centerrow"><?=$starttime;?></td>
                                <td class="centerrow"><?=$endtime;?>  <span class="label label-<?=($ban['banstatus'] == 'active' || ($ban['length'] == 0 && $ban['banstatus'] != UNBANNED)) ? 'success' : (($ban['banstatus'] == UNBANNED) ? 'warning' : 'important');?>"><?=($ban['length'] == 0 && $ban['banstatus'] != _("unbanned")) ? ("permanent") : $ban['banstatus'];?></span></td>
                                <td class="morerow"><a href="<?=$CurrentURL;?>edit/<?=intval($ban['bid']);?>/" class="btn btn-info btn-mini"><span class="icon"> <i class="icooo-on-edit"></i> </span><?= _("edit");?></a></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>


                    <div class="pagination">
                        <ul>

                            <li<?=$prev1class;?>><a class="paginationtext" href="<?=$prev1url;?>"><?=_("Prev");?></a></li>

                            <?php if($CurrentPage > 3) { ?>
                                <li<?=$prev2class;?>><a href="<?=$firstpageurl;?>">1</a></li>
                            <?php } ?>

                            <li<?=$prev2class;?>><a href="<?=$prev2url;?>"><?=$prev2Num;?></a></li>
                            <li<?=$prev1class;?>><a href="<?=$prev1url;?>"><?=$prev1Num;?></a></li>
                            <li class="active"><a href="#"><?=$CurrentPage;?></a></li>
                            <li<?=$next1class;?>><a href="<?=$next1url;?>"><?=$next1Num;?></a></li>
                            <li<?=$next2class;?>><a href="<?=$next2url;?>"><?=$next2Num;?></a></li>

                            <?php if($MaxPages > 3 && $CurrentPage + 3 <= $MaxPages) { ?>
                                <li<?=$next2class;?>><a href="<?=$maxpageurl;?>"><?=$MaxPages;?></a></li>
                            <?php } ?>

                            <li<?=$next1class;?>><a class="paginationtext" href="<?=$next1url;?>"><?=_("Next");?></a></li>

                        </ul>
                    </div>

                </div>
            </div>



        </div>
    </div>
</div>

