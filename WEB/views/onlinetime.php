<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
                    <h5><?=UP($username);?></h5>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?=UP(SERVER);?></th>
                            <th><?=UP(ONLINE);?></th>
                            <th><?=UP(LAST).' '.WEEK;?></th>
                            <th><?=UP(CONNECTIONS);?></th>
                            <th><?=UP(LAST).' '.ONLINE;?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ((array)$data as $data) { if(empty($data['name'])) return; ?>
                            <tr class="odd gradeX">
                                <td <?php if($data['online'] == $data['sid']) echo 'style="border-left: solid 3px #3CCC08"';?>><?=$data['name'];?></td>
                                <td style="text-align:center"><?=convertToHoursMinsBans($data['sum'], true);?></td>
                                <td style="text-align:center">-</td>
                                <td style="text-align:center"><?=$data['con'];?></td>
                                <td style="text-align:center"><?=convertToHoursMinsBans($data['last_online'], true, true);?> <?=_('ago');?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


