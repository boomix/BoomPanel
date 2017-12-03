<?php

//Start sessions
session_start();
ob_start();

//Check if neccessary data is entered
$serverName = isset($_POST['serverName']) ? htmlspecialchars($_POST['serverName']) : '';
if(empty($serverName))
    die();

include '../config.php';

//Check if logged in
if(!isset($_SESSION['steamid']))
    die();


include '../includes.php';

//Get server date - rccon password, ip, port
$GetServerDate = $db->selectOne("SELECT `ip`, `port`, `rcon_pw` FROM bp_servers WHERE name = :serverName", array("serverName" => $serverName));

//Try to connect
try {

    $Query->Connect($GetServerDate['ip'], $GetServerDate['port'], 1, $Source );
    $Query->SetRconPassword($GetServerDate['rcon_pw']);

    $data = $Query->Rcon('sm_BPstatus');
    preg_match('~\{(?:[^{}]|(?R))*\}~', $data, $match);
    $json = (array)json_decode($match[0]);

    $stats      = ($json) ? $json['stats'] : ' ';
    $players    = ($json) ? $json['players'] : ' ';

}
catch( Exception $e ) {

    header(HEADERERROR);
    die( UP(SERVER_ERROR).$e->getMessage() );

} finally {
    $Query->Disconnect( );
    if(empty($json)) {

        header(HEADERERROR);
        die( UP(SERVER_ERROR).' <br><b>'.UP(DATA).':</b> '.(!empty($data)) ? $data : "-" );

    }
}




//Put all client IDs in one string
$AllClientIDs = "";
foreach ((array)$players as $player)
    $AllClientIDs .= (empty($AllClientIDs)) ? $player->p : ", ".$player->p;

//Get all user data from database
$PlayerDataFromDB = $db->select(
    "SELECT * FROM bp_players p 
            LEFT JOIN bp_countries c ON p.country = c.country_code 
            LEFT JOIN (SELECT * FROM bp_players_ip GROUP BY pid DESC) ip2 ON p.id = ip2.pid
            LEFT JOIN (SELECT * FROM bp_players_username GROUP BY pid ORDER BY last_used DESC) u2 ON p.id = u2.pid
            LEFT JOIN (SELECT `pid`, SUM(TIMESTAMPDIFF(MINUTE, connected, disconnected)) summa FROM bp_players_online WHERE sid = :sid GROUP BY pid) o ON p.id = o.pid
            WHERE `id` IN (".$AllClientIDs.") 
            GROUP BY steamid",
            array( "sid" => intval($stats->sid) )
);

//Load all avatars
$AvatarPlayers = array();
foreach ((array)$PlayerDataFromDB as $player)
    if(!in_array($player['steamid'], $AvatarPlayers))
        array_push($AvatarPlayers, $player['steamid']);
$avatars = GetPlayersAvatars($AvatarPlayers);

//Display first important stuff
echo $stats->map .'||'.$stats->online.'||';

//Display all the players
for($i = 0;$i < $stats->online;$i++) {

    //Get main data
    $team       = intval($players[$i]->t);
    $online     = intval($players[$i]->o);
    $kills      = intval($players[$i]->k);
    $deaths     = intval($players[$i]->d);
    $steamid    = (isset($PlayerDataFromDB[$i]['steamid'])) ? $PlayerDataFromDB[$i]['steamid'] : 0;
    $country    = (isset($PlayerDataFromDB[$i]['country_name'])) ?$PlayerDataFromDB[$i]['country_name'] : UP(UNKNOWN);
    $IP         = (isset($PlayerDataFromDB[$i]['ip'])) ? $PlayerDataFromDB[$i]['ip'] : "0.0.0.0";
    $username   = (isset($PlayerDataFromDB[$i]['username'])) ? $PlayerDataFromDB[$i]['username'] : PLAYER.' '.NOTFOUND;
    $totalONL   = (isset($PlayerDataFromDB[$i]['summa'])) ? intval($PlayerDataFromDB[$i]['summa']) : 0;
    $class      = null; if($team == 2) $class = "T"; else if($team == 3) $class = "CT"; else $class = "SPEC";
    $avatar     = (isset($avatars[$steamid])) ? $avatars[$steamid] : "https://i.imgur.com/LrwiLir.png";
    $sessiON    = ($online != 0) ? convertToHoursMinsBans($online, true) : "0m";
    $nowOn      = convertToHoursMinsBans($totalONL + $online, true);

    //Display the table
    echo '<tr class="empty" id="'.$steamid.'">';
    echo '  <td class="coutryrow '.$class.'"><img src="'.WEBSITE.'/img/flag/'.$country.'.png" class="flag"></td>';
    echo '    <td style="overflow: hidden;white-space: nowrap;">';
    echo '      <a target="_blank" href="http://steamcommunity.com/profiles/'.$steamid.'">';
    echo '          <img src="'.$avatar.'" class="player-avatar">';
    echo '          <span id="username">'.htmlspecialchars($username).'</span>';
    echo '      </a>';
    echo '    </td>';
    echo '    <td class="steamidrow" >'.toSteamID($steamid).' <a data-clipboard-text="'.toSteamID($steamid).'" title="'.COPY.'" class="clipboard tip-bottom"><i class="icon-copy"></i></a></td>';
    echo '    <td class="steamidrow">'.$IP.' <a data-clipboard-text="'.$IP.'" title="'.COPY.'" class="clipboard tip-bottom"><i class="icon-copy"></i></a></td>';
    echo '    <td class="centerrow">'.$sessiON .'</td>';
    echo '    <td class="centerrow">'.$nowOn.'</td>';
    echo '    <td class="centerrow" style="width:50px">'.$kills.'</td>';
    echo '    <td class="centerrow" style="width:50px">'.$deaths.'</td>';
    echo '    <td class="morerow">';
    echo '           <button onclick="OpenActionMenu(\''.$steamid.'\')" href="#actionMenu" data-toggle="modal" class="btn btn-info btn-mini">';
    echo '                <span class="icon"> <i class="icon-edit"></i></span>';
    echo '            </button>';
    echo '          </td>';
    echo '</tr>';


}

?>