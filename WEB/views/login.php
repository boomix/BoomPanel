<?php if(!isset($db)) die(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>BoomPanel</title><meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?=WEBSITE;?>/css/matrix-login.css" />
    <link href="<?=WEBSITE;?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

</head>
<body>


<div id="loginbox">
    <form id="loginform" class="form-vertical" action="#" style="margin-top: 10rem;margin-bottom: 4rem">
        <div class="control-group normal_text"> <h3><img src="<?=WEBSITE;?>/img/logo2.png" alt="Logo" /></h3></div>

        <div class="form-actions" style="margin-top:0"></div>
        <div class="login-button" style="text-align: center;">
            <a href='?login'><img class='login' src='<?=WEBSITE;?>/img/steamlogin.jpg'></a>
        </div>

    </form>

</div>

</body>

</html>
