<?php

//If just php is opened up
if(!isset($db)) die();

$b = (strtolower($title) == strtolower(NAV_MUTEGAG)) ? false : true;
$database = ($b) ? "bp_bans" : "bp_mutegag";

//Pagination
$CurrentPage = (isset($match['params']['action']) && $match['params']['action'] == 'page') ? $match['params']['id'] : 1;
$limit = ($CurrentPage == 1) ? ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.','.ITEMSPERPAGE;

$defaultSelect = "SELECT *,
                p.steamid player_steamid,
                p2.steamid admin_steamid,
                pu.username player_username,
                pu2.username admin_username,
                IF(TIMESTAMPDIFF(MINUTE, b.time, now()) < b.length, '" . htmlspecialchars(ACTIVE) . "', '" . htmlspecialchars(EXPIRED) . "') as banstatus
                FROM ".$database." b
                LEFT JOIN bp_servers s ON b.sid = s.id
                LEFT JOIN bp_players p ON b.pid = p.id
                LEFT JOIN bp_players p2 ON b.aid = p2.id
                LEFT JOIN bp_players_username pu ON b.pid = pu.pid
                LEFT JOIN bp_players_username pu2 ON b.aid = pu2.pid
                LEFT JOIN bp_countries c ON c.country_code = p.country";


if(isset($_GET['search']))
{

    $data = array(); $query = "";

    //Player INPUT
    if(!empty($_GET['player']))
    {
        //Search by player username
        $query .= " OR pu.username LIKE :username";
        $data['username'] = "%".$_GET['player']."%";

        //Check if in player place is written steamID
        $steamid = getSteamID($_GET['player']);
        $query  .= ($steamid != -1) ? " OR p.steamid = :psteamid" : "";
        if($steamid != -1) $data['psteamid'] = $steamid;
    }

    //admin INPUT
    $addAdminSearch = ""; $addAdminSteamID = "";
    if(!empty($_GET['admin']))
    {
        //Search by player username
        $query .= " OR pu2.username LIKE :ausername";
        $data['ausername'] = "%".$_GET['admin']."%";

        //Check if in player place is written steamID
        $steamid = getSteamID($_GET['admin']);
        $query  .= ($steamid != -1) ? " OR p2.steamid = :asteamid" : "";
        if($steamid != -1) $data['asteamid'] = $steamid;

    }

    //reason INPUT
    if(!empty($_GET['reason']))
    {
        $query .= " OR reason LIKE :reason";
        $data['reason'] = "%".htmlspecialchars($_GET['reason'])."%";
    }

    if(!empty($_GET['server']) && is_numeric($_GET['server']) || is_numeric($_GET['server']) && $_GET['server'] == 0)
    {
        $query .= (empty($query)) ? " OR " : " AND ";
        $query .= "sid = :sid";
        $data['sid'] = intval($_GET['server']);
    } else {
        $query .= (empty($query)) ? " OR " : " AND ";
        $query .= "sid >= 0";
    }

    if(!empty($_GET['mgtype']))
    {
        $count = 0;
        foreach((array)$_GET['mgtype'] as $type) {
            $query .= " ".(($count == 0) ? "AND" : "OR")." mgtype = " . intval($type);
            $count++;
        }
    }

    echo_dev($defaultSelect." WHERE 1 = 0 ".$query." GROUP BY bid ORDER BY bid DESC LIMIT ".$limit);


    $GetAllBans     = $db->select($defaultSelect." WHERE 1 = 0 ".$query." GROUP BY bid ORDER BY bid DESC LIMIT ".$limit, $data);


    //Count all search bans
    $leftjoin = explode("FROM", $defaultSelect);
    $CountAllBans   = $db->selectOne("SELECT COUNT(DISTINCT bid) AS count FROM ".$leftjoin[1]." WHERE 1 = 0 ".$query." ORDER BY bid", $data);

} else {


    //Why the fuck do I need to write so big queries? :(
    $GetAllBans     = $db->select($defaultSelect." GROUP BY bid ORDER BY bid DESC LIMIT ".$limit);
    $CountAllBans   = $db->selectOne("SELECT COUNT(bid) AS count FROM ".$database);

    echo_dev($defaultSelect." GROUP BY bid ORDER BY bid DESC LIMIT ".$limit);

}

