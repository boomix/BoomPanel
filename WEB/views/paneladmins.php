<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">

        <div class="span6">
            <?php include "model/alert-messages.php"; ?>
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?= _("Add panel admins");?></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="POST" class="form-horizontal">


                        <div class="control-group">
                            <label class="control-label"><?= _("Admin");?> :</label>
                            <div class="controls">
                                <input type="text" name="admin" value="<?=isset($_POST['admin']) ? htmlspecialchars($_POST['admin']) : '';?>" autocomplete="off" class="span11" placeholder="..." value="">
                            </div>
                        </div>

                        <?php if(isset($match['params']['action']) && $match['params']['action'] == 'edit'){ ?>
                            <div class="control-group">
                                <label class="control-label"><?=_("Page access");?>:</label>
                                <?php foreach ((array)$GetAllPermissions as $permission) { ?>
                                    <div class="controls">
                                        <label><input class="check" type="checkbox" <?=isset($_POST['checkboxes'][$permission['permissionid']]) ? 'checked' : "";?> name="checkboxes[]" value="<?=intval($permission['permissionid']);?>" /><?=_($permission['name']);?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn btn-success pull-right"><?= _("Add");?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="span6">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
                    <h5><?= _("All panel admins");?></h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?= _("Admin");?></th>
                            <th><?= _("Edit");?></th>
                            <th><?= _("Delete");?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <!-- Shows main admin without option to delete -->
                        <tr>
                            <td>
                                <?php $data = GetPlayerData(MAINADMIN);?>
                                <a target="_blank" href="http://steamcommunity.com/profiles/<?=MAINADMIN;?>">
                                    <img src="<?=$data['avatar'];?>" class="player-avatar">
                                    <?=$data['username'];?>
                                </a>
                            </td>
                            <td> </td>
                            <td> </td>
                        </tr>

                        <?php

                        //Load all player avatars with 1 request to steam
                        $Players = array();
                        foreach ((array)$GetAllPanelAdmins as $admin)
                            if($admin['steamid'] != 0 && !in_array($admin['steamid'], $Players))
                                array_push($Players, $admin['steamid']);
                        $playerdata = GetPlayersAvatars($Players);

                        ?>

                        <!-- Shows the rest of the admins -->
                        <?php
                        foreach ((array)$GetAllPanelAdmins as $admin) {
                            if(!empty($admin['id'])) {
                                ?>
                                <tr>
                                    <td>
                                        <a target="_blank" href="http://steamcommunity.com/profiles/<?=$admin['steamid'];?>">
                                            <img src="<?=$playerdata[$admin['steamid']];?>" class="player-avatar">
                                            <?=$playerdata['username-'.$admin['steamid']];?>
                                        </a>
                                    </td>
                                    <td style="text-align: center">
                                        <a href="<?=$CurrentURL;?>edit/<?=$admin["id"];?>" class="btn btn-warning btn-mini"><?=_("edit");?></a>
                                    </td>
                                    <td style="text-align: center">
                                        <a href="<?=$CurrentURL;?>delete/<?=$admin["id"];?>" class="btn btn-danger btn-mini"><?=_("delete");?></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
