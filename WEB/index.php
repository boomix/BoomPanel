<?php

session_start();
ob_start();


//Redirect to url with slash if there is no slash
if( strlen($_SERVER['REQUEST_URI']) > 1 && substr($_SERVER['REQUEST_URI'], -1) != '/' && strpos($_SERVER['REQUEST_URI'], '?') != true) {
     $url = $_SERVER['REQUEST_URI']."/";
     header("Location: $url");
     exit;
}

include 'config.php';
include 'includes.php';

require_once('./class/i18n.php');
I18N::init('boompanel', './lang', 'en_US', array(
    '/^en((-|_).*?)?$/i' => 'en_US', // done
    '/^de((-|_).*?)?$/i' => 'de_DE', // done
    '/^fr((-|_).*?)?$/i' => 'fr_FR', // done
    '/^it((-|_).*?)?$/i' => 'it_IT', // done
    '/^pl((-|_).*?)?$/i' => 'pl_PL', // done
    '/^ru((-|_).*?)?$/i' => 'ru_RU', // done
    '/^tr((-|_).*?)?$/i' => 'tr_TR', // done
    '/^zh((-|_).*?)?$/i' => 'zh_HC', // done
));

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    I18N::changeLanguage(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
} else {
    I18N::changeLanguage(LANGUAGE);
}

//Show errors | disable when live
if(DEVELOPERMOD == 1) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$offset = intval(TIMEZONE);
$timezone_name = timezone_name_from_abbr('', $offset * 3600, FALSE);
date_default_timezone_set($timezone_name);

//Generate basepath
$url        = WEBSITE;
$url        = preg_match("@^https?://@", $url) ? $url : 'http://' . $url;
$url        = parse_url($url);
if(isset($url['path'])) {
    $explode    = explode('/', $url['path']);
    $basepath   = (!empty($explode[1])) ? "/".$explode[1] : '';
} else {
    $basepath 	= '';
}

//Router
$router = new AltoRouter();
$router->setBasePath($basepath);
for ( $i = 0; $i < count( $navigation ); $i ++ ) {

    $router->map( $navigation[$i]['method'], $navigation[$i]['url'], $navigation[$i]['target'], $navigation[$i]['name']);

    if(isset($navigation[$i]['submenu']))
        foreach ($navigation[$i]['submenu'] as $subnavigation)
                $router->map($subnavigation['method'], $subnavigation['url'], $subnavigation['target'], $subnavigation['name']);


}

$match = $router->match();
$result = substr($match['name'], 0, 3);


//Get current full opened router page URL in varianble
$CurrentURL;
for ( $i = 0; $i < count( $navigation ); $i ++ ) {
    if(empty($CurrentURL)) {

        if ($navigation[$i]['name'] == $match['name'])
            $CurrentURL = (isset($navigation[$i]['overrideurl'])) ? $navigation[$i]['overrideurl'] : $navigation[$i]['url'];

        if (isset($navigation[$i]['submenu']))
            foreach ($navigation[$i]['submenu'] as $subnavigation)
                if($subnavigation['name'] == $match['name'])
                    $CurrentURL = (isset($subnavigation['overrideurl'])) ? $subnavigation['overrideurl'] : $subnavigation['url'];
    }
}

if(isset($CurrentURL))
    $CurrentURL = WEBSITE.$CurrentURL;
else
    $CurrentURL = WEBSITE;

require 'partial/header.php';


if($match) {

    $modelInc = str_replace("views/", "model/", $match['target']);
    if(file_exists($modelInc))
        include $modelInc;

    if(file_exists($match['target']))
        require $match['target'];
    else
        require 'views/404.php';
} else {

    require 'views/404.php';

}

require 'partial/footer.php';



?>
