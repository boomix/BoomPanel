<?php
    if(!isset($db)) die();
    $GetAllServers = $db->select("SELECT * FROM bp_servers WHERE id != 0");


    //Wants to edit server
    if(isset($match['params']['action'], $match['params']['id']) && !isset($_POST['submit']))
    {
        //Get server data
        if($match['params']['action'] == 'edit') {
            $server = $db->selectOne("SELECT * FROM bp_servers WHERE id = :id", array("id" => intval($match['params']['id'])));
            if ($server) {
                $_SESSION['warning'] = "Your now editing server";

                $_POST['serverName']    = $server['name'];
                $_POST['serverIP']      = $server['ip'];
                $_POST['serverPort']    = $server['port'];
                $_POST['rconPassword']  = $server['rcon_pw'];

            } else {

                //Redirect to current URL
                header('Location: ' . $CurrentURL);

            }
        }

        //Wants to delete server
        else if($match['params']['action'] == 'delete')
        {



        }

    }



    //Submits new server
    if(isset($_POST['serverName'], $_POST['serverIP'], $_POST['serverPort'], $_POST['rconPassword'], $_POST['submit']))
    {

        //Check if all data is entered
        if(empty($_POST['serverName']) || empty($_POST['serverIP']) || empty($_POST['serverPort']) || empty($_POST['rconPassword']) )
        {
            $_SESSION['error'] = "Please fill all field!";
            return;
        }

        //Check if name doesnt contain symbols
        if(!preg_match("/^[a-zA-Z0-9-]+$/", $_POST['serverName']))
        {
            $_SESSION['error'] = "Please use only letters and numbers for server name!";
            return;
        }

        $serverIP = gethostbyname(htmlspecialchars($_POST['serverIP']));

        //Check if it is IP adress
        if (!filter_var($serverIP, FILTER_VALIDATE_IP)) {
            $_SESSION['error'] = "This is not valid IP adress!";
            return;
        }

        $data = array
        (
            "serverName"    => htmlspecialchars($_POST['serverName']),
            "serverIP"      => $serverIP,
            "serverPort"    => intval($_POST['serverPort']),
            "rconPassword"  => $_POST['rconPassword'],
        );

        if(!isset($match['params']['action'], $match['params']['id'])) {

            //Check if server with this IP isnt already added
            unset($data['rconPassword']);
            $created = $db->selectOne("SELECT * FROM `bp_servers` WHERE ip = :serverIP AND port = :serverPort OR name = :serverName", $data);

            if ($created) {
                $_SESSION['error'] = "Server IP or server name already exists! Please use different one!";
                return;
            }

            //Everything is okey, lets add to database
            $data['rconPassword'] = $_POST['rconPassword'];
            $db->query("INSERT INTO `bp_servers` (`name`, `ip`, `port`, `rcon_pw`) VALUES (:serverName, :serverIP, :serverPort, :rconPassword)", $data);
            $_SESSION['success'] = "You have added new server!";

        } else if(isset($match['params']['action'], $match['params']['id'])){

            if($match['params']['action'] == 'edit')
            {

                //Everything is okey, lets update
                $data['id'] = intval($match['params']['id']);
                $db->query("UPDATE bp_servers SET `name` = :serverName, `ip` = :serverIP, `port` = :serverPort, `rcon_pw` = :rconPassword WHERE id = :id", $data);
                $_SESSION['success'] = "You have successfully updated server!";

            }

        }

        //Redirect to current URL, to reload page
        header('Location: ' . $CurrentURL );
        exit;


    }


?>