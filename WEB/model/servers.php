<?php
    if(!isset($db)) die();

    if(!HasPermission("Access servers page"))
        header('Location: '.WEBSITE);

    //Check if server exists
    if(!$db->selectOne("SELECT * FROM bp_servers WHERE name = :serverName", array("serverName" => htmlspecialchars($match['params']['server'])))) {
        die(SERVER.' '.NOTFOUND);
    }

    $serverName = htmlspecialchars($match['params']['server']);

    function GetTotalOnlineTime($db, $steamid, $serverName)
    {
        $query = $db->selectOne("SELECT SUM(DISTINCT TIMESTAMPDIFF(MINUTE, connected, disconnected)) AS TotalTimeOnline FROM bp_players_online po LEFT JOIN bp_players p ON po.pid = p.id LEFT JOIN bp_servers s ON po.sid = s.id WHERE p.steamid = :steamid AND s.name = :serverName",
            array(
                "steamid"       => htmlspecialchars($steamid),
                "serverName"    => htmlspecialchars($serverName)
            )
        );

        return ($query['TotalTimeOnline']) ? intval($query['TotalTimeOnline']) : 0;
    }

    function CountOnlinePast($db, $serverName, $days)
    {
        if($days > -1) {
            $query = $db->selectOne("SELECT COUNT(DISTINCT(pid)) AS online FROM bp_players_online po LEFT JOIN bp_servers s ON po.sid = s.id WHERE s.name = :serverName AND connected > DATE_SUB(now(), INTERVAL :days DAY)",
                array(
                    "serverName"    => htmlspecialchars($serverName),
                    "days"          => intval($days)
                )
            );
        } else {
            $query = $db->selectOne("SELECT COUNT(DISTINCT(pid)) AS online FROM bp_players_online po LEFT JOIN bp_servers s ON po.sid = s.id WHERE s.name = :serverName",
                array(
                    "serverName" => htmlspecialchars($serverName)
                )
            );
        }

        return ($query['online']) ? intval($query['online']) : 0;
    }


?>