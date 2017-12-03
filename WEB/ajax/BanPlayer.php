<?php

//Start sessions
session_start();
ob_start();

//Check if neccessary data is entered
if(isset($_POST['steamid'], $_POST['serverName'], $_POST['reason']))
{
    //Check if logged in
    include '../config.php';
    if(!isset($_SESSION['steamid']))
        die(ERROR);

    include '../includes.php';

    //Check if is admin
    $IsAdmin = $db->selectOne("SELECT * FROM bp_panel_admins WHERE steamid = :steamid", array("steamid" => $_SESSION['steamid']));
    if(!$IsAdmin && $_SESSION['steamid'] != MAINADMIN)
        die(ERROR.' 2');

    //Get all data in variables
    $serverName = htmlspecialchars($_POST['serverName']);
    $steamid    = toSteamID($_POST['steamid']);
    $reason     = htmlspecialchars($_POST['reason']);

    //If player is not valid
    if($steamid == -1) {
        header(HEADERERROR);
        die(PLAYER.' '.NOTFOUND.' 1');
    }

    //Get server data - rcon password, ip, port, id
    $GetServerData = $db->selectOne("SELECT `ip`, `port`, `rcon_pw`, `id` FROM bp_servers WHERE name = :serverName", array("serverName" => $serverName));
    if(!$GetServerData['id']) {
        header(HEADERERROR);
        die(SERVER . ' ' . NOTFOUND);
    }

    //Get user ID
    $playerID = -1;
    $GetPlayerID = $db->selectOne("SELECT `id` FROM bp_players WHERE `steamid` = :steamid", array("steamid" => htmlspecialchars($_POST['steamid'])));
    if($GetPlayerID['id'])
        $playerID = intval($GetPlayerID['id']);

    if($playerID == -1) {
        header(HEADERERROR);
        die(PLAYER . ' ' . NOTFOUND . ' 2');
    }

    //Get admin ID
    $GetAdminData = $db->selectOne("SELECT `id`, `username` FROM bp_players p LEFT JOIN bp_players_username u ON p.id = u.pid WHERE `steamid` = :steamid GROUP BY `id` ORDER BY last_used DESC LIMIT 1",
        array("steamid" => htmlspecialchars($_SESSION['steamid'])));
    if(!$GetAdminData['id']) {
        header(HEADERERROR);
        die(ADMIN . ' ' . NOTFOUND);
    }


    $adminID        = $GetAdminData['id'];
    $adminUsername  = $GetAdminData['username'];

    //Length
    $days       = (empty($_POST['days'])) ? 0 : intval($_POST['days']);
    $hours      = (empty($_POST['hours'])) ? 0 : intval($_POST['hours']);
    $minutes    = (empty($_POST['minutes'])) ? 0 : intval($_POST['minutes']);
    $length     = intval(($days * 1440) + ($hours * 60) + $minutes);

    //Add ban in database
    $data = array
    (
        "pid" => intval($playerID),
        "sid" => intval($GetServerData['id']),
        "aid" => intval($adminID),
        "reason" => $reason,
        "length" => $length
    );
    $db->query("INSERT INTO bp_bans (`pid`, `sid`, `aid`, `reason`, `length`) VALUES (:pid, :sid, :aid, :reason, :length)", $data);


    //Kick client from server
    try {

        //Execute command
        $Query->Connect($GetServerData['ip'], $GetServerData['port'], 1, $Source );
        $Query->SetRconPassword($GetServerData['rcon_pw']);
        $data = $Query->Rcon('sm_BPbankick "'.$steamid.'" "'.$adminUsername.'" "'.$reason.'" "'.$length.'"');

    }
    catch( Exception $e ) {

        //Error connecting to server
        header(HEADERERROR);
        die( UP(SERVER_ERROR).$e->getMessage() );

    } finally {

        $Query->Disconnect( );
        if(empty($json)) {
            die( $data );
        }

    }

}


?>