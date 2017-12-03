<?php
    include 'config.php';

    //Some other configs
    define("TIMEFORMAT", "Y-m-d H:i");
    define("ITEMSPERPAGE", 25);
    define("ONLINELASTMINUTES", 10);
    define("HEADERERROR", "HTTP/1.1 500 Internal Server Booboo");
    define("UNKNOWN_COUNTRY", "HZ");

    //Require
    include 'class/DataBase.php';
    include 'class/AltoRouter.php';
    require 'steamauth/steamauth.php';
    require 'class/SourceQuery/bootstrap.php';
    include 'lang/'.LANGUAGE.'.php';
    include 'model/navigation.php';

    $db = new DataBase();
    use xPaw\SourceQuery\SourceQuery;
    $Query = new SourceQuery();
    $Source = SourceQuery::SOURCE;

    include 'model/global_functions.php';
?>