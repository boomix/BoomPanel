<?php

    if(!isset($db)) die();

    if(!HasPermission("Access just online page"))
        header('Location: '.WEBSITE);

    $FindAllUsers = $db->select("
            SELECT `bid`, `steamid`, `country_name`, `username`, `name`,  
            (TIMESTAMPDIFF(MINUTE, DATE_SUB(now(), INTERVAL ".ONLINELASTMINUTES." MINUTE), disconnected) + 1) difference,
            (TIMESTAMPDIFF(MINUTE, connected, disconnected)) timeonline
            FROM bp_players_online o LEFT 
            JOIN bp_players p ON o.pid = p.id LEFT 
            JOIN bp_countries c ON c.country_code = p.country LEFT 
            JOIN bp_players_username u ON u.pid = o.pid LEFT
            JOIN bp_servers s ON o.sid = s.id LEFT
            JOIN bp_bans b ON b.pid = o.pid AND (b.sid = o.sid OR b.sid = 0) AND unbanned = 0 AND ((TIMESTAMPDIFF(MINUTE, b.time, now()) < b.length) OR b.length = 0)
            WHERE disconnected > DATE_SUB(now(), INTERVAL ".ONLINELASTMINUTES." MINUTE) 
            AND connected != disconnected
            GROUP BY o.pid, o.sid 
            ORDER BY difference DESC
     ");

?>