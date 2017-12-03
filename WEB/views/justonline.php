<?php if(!isset($db)) die(); ?>
<div class="container-fluid">
    <hr>

                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
                        <h5><?=UP(RESULTS);?></h5>
                    </div>
                    <div class="widget-content">
                        <p class="totalresults"><?=UP(LAST).' '.ONLINE.' '.ONLINELASTMINUTES.' ' .MINUTES;?></p>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><?=UP(PLAYER);?></th>
                                <th>SteamID</th>
                                <th><?=UP(WAS).' '.ONLINE. ' '. BEFORE;?></th>
                                <th><?=UP(TIME).' '.ONLINE;?></th>
                                <th><?=UP(SERVER);?></th>
                                <th><?=UP(ACTION);?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            //Load all player avatars with 1 request to steam
                            $Players = array();
                            foreach ((array)$FindAllUsers as $search)
                                if(!in_array($search['steamid'], $Players))
                                    array_push($Players, $search['steamid']);
                            $avatars = GetPlayersAvatars($Players);

                            ?>


                            <?php foreach ((array)$FindAllUsers as $result) { if(!empty($result['steamid'])) {?>
                                <tr>
                                    <td class="coutryrow <?=(!empty($result['bid']) && is_numeric($result['bid'])) ? 'banned' : '';?>"><img src="<?=WEBSITE;?>/img/flag/<?=$result['country_name'];?>.png" title="<?=$result['country_name'];?>" class="flag"></td>
                                    <td><a target="_blank" href="http://steamcommunity.com/profiles/<?=$result['steamid'];?>"><img src="<?=$avatars[$result['steamid']]?>" class="player-avatar"> <?=htmlspecialchars($result['username']);?></a></td>
                                    <td class="steamidrow"><?=toSteamID($result['steamid']);?> <a data-clipboard-text="<?=toSteamID($result['steamid']);?>" title="<?=COPY;?>" class="clipboard tip-right"><i class="icon-copy"></i></a></td>
                                    <td class="centerrow"><?=convertToHoursMinsBans(ONLINELASTMINUTES - $result['difference'], true);?></td>
                                    <td class="centerrow"><?=convertToHoursMinsBans($result['timeonline'], true);?></td>
                                    <td class="centerrow"><?=htmlspecialchars($result['name']);?></td>
                                    <td class="morerow"><a href="<?=WEBSITE;?>/bans/?banplayer=<?=$result['steamid'];?>" class="btn btn-danger btn-mini"><span class="icon"> <i class="icooo-on-edit"></i> </span><?=UP(BAN);?></a></td>
                                </tr>
                            <?php } } ?>
                            </tbody>
                        </table>






                    </div>
                </div>


        </div>
    </div>
</div>