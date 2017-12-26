<?php

//Start sessions
session_start();
ob_start();
//Check if neccessary data is entered
if(isset($_POST['serverName'], $_POST['commnad']))
{
    //Check if logged in
    include '../config.php';
    if(!isset($_SESSION['steamid']))
        die();

    include '../includes.php';

    $isOwner = ($steamprofile['steamid'] == MAINADMIN) ? true : false;

    //Check if client has access
    $IsAdmin = $db->selectOne("SELECT * FROM bp_panel_admins WHERE steamid = :steamid", array("steamid" => $steamprofile['steamid']));
    if(!$IsAdmin && !$isOwner)
        die();


    $adminID = (!$isOwner) ? intval($IsAdmin['id']) : -1;

    //Get all admin permissions
    if($adminID >= 0) {
        $permissions = $db->select(
            "SELECT `name` FROM bp_panel_admin_permissions ap 
            LEFT JOIN bp_panel_permissions p ON ap.permissionid = p.permissionid 
            WHERE paneladmin = :adminID",
            array("adminID" => $adminID)
        );
    } else {
        //If owner, give him all the permissions
        $permissions = $db->select("SELECT `name` FROM bp_panel_permissions");
    }

    $canSend = false;
    foreach ((array)$permissions as $perms)
    {
        if($perms['name'] == "Can send rcon command")
            $canSend = true;
    }

    if(!$canSend)
        die();

    //Get all data in variables
    $serverName = htmlspecialchars($_POST['serverName']);
    $command    = htmlspecialchars($_POST['commnad']);

    //Get server date - rccon password, ip, port
    $GetServerData = $db->selectOne("SELECT `ip`, `port`, `rcon_pw` FROM bp_servers WHERE name = :serverName", array("serverName" => $serverName));
    if(!$GetServerData)
        die();

    //Try to connect
    try {

        //Execute command
        $Query->Connect($GetServerData['ip'], $GetServerData['port'], 1, $Source );
        $Query->SetRconPassword($GetServerData['rcon_pw']);
        $data = $Query->Rcon($command);

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