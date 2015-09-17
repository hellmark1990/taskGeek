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
        <li>
            <a href="/index/show">Show numbers from session</a>
        </li>
        <li>
            <a href="/index/load">Load numbers from DB to session</a>
        </li>
        <li>
            <a href="/index/save">Save numbers to DB</a>
        </li>
    </ul>
    <div id="add-number-form-container">
        <h4>Add number to session</h4>
        <form method="post" action="/index/add">
            <input type="number" value="0" name="number">
            <button type="submit">Add</button>
        </form>
    </div>
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

