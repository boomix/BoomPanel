<?php

    if(!isset($db)) die();

    $CurrentPage = (isset($match['params']['action']) && $match['params']['action'] == 'page') ? $match['params']['id'] : 1;
    $limit = ($CurrentPage == 1) ? ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.','.$CurrentPage * ITEMSPERPAGE;
    $GetAllServers = $db->select("SELECT name, id FROM bp_servers");



    $MainQuery = "FROM bp_chat c
                LEFT JOIN bp_players p ON c.pid = p.id
                LEFT JOIN bp_countries co ON p.country = co.country_code
                LEFT JOIN bp_players_username u ON p.id = u.pid
                LEFT JOIN bp_servers s ON c.sid = s.id
                WHERE type = 1 ";

    $CountAllLogs = 0;
    $searchArray = array();

    if(isset($_GET['search'], $_GET['submit'], $_GET['server']))
    {

        //Remove spaces from front (mistake or whatever)
        $searchText = htmlspecialchars(trim($_GET['search']));
        $_GET['search'] = $searchText;

        //  *** Username search start
        $MainQuery .= "AND (username LIKE :username ";
        $searchArray["username"] = "%".$searchText."%";

        //  *** Check if steam URL / ID entered
        $steamid = getSteamID($searchText);
        $MainQuery .= ($steamid != -1) ? "OR steamid = :steamid " : '';
        if($steamid != -1) { $searchArray["steamid"] = $steamid; }

        //  *** Command
        $MainQuery .= "OR message LIKE :message) ";
        $searchArray["message"] = "%".$searchText."%";

        // *** Check if server submited
        $server = intval($_GET['server']);
        $MainQuery .= ($server > 0) ? "AND sid = :server " : '';
        if($server  > 0) { $searchArray["server"] = $server; }


    }


    //Finish the query
    $CountAllLogs  = $db->selectOne("SELECT COUNT(DISTINCT lid) AS count ".$MainQuery, $searchArray);
    $MaxPages       = ceil($CountAllLogs['count'] / ITEMSPERPAGE);
    $MainQuery .= "GROUP BY lid ORDER BY time DESC LIMIT ".$limit;
    $SearchLogs     = $db->select("SELECT * ".$MainQuery, $searchArray);


    //Debug stuff
    echo_dev( "SELECT * ".$MainQuery);
    echo_dev($searchArray);


?>
