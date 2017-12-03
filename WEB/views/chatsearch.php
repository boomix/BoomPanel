<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5><?=UP(CHAT).' '.SEARCH;?></h5>
                </div>
                <div class="widget-content">

                    <div class="controls controls-row">
                        <form action="<?=$CurrentURL;?>" method="GET" class="form-horizontal">
                            <input type="text" name="search" placeholder="..." autocomplete="off" class="span11 m-wrap" style="margin-bottom: 1rem" value="<?=(isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <input type="submit" name="submit" class="btn btn-success span1" value="<?=UP(SEARCH);?>">
                            <div class="widget-box collapsible">
                                <div class="widget-title"> <a href="#MoreOptions" data-toggle="collapse"> <span class="icon"><i class="icon-arrow-right"></i></span>
                                        <h5><?=UP(MORE).' '.OPTIONS;?></h5>
                                    </a> </div>
                                <div class="collapse <?=(isset($_GET['server']) && $_GET['server'] != 0 || isset($_GET['date']) && $_GET['date'] != 0) ? 'in' : '';?>" id="MoreOptions">
                                    <div class="widget-content">
                                        <div class="span3">
                                            <select name="server">
                                                <?php foreach ((array)$GetAllServers as $server) { ?>
                                                    <option <?=(isset($_GET['server']) && $_GET['server'] == $server['id']) ? 'selected="selected"' : '';?> value="<?=intval($server['id']);?>"><?=$server['name'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="span3">
                                            <select name="date">
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 0) ? 'selected="selected"' : '';?> value="0"><?=UP(ANY).' '.TIME;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 60) ? 'selected="selected"' : '';?>value="60"><?=UP(LAST).' '.HOUR;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 360) ? 'selected="selected"' : '';?>value="360"><?=UP(LAST).' 6 '.HOURS;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 1440) ? 'selected="selected"' : '';?>value="1440"><?=UP(LAST).' '.DAY;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 2880) ? 'selected="selected"' : '';?>value="2880"><?=UP(LAST).' 2 '.DAYS;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 10080) ? 'selected="selected"' : '';?>value="10080"><?=UP(LAST).' '.WEEK;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 20160) ? 'selected="selected"' : '';?>value="20160"><?=UP(LAST).' 2 '.WEEKS;?></option>
                                                <option <?=(isset($_GET['date']) && $_GET['date'] == 43829) ? 'selected="selected"' : '';?>value="43829"><?=UP(LAST).' 30 '.DAYS;?></option>
                                            </select>
                                        </div>
                                        <br style="clear:both;">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <hr>


            <?php if(isset($ChatSearch)) { //print_r($searchQuery); echo '<br>'; print_r($searchArray);?>

                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                        <h5><?=UP(RESULTS);?></h5>
                    </div>
                    <div class="widget-content">
                        <?php $results = $limit = ($CurrentPage == 1) ? '1 - '.ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.' - '.$CurrentPage * ITEMSPERPAGE; ;?>
                        <p class="totalresults"><?=UP(TOTAL).' '.RESULTS;?>: <b><?=$CountChatSearch['count'];?></b> | <?=SHOWINGRESULTS;?> <b><?=$results;?></b></p>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><?=UP(PLAYER);?></th>
                                <th><?=UP(MESSAGE);?></th>
                                <th>SteamID</th>
                                <th><?=UP(SERVER);?></th>
                                <th><?=UP(TIME);?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            //Load all player avatars with 1 request to steam
                            $Players = array();
                            foreach ((array)$ChatSearch as $search)
                                if(!in_array($search['steamid'], $Players))
                                    array_push($Players, $search['steamid']);
                            $avatars = GetPlayersAvatars($Players);

                            ?>


                            <?php foreach ((array)$ChatSearch as $result) { if(!empty($result['steamid'])) {?>
                                <tr>
                                    <?php
                                    //Give other style if player is online

                                    ?>
                                    <td class="coutryrow"><img src="<?=WEBSITE;?>/img/flag/<?=$result['country_name'];?>.png" title="<?=$result['country_name'];?>" class="flag"></td>
                                    <td><a target="_blank" href="http://steamcommunity.com/profiles/<?=$result['steamid'];?>"><img src="<?=$avatars[$result['steamid']]?>" class="player-avatar"> <?=htmlspecialchars($result['username']);?></a></td>
                                    <td><?=htmlspecialchars($result['message']);?></td>
                                    <td class="steamidrow"><?=toSteamID($result['steamid']);?> <a data-clipboard-text="<?=toSteamID($result['steamid']);?>" title="<?=COPY;?>" class="clipboard tip-right"><i class="icon-copy"></i></a></td>
                                    <td class="steamidrow"><?=htmlspecialchars($result['name']);?></td>
                                    <td class="steamidrow"><?=date(TIMEFORMAT, strtotime($result['time']));?></td>
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
            <?php } ?>


        </div>
    </div>
</div>