<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
                    <h5><?= _("About search");?></h5>
                </div>
                <div class="widget-content"><?= _("You can search users by <i>'username', 'IP', 'steamID32', 'steamID64', 'steamcommunity profile URL'</i>. Panel saves every IP and username with what player has been in the server, so it's easier to find alternative accouts or anyone who has used your username before.<br> <b>Undestand that this search is not finished and will have upgrades later!</b>") ?></div>
            </div>


            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?= _("Search Players");?></h5>
                </div>
                <div class="widget-content">

                        <div class="controls controls-row">
                            <form action="#" method="GET" class="form-horizontal">
                                <input type="text" name="search" placeholder="..." autocomplete="off" class="span11 m-wrap" style="margin-bottom: 1rem" value="<?=(isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <input type="submit" name="submit" class="btn btn-success span1" value="<?= _('Search');?>">
                            </form>
                        </div>

                </div>
            </div>

            <hr>


            <?php if(isset($SearchUsers)) { //print_r($searchQuery); echo '<br>'; print_r($searchArray);?>

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                    <h5><?= _("Results");?></h5>
                </div>
                <div class="widget-content">
                    <?php $results = $limit = ($CurrentPage == 1) ? '1 - '.ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.' - '.$CurrentPage * ITEMSPERPAGE; ;?>
                    <p class="totalresults"><?= _("Total Results");?>: <b><?=$CountAllUsers['count'];?></b> | <?= _("Showing Results from");?> <b><?=$results;?></b></p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= _("Player");?></th>
                            <th>SteamID</th>
                            <th><?= _("Last");?> IP</th>
                            <th><?= _("Online time");?></th>
                            <th><?= _("connections");?></th>
                            <th><?= _("First online");?></th>
                            <th><?= _("Last online");?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        //Load all player avatars with 1 request to steam
                        $Players = array();
                        foreach ((array)$SearchUsers as $search)
                            if(!in_array($search['steamid'], $Players))
                                array_push($Players, $search['steamid']);
                        $avatars = GetPlayersAvatars($Players);

                        ?>


                        <?php foreach ((array)$SearchUsers as $result) { if(!empty($result['steamid'])) {?>
                            <tr>
                                <?php
                                    //Give other style if player is online

                                ?>
                                <td class="coutryrow"><img src="<?=WEBSITE;?>/img/flag/<?=$result['country_name'];?>.png" title="<?=$result['country_name'];?>" class="flag"></td>
                                <td><a target="_blank" href="http://steamcommunity.com/profiles/<?=$result['steamid'];?>"><img src="<?=$avatars[$result['steamid']]?>" class="player-avatar"> <?=htmlspecialchars($result['username']);?></a></td>
                                <td class="steamidrow"><?=toSteamID($result['steamid']);?> <a data-clipboard-text="<?=toSteamID($result['steamid']);?>" title="<?=COPY;?>" class="clipboard tip-right"><i class="icon-copy"></i></a></td>
                                <td class="centerrow"><?=$result['ip'];?> <a data-clipboard-text="<?=$result['ip'];?>" title="<?=COPY;?>" class="clipboard tip-right"><i class="icon-copy"></i></a></td>
                                <td class="centerrow"><?=convertToHoursMinsBans($result['timeonline'], true);?></td>
                                <td class="centerrow"><?=intval($result['connections']);?></td>
                                <td class="centerrow"><?=date(TIMEFORMAT, strtotime($result['first_online']));?></td>
                                <td class="centerrow"><?=date(TIMEFORMAT, strtotime($result['last_online']));?></td>
                            </tr>
                        <?php } } ?>
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

                            <li<?=$prev1class;?>><a class="paginationtext" href="<?=$prev1url;?>"><?=_("Prev");?></a></li>
                            <li<?=$prev2class;?>><a href="<?=$prev2url;?>"><?=$prev2Num;?></a></li>
                            <li<?=$prev1class;?>><a href="<?=$prev1url;?>"><?=$prev1Num;?></a></li>
                            <li class="active"><a href="#"><?=$CurrentPage;?></a></li>
                            <li<?=$next1class;?>><a href="<?=$next1url;?>"><?=$next1Num;?></a></li>
                            <li<?=$next2class;?>><a href="<?=$next2url;?>"><?=$next2Num;?></a></li>
                            <li<?=$next1class;?>><a class="paginationtext" href="<?=$next1url;?>"><?=_("Next");?></a></li>

                        </ul>
                    </div>





                </div>
            </div>
            <?php } ?>


        </div>
    </div>
</div>
