<?php

    ini_set('allow_url_fopen',1);

    //Some other configs
    define("TIMEFORMAT", "Y-m-d H:i");
    define("ITEMSPERPAGE", 25);
    define("ONLINELASTMINUTES", 10);
    define("HEADERERROR", "HTTP/1.1 500 Internal Server Booboo");
    define("UNKNOWN_COUNTRY", "HZ");
    define("DEFAULT_MAP", "http://i.imgur.com/O0wBACS.jpg");

    //Require
    include 'class/DataBase.php';
    include 'class/AltoRouter.php';
    require 'steamauth/steamauth.php';
    require 'class/SourceQuery/bootstrap.php';
    include 'lang/'.LANGUAGE.'.php';
    include 'lang/default.lang.php';
    include 'model/permissions.php';
    include 'model/navigation.php';

    require 'class/cache.class.php';

    $c = new Cache();

    $db = new DataBase();
    use xPaw\SourceQuery\SourceQuery;
    $Query = new SourceQuery();
    $Source = SourceQuery::SOURCE;

    include 'model/global_functions.php';
?>