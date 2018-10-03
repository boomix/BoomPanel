<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span12">

            <?php include "model/alert-messages.php"; ?>

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-plus"></i> </span>
                    <h5><?= _("Add Server"); ?></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="POST" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label"><?= _("Admin"); ?> :</label>
                            <div class="controls">
                                <input type="text" name="admin" autocomplete="off" class="span11" placeholder="..." value="<?=(isset($_POST['admin'])) ? htmlspecialchars($_POST['admin']) : '';?>">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?= _("Server");?> </label>
                            <div class="controls">
                                <select name="server">
                                    <?php foreach ((array)$GetAllServers as $server) { ?>
                                        <option value="<?=$server['id'];?>" <?=(isset($_POST['server']) && $_POST['server'] == $server['id']) ? 'selected="selected"' : '';?>><?=$server['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?= _("Group");?></label>
                            <div class="controls">
                                <select name="group">
                                    <?php
                                    foreach ((array) $GetAllGroups as $group){
                                        //Check if active
                                        $class = (isset($_POST['group']) && $group['id'] == $_POST['group']) ? "selected" : "";
                                        ?>
                                        <option value="<?=$group['id'];?>" <?=$class;?>><?=$group['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn btn-success pull-right"><?=(isset($match['params']['action'])) ? _("Edit") : _("Add");?></button>
                            <?php if(isset($match['params']['action']) && $match['params']['action'] == 'edit'){?>
                                <a href="#deleteAlert" onclick="updatedeleteurl(<?=intval($admin['aid']);?>)" data-toggle="modal" class="btn btn-danger pull-left"><?= _("Delete");?></a>
                            <?php } ?>
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
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?= _("All Server Admins");?></h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= _("Player");?></th>
                            <th><?= _("Group Name");?></th>
                            <th><?= _("Server");?></th>
                            <th><?= _("Added");?></th>
                            <th><?= _("Edit Admin");?></th>
                        </tr>
                        </thead>
                        <tbody>

                            <?php
                                $Players = [];
                                foreach ((array)$GetAllAdmins as $admin) {
                                    if(!in_array($admin['steamid'], $Players)) {
                                        array_push($Players, $admin['steamid']);
                                    }
                                }

                                $avatars = [];
                                $first = true;
                                foreach (array_chunk($Players, 100) as $players) {
                                    if ($first) {
                                        $avatars = GetPlayersAvatars($players);
                                        $first = false;
                                        continue;
                                    }

                                    array_replace($avatars, GetPlayersAvatars($players));
                                }
                            ?>

                            <?php foreach ((array) $GetAllAdmins as $admin) { if(!empty($admin['steamid'])){?>
                            <tr>
                                <td class="coutryrow"><img src="<?=WEBSITE;?>/img/flag/<?=$admin['country_name'];?>.png" title="<?=$admin['country_name'];?>" class="flag"></td>
                                <td>
                                    <a target="_blank" href="http://steamcommunity.com/profiles/<?=$admin['steamid'];?>">
                                        <img src="<?=$avatars[$admin['steamid']]?>" class="player-avatar">
                                        <?=(!empty($admin['player_username'])) ? htmlspecialchars($admin['player_username']) : htmlspecialchars($avatars['username-'.$admin['steamid']]);?>
                                    </a>
                                </td>
                                <td class="centerrow"><?=htmlspecialchars($admin['group_name']);?></td>
                                <td class="centerrow"><?=htmlspecialchars($admin['server_name']);?></td>
                                <td class="centerrow"><?=date(TIMEFORMAT, strtotime($admin['add_time']));?></td>
                                <td class="centerrow"><a href="<?=$CurrentURL;?>edit/<?=intval($admin['aid']);?>/" class="btn btn-info btn-mini"><span class="icon"> <i class="icooo-on-edit"></i> </span><?= _("Edit");?></a></td>
                            </tr>

                            <?php } else {echo "<tr><td>-</td></tr>";} } ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



</div>

<div id="deleteAlert" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><?= _("Are you sure you want to delete?");?></h3>
    </div>
    <div class="modal-body">
        <p><?= _("If you delete this group, all the admins with this group will also be deleted!");?></p>
    </div>
    <div class="modal-footer">
        <a id="deleteurl" class="btn btn-primary" href="#"><?=_("Confirm");?></a>
        <a data-dismiss="modal" class="btn" href="#"><?=_("Cancel");?></a>
    </div>
</div>

<script>
    function updatedeleteurl(id)
    {
        $('#deleteurl').attr('href', '<?=$CurrentURL;?>delete/' + id);
    }
</script>
