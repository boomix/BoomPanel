<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span6">

            <?php include "model/alert-messages.php"; ?>

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?=ADD.' '.SERVER;?></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="POST" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label"><?=UP(SERVER).' '.NAME;?> :</label>
                            <div class="controls">
                                <input type="text" name="serverName" autocomplete="off" class="span11" placeholder="My cool server" value="<?=(isset($_POST['serverName'])) ? htmlspecialchars($_POST['serverName']) : '';?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?=UP(SERVER);?> IP :</label>
                            <div class="controls">
                                <input type="text" name="serverIP" autocomplete="off" class="span11" placeholder="123.123.123.123" value="<?=(isset($_POST['serverIP'])) ? htmlspecialchars($_POST['serverIP']) : '';?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?=UP(SERVER);?> port</label>
                            <div class="controls">
                                <input type="number" name="serverPort" autocomplete="off" class="span11" value="<?=(isset($_POST['serverPort'])) ? htmlspecialchars($_POST['serverPort']) : '27015';?>" min="1" max="999999" step="1" placeholder="27015">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">RCON <?=PASSWORD;?> :</label>
                            <div class="controls">
                                <input type="text" name="rconPassword" autocomplete="off" class="span11" placeholder="mysecretpassword" value="<?=(isset($_POST['rconPassword'])) ? htmlspecialchars($_POST['rconPassword']) : '';?>">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn btn-success pull-right"><?=(isset($match['params']['action'])) ? 'Edit' : 'Add';?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="span6">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
                    <h5>Currently added servers</h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Server name</th>
                            <th>Server IP</th>
                            <th>Rcon pw</th>
                            <th>Edit server</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ((array)$GetAllServers as $server) { ?>
                        <tr>
                            <td><?=$server['name'];?></td>
                            <td><?=$server['ip'];?>:<?=$server['port'];?></td>
                            <td><?=$server['rcon_pw'];?></td>
                            <td style="text-align: center"><a href="<?=$CurrentURL;?>edit/<?=$server['id'];?>/" class="btn btn-warning btn-mini">Edit</a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>