<?php

//If client is not logged in, show him the login page
if(!isset($_SESSION['steamid'])) {
    include 'views/login.php';
    die();
}

include ('steamauth/userInfo.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BoomPanel</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/colorpicker.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/datepicker.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/uniform.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/select2.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/matrix-style.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/matrix-media.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/noty.css" >
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/jquery.gritter.css" />

    <meta name="theme-color" content="#2E363F">
    <link rel="shortcut icon" type="image/ico" href="<?=WEBSITE;?>/img/favicon.ico"/>
    <link href="<?=WEBSITE;?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>

<!--Header-part-->
<div id="header">
    <h1><a href="dashboard.html">BoomPanel</a></h1>
</div>
<!--close-Header-part-->


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse" style="left: unset;right: 0;">
    <ul class="nav">
        <li style="float:right;"><a href="?logout=1"><i class="icon icon-share-alt"></i> <span class="text"><?=UP(LOGOUT);?></span></a></li>
        <li style="float:right;"><a href="#"><i class="icon icon-user"></i> <span class="text"><?=_("Welcome");?> <?=htmlspecialchars($steamprofile['personaname']);?></span></a></li>
    </ul>
</div>
<!--close-top-Header-menu-->

<?php

    //Check if client has access
    $IsAdmin = $db->selectOne("SELECT * FROM bp_panel_admins WHERE steamid = :steamid", array("steamid" => $steamprofile['steamid']));
    if(!$IsAdmin && $steamprofile['steamid'] != MAINADMIN)
        die("<h1 style='margin-top: 2rem;color:#fff;text-align: center'>"._("You dont have access to this page!")."</h1>");

?>

<!--sidebar-menu-->

        <!-- Get all menu items -->
        <?php for ( $i = 0; $i < count( $navigation ); $i ++ ) {


            $currentPage = $match['name'];
            $submenu = (isset($navigation[$i]['submenu'])) ? "submenu" : "";

            //Check if submenu needs to be opened up
            if(!empty($submenu))
                foreach ($navigation[$i]['submenu'] as $subnavigation)
                    if($subnavigation['name'] == $match['name'])
                        $submenu .= " open";


            $class  = ($navigation[$i]['name'] == $match['name']) ? 'class="active '.$submenu.'"' : 'class="'.$submenu.'"';
            $url    = (isset($navigation[$i]['overrideurl'])) ? $navigation[$i]['overrideurl'] : $navigation[$i]['url'];

            if($i == 0) { ?>
                <nav><div id="sidebar" style=""><a href="#" class="visible-phone"><i class="icon icon-home"></i> <?=UP($match['name']);?></a><ul>
            <?php } ?>


            <li <?=$class;?>>
                <a href="<?=WEBSITE.$url;?>">
                    <i class="icon <?=$navigation[$i]['icon'];?>"></i>
                    <span class="navitem"><?=UP($navigation[$i]['name']);?></span>
                </a>

                <?php
                if(!empty($submenu)) {

                    $class3 = "";
                    if(!empty($submenu))
                        foreach ($navigation[$i]['submenu'] as $subnavigation)
                            if($subnavigation['name'] == $match['name'])
                                $class3 = 'style="display:block"';

                    echo '<ul '.$class3.'>';
                    foreach ($navigation[$i]['submenu'] as $subnavigation)
                    {
                        $class2 = ($subnavigation['name'] == $match['name']) ? 'class="active"' : '';
                        $url = (isset($subnavigation['overrideurl'])) ? $subnavigation['overrideurl'] : $subnavigation['url'];

                        if(strpos($url, "{SERVER_NAME}") !== false){

                                //foreach all servers
                                $results = $db->select("SELECT `name`, `id` FROM bp_servers");
                                foreach ($results as $result) {

                                    if($result['id'] != 0)
                                    {
                                        $class2 = (isset($match['params']['server']) && $result['name'] == $match['params']['server']) ? 'class="active"' : '';
                                        $newurl = str_replace("{SERVER_NAME}", $result['name'], $url);

                                        if(isset($match['params']['server']) && $result['name'] == $match['params']['server']) {
                                            $serverName = str_replace("{SERVER_NAME}", $result['name'], $currentPage);
                                        }


                                        echo ' <li '.$class2.'><a href="'.WEBSITE.$newurl.'" class="navlink">'.UP($result['name']).'</a></li>';
                                    }

                                }

                        } else {

                            echo ' <li ' . $class2 . '><a class="navlink" href="' . WEBSITE . $url . '">' . UP($subnavigation['name']) . '</a></li>';

                        }
                    }
                    echo '</ul>';
                }
                ?>

            </li>
        <?php } ?>



    </ul>
</div></nav>


<div id="content">

    <div id="content-header">
        <?php $title = (isset($serverName)) ? UP($serverName) : UP($currentPage); ?>
        <div id="breadcrumb"> <a href="<?=WEBSITE;?>"><?= _("Home");?></a> <a href="<?=$CurrentURL;?>"><?=$title;?></a></div>
        <h1><?=$title;?></h1>
    </div>
