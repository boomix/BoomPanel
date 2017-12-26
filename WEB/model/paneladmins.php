<?php
    if(!isset($db)) die();

    if(!HasPermission("Access add panel admins page"))
        header('Location: '.WEBSITE);

    //Default queries
    $GetAllPanelAdmins = $db->select("SELECT * FROM bp_panel_admins ORDER BY id DESC");
    $GetAllServers = $db->select("SELECT * FROM bp_servers");
    $GetAllPermissions = $db->select("SELECT * FROM bp_panel_permissions");


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

        if($match['params']['action'] != 'edit') {
            $db->query("INSERT INTO bp_panel_admins (steamid) VALUES (:steamid)", array("steamid" => $steamid));
            $_SESSION['success'] = PANELADMINADDED;
        } else {

            //Delete all pervius permissions
            $adminID = intval($match['params']['id']);
            $db->query("DELETE FROM bp_panel_admin_permissions WHERE paneladmin = :paneladmin", array("paneladmin" => $adminID));

            $insertPerms = "INSERT INTO bp_panel_admin_permissions (paneladmin, permissionid) VALUES ";
            foreach ($_POST['checkboxes'] as $perms)
                $insertPerms .= "(".$adminID.", ".$perms."),";

            $insertPerms = rtrim($insertPerms,",");
            $db->query($insertPerms);

            $_SESSION['success'] = _("Panel admin edited!");

        }



        header('Location: ' . $CurrentURL );
        exit;

    }


    //Deleting panel admin
    if(isset($match['params']['action'], $match['params']['id'])) {

        //echo $match['params']['action'];

        if($match['params']['action'] == 'edit')
        {
            $_SESSION['warning'] = _("You are now editing panel admin!");
            $admin = $db->selectOne("SELECT * FROM bp_panel_admins WHERE id = :id", array("id" => intval($match['params']['id'])));
            $permissions = $db->select("SELECT ap.permissionid `permid` FROM bp_panel_admin_permissions ap 
            LEFT JOIN bp_panel_permissions p ON ap.permissionid = p.permissionid 
            WHERE paneladmin = :adminid", array("adminid" => intval($admin['id'])));

            $_POST['admin'] = $admin['steamid'];
            foreach ((array)$permissions as $permission)
                $_POST['checkboxes'][$permission['permid']] = $permission['permid'];
        }


        if($match['params']['action'] == 'delete')
        {
            $id = intval($match['params']['id']);

            $db->query("DELETE FROM bp_panel_admins WHERE id = :id", array("id" => $id));
            $_SESSION['success'] = PANELADMINDELETED;

            header('Location: ' . $CurrentURL );
            exit;

        }


    }

