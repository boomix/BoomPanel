<?php

//Pages that are not in navigation
$hidden = array(

    array(
        "name"  => ONLINE.' '.TIME,
        "url"   => "/onlinetime/[i:pid]?/",
        "overrideurl" => "/onlinetime/",
        "target"    => "views/onlinetime.php",
        "method"    => "GET"
    )

);

//Main page navigation
$navigation = array(

    array(
        "name"      => NAV_HOME,
        "url"       => "/",
        "icon"      => "icon-home",
        "method"    => "GET",
        "target"    => "views/home.php",
    ),

    array(
        "name"      => NAV_SETTINGS,
        "url"       => "",
        "icon"      => "icon-cog",
        "method"    => "GET",
        "target"    => "",
        "submenu"   => array (
            array(
                "name"        => NAV_ADD_SERVER,
                "overrideurl" => "/addserver/", //we use override because we have optinional parameters for url
                "url"         => "/addserver/[delete|edit:action]?/[i:id]?/",
                "method"      => "GET|POST",
                "target"      => "views/addserver.php",
                "permissions"  => "Access add server page"
            ),

            array(
                "name"          => NAV_ADDPANELADMINS,
                "overrideurl"   => "/addpaneladmins/",
                "url"           => "/addpaneladmins/[delete|edit:action]?/[i:id]?/",
                "method"        => "GET|POST",
                "target"        => "views/paneladmins.php",
                "permissions"    => "Access add panel admins page"
            )
        )

    ),

    array(
        "name"      => NAV_ADMINS,
        "url"       => "",
        "icon"      => "icon-user-md",
        "method"    => "GET",
        "target"    => "views/servers.php",
        "submenu"   => array (

            array(
                "name"    => NAV_ADDSERVERADMINS,
                "overrideurl" => "/admins/addserveradmins/", //we use override because we have optinional parameters for url
                "url"         => "/admins/addserveradmins/[delete|edit:action]?/[i:id]?/",
                "method"  => "GET|POST",
                "target"  => "views/addserveradmins.php",
                "permissions"    => "Access add server admins page"
            ),
            array(
                "name"    => NAV_ADDSADMINGROUPS,
                "url"     => "/admins/addadmingroups/",
                "overrideurl" => "/admins/addadmingroups/", //we use override because we have optinional parameters for url
                "url"         => "/admins/addadmingroups/[delete|edit:action]?/[i:id]?/",
                "method"  => "GET|POST",
                "target"  => "views/addadmingroups.php",
                "permissions"    => "Access add admin group page"
            ),
            array(
                "name"    => NAV_ADMINONLINETIME,
                "url"     => "/admins/onlinetime/",
                "method"  => "GET",
                "target"  => "views/adminonlinetime.php",
                "permissions"    => "Access admin online time page"
            ),
            array(
                "name"    => NAV_ADMINCOMMANDS,
                "overrideurl" => "/admins/adminlogs/",
                "url"     => "/admins/adminlogs/[page:action]?/[i:id]?/",
                "method"  => "GET",
                "target"  => "views/adminlogs.php",
                "permissions"    => "Access admin logs"
            )

        )
    ),

    array(
        "name"      => BANS,
        "url"       => "",
        "icon"      => "icon-ban-circle",
        "method"    => "GET|POST",
        "target"    => "",
        "submenu"   => array (
            array(
                "name"        => NAV_SERVERBANS,
                "overrideurl" => "/bans/", //we use override because we have optinional parameters for url
                "url"         => "/bans/[delete|edit|unban|page:action]?/[i:id]?/",
                "method"      => "GET|POST",
                "target"      => "views/bans.php",
                "permissions"    => "Access server bans"
            ),

            array(
                "name"    => NAV_COMMUNITYBANS,
                "url"     => "/bans/communitybans/",
                "method"  => "GET|POST",
                "target"  => "views/communitybans.php",
                "permissions"    => "Access community bans"
            )
        )
    ),


    //We use bans.php also for mute gag, because it makes life easier
    array(
        "name"          => NAV_MUTEGAG,
        "overrideurl"   => "/mutegag/",
        "url"           => "/mutegag/[delete|edit|unban|page:action]?/[i:id]?/",
        "icon"          => "icon-volume-off",
        "method"        => "GET|POST",
        "target"        => "views/bans.php",
        "permissions"    => "Access mute gag page"
    ),


    array(
        "name"      => NAV_ANNOUNCEMENTS,
        "url"       => "/announcements/",
        "icon"      => "icon-bell",
        "method"    => "GET|POST",
        "target"    => "views/announcements.php",
        "permissions"    => "Access annoncements page"
    ),

    array(
        "name"      => PLAYERS,
        "url"       => "",
        "icon"      => "icon-user",
        "method"    => "GET",
        "target"    => "",
        "submenu"   => array (

            array(
                "name"        => SEARCH,
                "overrideurl" => "/searchplayers/", //we use override because we have optinional parameters for url
                "url"         => "/searchplayers/[page:action]?/[i:id]?/",
                "method"      => "GET",
                "target"      => "views/searchplayers.php",
                "permissions"    => "Access player search page"
            ),

            array(
                "name"        => NAV_JUST_ONLINE,
                "overrideurl" => "/justonline/", //we use override because we have optinional parameters for url
                "url"         => "/justonline/[page:action]?/[i:id]?/",
                "method"      => "GET",
                "target"      => "views/justonline.php",
                "permissions"    => "Access just online page"
            ),

            array(
                "name"        => NAV_MOSTACTIVE,
                "overrideurl" => "/mostactive/", //we use override because we have optinional parameters for url
                "url"         => "/mostactive/[page:action]?/[i:id]?/",
                "method"      => "GET",
                "target"      => "views/mostactive.php",
                "permissions"    => "Access most active page"
            )

        )
    ),

    array(
        "name"      => NAV_SERVERS,
        "url"       => "", //We leave url empty, because its submenu
        "icon"      => "icon-align-justify",
        "method"    => "GET",
        "target"    => "",
        "submenu"   => array (

            //If name will ocntain {SERVER_NAME} , it will loop thru all servers
            array(
                "name"         => "{SERVER_NAME}",
                "overrideurl"  => "/server/{SERVER_NAME}/", //we use override because we have optinional parameters for url
                "url"          => "/server/[:server]/",
                "method"       => "GET",
                "target"       => "views/servers.php",
                "permissions"    => "Access servers page"
            )

        )
    ),

    array(
        "name"      => NAV_CHAT,
        "url"       => "/chat/",
        "overrideurl" => "/chat/", //we use override because we have optinional parameters for url
        "url"         => "/chat/[page:action]?/[i:id]?/",
        "icon"      => "icon-list-ul",
        "method"    => "GET",
        "target"    => "views/chatsearch.php",
        "permissions"    => "Access chat search"

    ),




);

?>