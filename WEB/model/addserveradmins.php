<?php

if(!isset($db)) die();
$GetAllServers  = $db->select("SELECT * FROM bp_servers ORDER BY id ASC");
$GetAllGroups   = $db->select("SELECT * FROM bp_admin_groups ORDER BY id");
$GetAllAdmins   = $db->select("SELECT *, s.name server_name, g.name group_name, a.pid pid FROM bp_admins a 
                                        LEFT JOIN bp_players p ON a.pid = p.id 
                                        LEFT JOIN bp_admin_groups g ON a.gid = g.id 
                                        LEFT JOIN bp_countries c ON p.country = c.country_code 
                                        LEFT JOIN (SELECT `username`, `pid` FROM bp_players_username GROUP BY pid ORDER BY last_used DESC) u ON a.pid = u.pid 
                                        LEFT JOIN bp_servers s ON a.sid = s.id WHERE (TIMESTAMPDIFF(MINUTE, add_time, now()) < g.usetime OR g.usetime = 0) ORDER BY immunity DESC");



//If player wants to edit admin
if(isset($match['params']['action'], $match['params']['id']) && !isset($_POST['submit'])) {

    //Get server data
    $data = array("id" => intval($match['params']['id']));

    if ($match['params']['action'] == 'edit') {

        //Check if ban with that ID exists
        $admin = $db->selectOne("SELECT * FROM bp_admins a LEFT JOIN bp_players p ON a.pid = p.id LEFT JOIN bp_servers s ON a.sid = s.id WHERE aid = :id", $data);
        if ($admin) {
            $_SESSION['warning'] = UP(NOWEDITING) .' '.ADMIN;

            $_POST['admin']        = $admin['steamid'];
            $_POST['group']        = $admin['gid'];
            $_POST['server']       = $admin['sid'];

        } else {
            header('Location: ' . $CurrentURL );
            exit;
        }

    } else if($match['params']['action'] == 'delete') {

        //Get playerID for who the admin is getting removed
        $player = $db->selectOne("SELECT `pid` FROM bp_admins WHERE aid = :id", $data);
        $_SESSION['success'] = UP(ADMIN) . ' ' . DELETED;
        $db->query("DELETE FROM bp_admins WHERE aid = :id", $data);

        if(!empty($player['pid']))
            CommandToPlayer($Query, $player['pid'], 'sm_BPreloadadmin "{STEAMID}"');

        header('Location: ' . $CurrentURL );
        exit;
    }

}








//When ban data is posted
if(isset($_POST['admin'], $_POST['server'], $_POST['group'], $_POST['submit']))
{

    //If player is not entered
    if(empty($_POST['admin']))
    {
        $_SESSION['error'] = ENTERPLAYER;
        return;
    }

    //Get player steamID64 from post
    $steamid = getSteamID(htmlspecialchars($_POST['admin']));
    if($steamid == -1)
    {
        $_SESSION['error'] = PLAYERNOTFOUND;
        return;
    }

    //Check if server ID exists in database
    $serverID = 0;
    $GetserverID = $db->selectOne("SELECT `id` FROM bp_servers WHERE `id` = :serverID", array("serverID" => intval($_POST['server'])) );
    if($GetserverID['id'])
        $serverID = intval($GetserverID['id']);


    //Check if group ID exists in database
    $groupID = -1;
    $GetGroupID = $db->selectOne("SELECT `id` FROM bp_admin_groups WHERE `id` = :groupID", array("groupID" => intval($_POST['group'])) );
    if($GetGroupID['id'])
        $groupID = intval($GetGroupID['id']);

    if($groupID == -1)
    {
        $_SESSION['error'] = GROUP.' '.NOTFOUND;
        return;
    }

    //Get adding admin ID
    $adminID = -1;
    $GetAdminID = $db->selectOne("SELECT `id` FROM bp_players WHERE `steamid` = :steamid", array("steamid" => htmlspecialchars($steamid)));
    if($GetAdminID['id'])
        $adminID = intval($GetAdminID['id']);
    else
    {
        //If admin id not in database, add it
        $db->query("INSERT INTO bp_players (steamid, country) VALUES (:steamid, '".UNKNOWN_COUNTRY."')", array("steamid" => htmlspecialchars($steamid)));
        $GetAdminID = $db->selectOne("SELECT `id` FROM bp_players WHERE `steamid` = :steamid", array("steamid" => htmlspecialchars($steamid)));
        if($GetAdminID['id'])
            $adminID = intval($GetAdminID['id']);
    }


    $data = array
    (
        "pid" => $adminID,
        "sid" => $serverID,
        "gid" => $groupID
    );

    //If adding new admin, not editing admin
    if(!isset($match['params']['action'], $match['params']['id'])) {

        //Insert into database
        $db->query("INSERT INTO bp_admins (`pid`, `sid`, `gid`, `add_time`) VALUES (:pid, :sid, :gid, NOW())", $data);
        $_SESSION['success'] = UP(ADMIN)." ".SUCCESSFULLY." ".ADDED;

    } else if(isset($match['params']['action'], $match['params']['id']) && $match['params']['action'] == 'edit'){


        $data["aid"] = intval($match['params']['id']);
        $db->query("UPDATE bp_admins SET `pid` = :pid, `sid` = :sid, `gid` = :gid WHERE `aid` = :aid", $data);
        $_SESSION['success'] = UP(YOU)." ".EDITED." ".ADMIN." ".SUCCESSFULLY;

    }

    //Reload player admin rcon command
    CommandToPlayer($Query, $adminID, 'sm_BPreloadadmin "{STEAMID}"');

    header('Location: ' . $CurrentURL);
    exit;

}

?>