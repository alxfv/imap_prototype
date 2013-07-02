<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/css/styles.css"/>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css"/>

    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>


<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#">Imap Prototype</a>
            <div class="nav-collapse collapse">
                <p class="navbar-text pull-right">
                    Logged in as <a href="#" class="navbar-link"><?=$username?></a>
                </p>
                <ul class="nav">
                    <li class="active"><a href="#"><?=$totalTime?> sec.</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Switch user <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($prms['hosts'] as $key => $host) : ?>
                                <?php if ($key) : ?>
                                    <li class="divider"></li>
                                <?php endif; ?>
                                <?php foreach ($host['users'] as $user) : ?>
                                    <li>
                                        <a href="/?username=<?=$user['name']?>&password=<?=base64_encode($user['password'])?>&host=<?=$host['host']?>&port=<?=$host['port']?>">
                                            <?=$user['name']?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>

            </div>

        </div>
    </div>
</div>

<div class="container-fluid">