$MaxPages = ceil($CountAllBans['count'] / ITEMSPERPAGE);

//Redirect to home if page is not found
if($CurrentPage != 1 && $CurrentPage > $MaxPages)
{
    header('Location: ' . $CurrentURL );
    exit;
}
$GetAllServers = $db->select("SELECT name, id FROM bp_servers");


//If player wants to edit ban
if(isset($match['params']['action'], $match['params']['id']) && !isset($_POST['submit'])) {
    //Get server data

    $data = array("id" => intval($match['params']['id']));

    if ($match['params']['action'] == 'edit') {

        //Check if ban with that ID exists
        $ban = $db->selectOne("SELECT * FROM ".$database." b LEFT JOIN bp_players p ON b.pid = p.id LEFT JOIN bp_servers s ON b.sid = s.id WHERE bid = :id", $data);
        if ($ban) {
            $_SESSION['warning'] = UP(NOWEDITING) .' '.BAN;

            $_POST['reason']        = $ban['reason'];
            $_POST['player']        = $ban['steamid'];
            $_POST['server']        = $ban['name'];

            if(!$b) { $_POST['mgtype'] = $ban['mgtype']; }

            $length = $ban['length'];
            $days   = $length / 1440;
            $hours  = ($length % 1440) / 60;
            $minutes = ($length % 60);

            $_POST['days']      = round($days, 0, PHP_ROUND_HALF_DOWN);
            $_POST['hours']     = round($hours, 0, PHP_ROUND_HALF_DOWN);
            $_POST['minutes']   = round($minutes, 0, PHP_ROUND_HALF_DOWN);

        } else {
            header('Location: ' . $CurrentURL );
            exit;
        }

    } else if($match['params']['action'] == 'unban') {

        $ban = $db->selectOne("SELECT *, MAX(`length` - TIMESTAMPDIFF(MINUTE, time, now())) as timeleft FROM ".$database." WHERE bid = :id", $data);
        if ($ban) {

            if($ban['unbanned'] == 0) {
                $_SESSION['success'] = UP(PLAYER) . ' ' . UNBANNED;
                $db->query("UPDATE ".$database." SET unbanned = 1 WHERE bid = :id", $data);
                if(!$b) CommandToPlayer($Query, $ban['pid'], 'sm_BPmutegagrem "{STEAMID}" "'.$ban['mgtype'].'"');
            } else {
                $_SESSION['success'] = UP(PLAYER) . ' ' . BAN . ' '.RESTORED;
                $db->query("UPDATE ".$database." SET unbanned = 0 WHERE bid = :id", $data);
                if(!$b && ($ban['timeleft'] > 0 || $ban['length'] == 0))
                    CommandToPlayer($Query, $ban['pid'], 'sm_BPmutegagres "{STEAMID}" "'.$ban['mgtype'].'" "'.$ban['length'].'" "'.$ban['timeleft'].'"');
            }

        }

        header('Location: ' . $CurrentURL );
        exit;
    }

}



