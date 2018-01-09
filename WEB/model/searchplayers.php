<?php
    if(!isset($db)) die();

    if(!HasPermission("Access player search page"))
        header('Location: '.WEBSITE);

    //Pagination
    $CurrentPage = (isset($match['params']['action']) && $match['params']['action'] == 'page') ? $match['params']['id'] : 1;
    $limit = ($CurrentPage == 1) ? ITEMSPERPAGE : ($CurrentPage - 1) * ITEMSPERPAGE.','.ITEMSPERPAGE;

    if(isset($_GET['search'], $_GET['submit']))
    {

        //Remove spaces from front (mistake or whatever)
        $searchText = htmlspecialchars(trim($_GET['search']));
        $_GET['search'] = $searchText;


        //Get all possible types what player could have searched for or by what

        //  *** Username search start
        $searchQuery = "WHERE username LIKE :username";
        $searchArray["username"]= "%".$searchText."%";

        //  *** Check if steam URL / ID entered
        $steamid = getSteamID($searchText);
        $searchQuery .= ($steamid != -1) ? " OR steamid = :steamid" : '';
        if($steamid != -1) { $searchArray["steamid"] = $steamid; }


        //  *** Check if IP is entered
        $bValid = filter_var($searchText, FILTER_VALIDATE_IP);
        $searchQuery .= ($bValid == true) ? " OR ip LIKE :ip" : "";
        if($bValid == true) { $searchArray["ip"] = $searchText; }



        $LeftJoin = "FROM bp_players p 
                  LEFT JOIN (SELECT `pid`, `ip` FROM bp_players_ip WHERE active = 1 GROUP BY pid) pip ON p.id = pip.pid 
                  LEFT JOIN (SELECT `pid`, `username` FROM bp_players_username WHERE active = 1 GROUP BY pid) purn ON p.id = purn.pid 
                  LEFT JOIN (SELECT SUM(connections) AS `connections2`, `pid` FROM bp_players_ip GROUP BY pid) pc ON p.id = pc.pid
                  LEFT JOIN bp_players_online pol ON p.id = pol.pid
                  LEFT JOIN bp_countries bctr ON p.country = bctr.country_code ".$searchQuery;

        //Run search
        $SearchUsers    = $db->select("SELECT *, `online`, SUM(TIMESTAMPDIFF(MINUTE, connected, disconnected)) AS timeonline, MAX(pol.connected) AS last_online, MIN(pol.connected) AS first_online ".$LeftJoin." GROUP BY steamid, p.id DESC LIMIT ".$limit, $searchArray);
        $CountAllUsers  = $db->selectOne("SELECT COUNT(DISTINCT p.id) AS count ".$LeftJoin, $searchArray);
        $MaxPages       = ceil($CountAllUsers['count'] / ITEMSPERPAGE);

        echo_dev ("SELECT *, SUM(TIMESTAMPDIFF(MINUTE, connected, disconnected)) AS timeonline, MAX(pol.connected) AS last_online, MIN(pol.connected) AS first_online ".$LeftJoin." GROUP BY steamid, p.id DESC LIMIT ".$limit);
        echo_dev($searchArray);


    }


?>