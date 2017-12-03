<?php

//Check if neccessary data is entered
if(isset($_POST['steamid'], $_POST['serverName'], $_POST['reason']))
{
    //Check if logged in
    include '../config.php';
    if(!isset($_SESSION['steamid']))
        die();

    //Check if is admin
    $IsAdmin = $db->selectOne("SELECT * FROM bp_panel_admins WHERE steamid = :steamid", array("steamid" => $_SESSION['steamid']));
    if(!$IsAdmin && $_SESSION['steamid'] != MAINADMIN)
        die();

    //Get all data in variables
    include '../model/global_functions.php';
    $serverName = htmlspecialchars($_POST['serverName']);
    $steamid    = toSteamID($_POST['steamid']);
    $reason     = htmlspecialchars($_POST['reason']);

    //If player is not valid
    if($steamid == -1) {
        header(HEADERERROR);
        die(PLAYER.' '.NOTFOUND);
    }

    //Get server date - rccon password, ip, port
    $GetServerData = $db->selectOne("SELECT `ip`, `port`, `rcon_pw` FROM bp_servers WHERE name = :serverName", array("serverName" => $serverName));
    if(!$GetServerData)
        die();

    //Try to connect
    try {

        //Execute command
        $Query->Connect($GetServerData['ip'], $GetServerData['port'], 1, $Source );
        $Query->SetRconPassword($GetServerData['rcon_pw']);
        $data = $Query->Rcon('sm_kick "#'.$steamid.'" "'.$reason.'"');

        //Check if player was actually kicked
        if(strpos($data, 'basecommands.smx') === FALSE)
            header(HEADERERROR);


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