//If new ban is added
if(isset($_POST['player'], $_POST['reason'], $_POST['server'], $_POST['days'], $_POST['hours'], $_POST['minutes'], $_POST['submit']))
{

    //If player is not entered
    if(empty($_POST['player']))
    {
        $_SESSION['error'] = ENTERPLAYER;
        return;
    }

    //Get player steamID64 from post
    $steamid = getSteamID(htmlspecialchars($_POST['player']));
    if($steamid == -1)
    {
        $_SESSION['error'] = PLAYERNOTFOUND;
        return;
    }

    //Get server ID
    $serverID = 0;
    $GetserverID = $db->selectOne("SELECT `id` FROM bp_servers WHERE `name` = :serverName", array("serverName" => htmlspecialchars($_POST['server'])) );
    if($GetserverID['id'])
        $serverID = intval($GetserverID['id']);

    //Get user ID
    $playerID = -1;
    $GetPlayerID = $db->selectOne("SELECT `id` FROM bp_players WHERE `steamid` = :steamid", array("steamid" => htmlspecialchars($steamid)));
    if($GetPlayerID['id'])
        $playerID = intval($GetPlayerID['id']);
    else
    {
        //If user id not added, add it
        $db->query("INSERT INTO bp_players (steamid, country) VALUES (:steamid, '".htmlspecialchars(UNKNOWN_COUNTRY)."')", array("steamid" => htmlspecialchars($steamid)));
        $GetPlayerID = $db->selectOne("SELECT `id` FROM bp_players WHERE `steamid` = :steamid", array("steamid" => htmlspecialchars($steamid)));
        if($GetPlayerID['id'])
            $playerID = intval($GetPlayerID['id']);
    }

    //Get admin ID
    $GetAdminData = $db->selectOne("SELECT `id`, `username` FROM bp_players p LEFT JOIN bp_players_username u ON p.id = u.pid WHERE `steamid` = :steamid GROUP BY `id` ORDER BY last_used DESC LIMIT 1", array("steamid" => htmlspecialchars($steamprofile['steamid'])));
    $adminID        = $GetAdminData['id'];
    $adminUsername  = $GetAdminData['username'];

    if(!isset($adminID) || $playerID == -1)
    {
        $_SESSION['error'] = THEREWASERROR;
    } else {


        $days       = (empty($_POST['days'])) ? 0 : intval($_POST['days']);
        $hours      = (empty($_POST['hours'])) ? 0 : intval($_POST['hours']);
        $minutes    = (empty($_POST['minutes'])) ? 0 : intval($_POST['minutes']);
        $length     = intval(($days * 1440) + ($hours * 60) + $minutes);

        $data = array
        (
            "pid" => intval($playerID),
            "sid" => intval($serverID),
            "aid" => intval($adminID),
            "reason" => htmlspecialchars($_POST['reason']),
            "length" => $length
        );

        if(!$b)
        {
            $mgtype = (!isset($_POST['mgtype']) || empty($_POST['mgtype'])) ? 0 : intval($_POST['mgtype']);
            if($mgtype < 0 || $mgtype > 2) $mgtype = 0;
            $data['mgtype'] = $mgtype;
        }

        if(!isset($match['params']['action'], $match['params']['id'])) {

            //Insert into database
            $db->query("INSERT INTO ".$database." (`pid`, `sid`, `aid`, `reason`, `length`".((!$b)? ', `mgtype`':'').") VALUES (:pid, :sid, :aid, :reason, :length".(($b)? '' :', :mgtype').")", $data);
            $_SESSION['success'] = UP(($b) ? BAN : MUTE.' '.GAG)." ".SUCCESSFULLY." ".ADDED;

            echo_dev("INSERT INTO ".$database." (`pid`, `sid`, `aid`, `reason`, `length`".((!$b)? ', `mgtype`':'').") VALUES (:pid, :sid, :aid, :reason, :length".(($b)? '' :', :mgtype').")");
            echo_dev($data);

            //Kick player if he is ingame
            CommandToPlayer($Query, $playerID, ($b) ? 'sm_BPbankick "{STEAMID}" "'.htmlspecialchars($adminUsername).'" "'.htmlspecialchars($_POST['reason']).'" "'.$length.'"' :
                'sm_BPmutegagadd "{STEAMID}" "'.$mgtype.'" "'.htmlspecialchars($_POST['reason']).'" "'.$length.'"');

        } else if(isset($match['params']['action'], $match['params']['id']) && $match['params']['action'] == 'edit'){

            $data["bid"] = intval($match['params']['id']);
            unset($data['aid']);
            $db->query("UPDATE ".$database." SET `pid` = :pid, `sid` = :sid, `reason` = :reason, `length` = :length".(($b)? '' : ', `mgtype` = :mgtype')." WHERE `bid` = :bid", $data);
            $_SESSION['success'] = UP(YOU)." ".EDITED." ".BAN." ".SUCCESSFULLY;

            if(DEVELOPERMOD == 1) {
                echo_dev("UPDATE ".$database." SET `pid` = :pid, `sid` = :sid, `reason` = :reason, `length` = :length".(($b)? '' : ', `mgtype` = :mgtype')." WHERE `bid` = :bid");
                echo_dev($data);
            }

            if(!$b)
                CommandToPlayer($Query, $playerID, "");

        }

        if(DEVELOPERMOD == 0) {

            header('Location: ' . $CurrentURL);
            exit;
        }

    }


}


?>
