<?php
    if(!isset($db)) die();
    //Default queries
    $GetAllPanelAdmins = $db->select("SELECT * FROM bp_panel_admins ORDER BY id DESC");


    //Adding new panel admin
    if(isset($_POST['admin'], $_POST['submit']))
    {

        $steamid = getSteamID($_POST['admin']);
        if ($steamid == -1)
        {
            $_SESSION['error'] = ADMIN.' '.NOTFOUND;
            return;
        }

        if($steamid == MAINADMIN)
            return;

        $db->query("INSERT INTO bp_panel_admins (steamid) VALUES (:steamid)", array("steamid" => $steamid));

        $_SESSION['success'] = PANELADMINADDED;

        header('Location: ' . $CurrentURL );
        exit;

    }


    //Deleting panel admin
    if(isset($match['params']['action'], $match['params']['id'])) {

        echo $match['params']['action'];

        if($match['params']['action'] == 'delete')
        {
            $id = intval($match['params']['id']);
            echo $id;

            $db->query("DELETE FROM bp_panel_admins WHERE id = :id", array("id" => $id));
            $_SESSION['success'] = PANELADMINDELETED;

            header('Location: ' . $CurrentURL );
            exit;

        }


    }

