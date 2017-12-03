<?php

    $GetAllServers  = $db->select("SELECT * FROM bp_servers ORDER BY id ASC");


    $ItemsToSelect = "`message`, `time`, `name`, `steamid`, `country_name`, `username`";
    $defaultQuery = "SELECT ".$ItemsToSelect." FROM bp_chat c 
            LEFT JOIN bp_servers s ON c.sid = s.id
            LEFT JOIN bp_players p ON c.pid = p.id 
            LEFT JOIN bp_countries cnt ON p.country = cnt.country_code 
            LEFT JOIN (SELECT `username`, `pid` FROM bp_players_username GROUP BY pid ORDER BY last_used DESC) u ON u.pid = c.pid ";

    $defaultQueryEnd = "ORDER BY time DESC LIMIT ";

    //Pagination
    $CurrentPage = (isset($match['params']['action']) && $match['params']['action'] == 'page') ? $match['params']['id'] : 1;
    $limit = ($CurrentPage == 1) ? ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.','.ITEMSPERPAGE;
    $defaultQueryEnd .= $limit;

    //If player is not searching anything, show latest chat messages
    if(!isset($_GET['submit'])) {

        $ChatSearch         = $db->select($defaultQuery.'WHERE type = 0 '.$defaultQueryEnd);
        echo_dev($defaultQuery.'WHERE type = 0 '.$defaultQueryEnd);
        $CountChatSearch    = $db->selectOne(str_replace($ItemsToSelect, "COUNT(DISTINCT lid) AS count", explode("LIMIT", $defaultQuery.'WHERE type = 0 '.$defaultQueryEnd)[0]));
        $MaxPages           = ceil($CountChatSearch['count'] / ITEMSPERPAGE);

    } else {

        $data   = array();$searchQuery = "WHERE type = 0 AND ";

        if(!empty($_GET['server']) && $_GET['server'] > 0) {
            $data['server'] = intval($_GET['server']);
            $searchQuery   .= " sid = :server AND ";
        }

        if(!empty($_GET['date']) && $_GET['date'] > 0) {
            $data['date'] = intval($_GET['date']);
            $searchQuery   .= " TIMESTAMPDIFF(MINUTE, time, NOW()) < :date AND ";
        }

        if(!empty($_GET['search'])) {

            //Add parameters
            $data['search']     = "%".htmlspecialchars($_GET['search'])."%";
            $data['search2']    = htmlspecialchars($_GET['search']);

            //  *** Check if steam URL / ID entered
            $steamid = getSteamID($_GET['search']);
            $searchQuery .= ($steamid != -1) ? " (steamid = :steamid OR " : "";
            if($steamid != -1) $data['steamid'] = $steamid;

            // *** Check if thats an username
            $searchQuery .= (($steamid == -1) ? '(' : '')."username LIKE :search OR username = :search2 ";

            //If message entered
            $searchQuery .= " OR message LIKE :search OR message = :search2) ";

        } else {

            $searchQuery .= " message LIKE '%%' ";

        }

        $FullQuery          = $defaultQuery.$searchQuery.$defaultQueryEnd;
        $ChatSearch         = $db->select($FullQuery, $data);
        $CountChatSearch    = $db->selectOne(str_replace($ItemsToSelect, "COUNT(DISTINCT lid) AS count", explode("LIMIT", $FullQuery)[0]), $data);
        $MaxPages           = ceil($CountChatSearch['count'] / ITEMSPERPAGE);

        if(DEVELOPERMOD == 1) {
            echo_dev($defaultQuery.$searchQuery.$defaultQueryEnd);
            echo_dev($data);
        }
    }


?>