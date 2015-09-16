<?php use app\components\App; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>page title - your site name</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="en">
    <link rel="stylesheet" href="/public/assets/css/style.css" type="text/css" media="all">
</head>

<body>

<div id="header">
    <div id="logo">
        <a href="/">
          <img src="/public/assets/images/logo.png">
        </a>
    </div>

    <ul id="top-menu">
        <?php if (!App::create()->session->isLoggedIn()) { ?>
            <li><a href="/register">Register</a></li>
            <li><a href="/login">Login</a></li>
        <?php } else { ?>
            <li><a href="/logout">Logout</a></li>
            <li><a href="/profile">Profile</a></li>
        <?php } ?>
    </ul>
</div>


<div id="content">

    <div class="content-container">
        <?= $this->layoutContent ?>
    </div>

</div>


<div id="footer">
</div>

</body>
</html>

