<?php

if(!isset($db)) die();

$results = $db->select("SELECT `name`, `ip`, `port`, COUNT(pid) AS online FROM bp_servers s LEFT JOIN bp_players_online po ON s.id = po.sid");

