<?php

    if(!isset($db)) die();
    $GetAllFlags    = $db->select("SELECT * FROM bp_flags");
    $GetAllGroups   = $db->select("SELECT *, g.name AS groupName, g.id AS gid FROM bp_admin_groups g ORDER BY immunity DESC");

    //If player wants to edit admin group
    if(isset($match['params']['action'], $match['params']['id']) && !isset($_POST['submit'])) {

        $groupID = intval($match['params']['id']);

        if ($match['params']['action'] == 'edit') {
            $group = $db->selectOne("SELECT *, name AS groupName FROM bp_admin_groups WHERE id = :gid", array("gid" => $groupID));
            if ($group) {

                $_SESSION['warning'] = UP(NOWEDITING) .' '.GROUP;

                $_POST['groupName'] = htmlspecialchars($group['groupName']);
                $_POST['immunity']  = intval($group['immunity']);
                $_POST['flags']     = htmlspecialchars($group['flags']);
                $_POST['radios']    = intval($group['isadmingroup']);

                $length     = $group['usetime'];
                $days       = $length / 1440;
                $hours      = ($length % 1440) / 60;
                $minutes    = ($length % 60);

                $_POST['days']      = round($days, 0, PHP_ROUND_HALF_DOWN);
                $_POST['hours']     = round($hours, 0, PHP_ROUND_HALF_DOWN);
                $_POST['minutes']   = round($minutes, 0, PHP_ROUND_HALF_DOWN);

            } else {

                header('Location: ' . $CurrentURL );
                exit;

            }

        } else if($match['params']['action'] == 'delete') {

            //Delete all admins using this group
            $db->query("DELETE FROM bp_admins WHERE gid = :id", array("id" => $groupID));

            //Delete the group
            $db->query("DELETE FROM bp_admin_groups WHERE id = :id", array("id" => $groupID));
            $_SESSION['success'] = UP(GROUP).' '.DELETED;
            header('Location: ' . $CurrentURL );
            exit;
        }
    }


    //If new ban is added
    if(isset($_POST['groupName'], $_POST['immunity'], $_POST['flags'], $_POST['days'], $_POST['hours'], $_POST['minutes'], $_POST['submit'])) {

        //If player is not entered
        if (empty($_POST['groupName'])) {
            $_SESSION['error'] = UP(PLEASEENTER).' '.GROUP.' '.NAME;
            return;
        }

        $allFlags = implode('', $_POST['flags']);

        //Check if flags are only letters
        if(!ctype_alpha($allFlags)) {
            $_SESSION['error'] = UP(PLEASEENTER).' '.ONLYLETTERS;
            return;
        }

        $groupName  = htmlspecialchars($_POST['groupName']);
        $immunity   = (empty($_POST['immunity'])) ? 0 : intval($_POST['immunity']);
        $days       = (empty($_POST['days'])) ? 0 : intval($_POST['days']);
        $hours      = (empty($_POST['hours'])) ? 0 : intval($_POST['hours']);
        $minutes    = (empty($_POST['minutes'])) ? 0 : intval($_POST['minutes']);
        $usetime    = (($days * 1440) + ($hours * 60) + $minutes);
        $admingroup = (empty($_POST['radios'])) ? 0 : 1;


        $data = array
        (
            "name"      => $groupName,
            "flags"     => $allFlags,
            "immunity"  => $immunity,
            "usetime"   => $usetime,
            "isadmingroup" => $admingroup
        );

        if(!isset($match['params']['action'], $match['params']['id'])) {

            //Check if there isnt already group with this name
            $exists = $db->selectOne("SELECT * FROM bp_admin_groups WHERE name = :groupname", array("groupname" => $groupName));
            if($exists)
            {
                $_SESSION['error'] = UP(NAMEEXISTS);
                return;
            }

            //Insert into database
            $db->query("INSERT INTO bp_admin_groups (`name`, `flags`, `immunity`, `usetime`, `isadmingroup`) VALUES (:name, :flags, :immunity, :usetime, :isadmingroup)", $data);
            $_SESSION['success'] = UP(GROUP).' '.ADDED;

        } else if(isset($match['params']['action'], $match['params']['id']) && $match['params']['action'] == 'edit') {

            //Edit
            $data['id'] = intval($match['params']['id']);
            $db->query("UPDATE bp_admin_groups SET `name` = :name, `flags` = :flags, `immunity` = :immunity, `usetime` = :usetime, `isadmingroup` = :isadmingroup WHERE `id` = :id", $data);
            $_SESSION['success'] = UP(GROUP).' '.EDITED;

        }

        header('Location: ' . $CurrentURL );
        exit;

    }


    ?>