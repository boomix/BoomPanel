<?php

$pid = intval($match['params']['pid']);

//Get username
$GetUsername = $db->selectOne("SELECT `username` FROM bp_players_username WHERE pid = :pid ORDER BY last_used DESC LIMIT 1", array("pid" => $pid));
$username = ($GetUsername['username']) ?: PLAYER.' '.NOTFOUND;

    //Check if is in DB
$data = $db->select("SELECT IF(online > 0 AND s.id = online, 0, TIMESTAMPDIFF(MINUTE, MAX(disconnected), NOW())) AS `last_online`, `name`, SUM(TIMESTAMPDIFF(MINUTE, connected, disconnected)) AS `sum`, `con` FROM bp_players_online o 
LEFT JOIN bp_servers s ON s.id = o.sid 
LEFT JOIN (SELECT `username`, `pid` FROM bp_players_username WHERE active = 1 GROUP BY pid) u ON u.pid = o.pid 
LEFT JOIN bp_players pl ON pl.id = o.pid
LEFT JOIN (SELECT `pid`, `sid`, COUNT(connected) `con` FROM bp_players_online GROUP BY pid, sid) c ON c.pid = o.pid AND c.sid = o.sid WHERE o.pid = :pid GROUP BY o.sid",
    array("pid" => $pid));

