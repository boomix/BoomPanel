<?php

if(!isset($db)) die();

$results = $db->select("SELECT `name`, `ip`, `port`, COUNT(pid) AS online FROM bp_servers s LEFT JOIN bp_players_online po ON s.id = po.sid");

$GetAllServers = $db->select("SELECT name, s.id, COUNT(p.steamid) `onlinePlayers` FROM bp_servers s LEFT JOIN bp_players p ON p.online = s.id WHERE s.id != 0 GROUP BY s.id");

function CountOnlinePast($db, $days)
{
    if($days > -1) {
        $query = $db->selectOne("SELECT COUNT(DISTINCT(pid)) AS online FROM bp_players_online WHERE connected > DATE_SUB(now(), INTERVAL :days DAY)",
            array(
                "days"          => intval($days)
            )
        );
    } else {
        $query = $db->selectOne("SELECT COUNT(DISTINCT(pid)) AS online FROM bp_players_online");
    }

    return ($query['online']) ? intval($query['online']) : 0;
}

function CountOnlineNow($db)
{
    $query = $db->selectOne("SELECT COUNT(steamid) `online` FROM bp_players WHERE online > 0");
    return ($query['online']) ? intval($query['online']) : 0;
}

function CountOnlinePastInServer($db, $serverName, $days)
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