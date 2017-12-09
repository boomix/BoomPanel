<?php

if(!isset($db)) die();

$results = $db->select("SELECT `name`, `ip`, `port`, COUNT(pid) AS online FROM bp_servers s LEFT JOIN bp_players_online po ON s.id = po.sid");

$CountOnlineNow = $db->selectOne("SELECT");


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
    $query = $db->selectOne("SELECT COUNT(pid) AS `online` FROM bp_players_online WHERE connected = disconnected");
    return ($query['online']) ? intval($query['online']) : 0;
}