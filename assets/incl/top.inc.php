<?php

require_once "func.inc.php";
if(!isset($_SESSION["uuid"])){
    header("Location: /");
    exit();
}

if(convertRankToID($currentPlayer->getRank()) < 7){
    header("Location: /logout");
}

$title = "ChestPanel v4";
$nav_home = false;
$nav_users = false;
$nav_logins = false;
$nav_stats = false;
$nav_bans = false;
$nav_content = false;
$nav_account = false;

$phpself = $_SERVER["PHP_SELF"];
if($phpself == "/dashboard/index.php"){
    $title = "Home | ChestPanel v4";
    $nav_home = true;
} else if($phpself == "/content/motd/index.php"){
    $title = "Update MotD | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/badwords/index.php"){
    $title = "Badwords | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/mg-songs/index.php"){
    $title = "Musical Guess: Song Manager | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/mg-songs/add/index.php"){
    $title = "Musical Guess: Song Manager | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/mg-songs/edit/index.php"){
    $title = "Musical Guess: Song Manager | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/mg-songs/delete/index.php"){
    $title = "Musical Guess: Song Manager | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/users/index.php"){
    $title = "Users | ChestPanel v4";
    $nav_users = true;
} else if($phpself == "/stats/index.php"){
    $title = "Statistics | ChestPanel v4";
    $nav_stats = true;
} else if($phpself == "/ban-management/bans/index.php"){
    $title = "Bans | ChestPanel v4";
    $nav_bans = true;
} else if($phpself == "/ban-management/ip-bans/index.php"){
    $title = "Bans | ChestPanel v4";
    $nav_bans = true;
} else if($phpself == "/ban-management/mutes/index.php"){
    $title = "Mutes | ChestPanel v4";
    $nav_bans = true;
} else if($phpself == "/ban-management/kicks/index.php"){
    $title = "Kicks | ChestPanel v4";
    $nav_bans = true;
} else if($phpself == "/ban-management/reports/index.php"){
    $title = "Reports | ChestPanel v4";
    $nav_bans = true;
} else if($phpself == "/logins/index.php"){
    $title = "Logins | ChestPanel v4";
    $nav_logins = true;
} else if($phpself == "/content/achievements/index.php"){
    $title = "Achievements | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/achievements/add/index.php"){
    $title = "Achievements | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/achievements/edit/index.php"){
    $title = "Achievements | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/achievements/delete/index.php"){
    $title = "Achievements | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/bg-words/index.php"){
    $title = "Build &amp; Guess Wordlist | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/nicklist/index.php"){
    $title = "Nicklist | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/fametitles/index.php"){
    $title = "Fame Titles | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/fametitles/add/index.php"){
    $title = "Fame Titles | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/fametitles/edit/index.php"){
    $title = "Fame Titles | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/fametitles/delete/index.php"){
    $title = "Fame Titles | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/maps/index.php"){
    $title = "Map pool | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/maps/edit/index.php"){
    $title = "Map pool | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/maps/statistics/index.php"){
    $title = "Map pool | ChestPanel v4";
    $nav_content = true;
} else if($phpself == "/content/maps/spawnpoints/index.php"){
    $title = "Spawnpoints | ChestPanel v4";
    $nav_content = true;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php print $title; ?></title>

        <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="/vendor/fortawesome/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet"/>
        <link href="/assets/css/jquery-jvectormap-2.0.3.css" type="text/css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <link href="/assets/css/morris.css" type="text/css" rel="stylesheet"/>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/assets/js/jquery.timeago.js"></script>
        <script src="/assets/js/jquery-jvectormap-2.0.3.min.js"></script>
        <script src="/assets/js/jquery-jvectormap-world-mill.js"></script>
        <script src="/assets/js/morris.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery.timeago.settings.allowFuture = true;
                jQuery("time.timeago").timeago();
            });
        </script>
    </head>

    <body>
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
            <div class="container">
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="/">ChestPanel v4</a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item<?php if($nav_home){ ?> active<?php } ?>">
                            <a class="nav-link" href="/">Home<?php if($nav_home){ ?> <span class="sr-only">(current)</span><?php } ?></a>
                        </li>

                        <li class="nav-item<?php if($nav_users){ ?> active<?php } ?>">
                            <a class="nav-link" href="/users/">Users<?php if($nav_users){ ?> <span class="sr-only">(current)</span><?php } ?></a>
                        </li>

                        <?php if(convertRankToID($currentPlayer->getRank()) >= 8){ ?>
                        <li class="nav-item<?php if($nav_logins){ ?> active<?php } ?>">
                            <a class="nav-link" href="/logins/">Logins<?php if($nav_logins){ ?> <span class="sr-only">(current)</span><?php } ?></a>
                        </li>
                        <?php } ?>

                        <li class="nav-item<?php if($nav_stats){ ?> active<?php } ?>">
                            <a class="nav-link" href="/stats/">Statistics<?php if($nav_stats){ ?> <span class="sr-only">(current)</span><?php } ?></a>
                        </li>

                        <li class="nav-item dropdown<?php if($nav_bans){ ?> active<?php } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ban Management</a>
                            <div class="dropdown-menu" aria-labelledby="dropdown">
                                <a class="dropdown-item" href="/ban-management/bans/">Bans</a>
                                <?php if(convertRankToID($currentPlayer->getRank()) >= 8){ ?>
                                    <a class="dropdown-item" href="/ban-management/ip-bans/">IP Bans</a>
                                <?php } ?>
                                <a class="dropdown-item" href="/ban-management/mutes/">Mutes</a>
                                <a class="dropdown-item" href="/ban-management/kicks/">Kicks</a>
                                <a class="dropdown-item" href="/ban-management/reports/">Reports</a>
                            </div>
                        </li>

                        <?php if(convertRankToID($currentPlayer->getRank()) >= 10){ ?>
                        <li class="nav-item dropdown<?php if($nav_content){ ?> active<?php } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Content</a>
                            <div class="dropdown-menu" aria-labelledby="dropdown">
                                <a class="dropdown-item" href="/content/badwords/">Badwords</a>
                                <a class="dropdown-item" href="/content/motd/">MotD</a>
                                <a class="dropdown-item" href="/content/translations/">Translations</a>
                                <a class="dropdown-item" href="/content/mg-songs/">Musical Guess Songs</a>
                                <a class="dropdown-item" href="/content/maps/">Maps</a>
                                <a class="dropdown-item" href="/content/achievements/">Achievements</a>
                                <a class="dropdown-item" href="/content/bg-words/">Build &amp; Guess Wordlist</a>
                                <a class="dropdown-item" href="/content/nicklist/">YouTuber Nicks</a>
                                <a class="dropdown-item" href="/content/urlshortener/">URL Shortener</a>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                    <ul class="nav navbar-nav float-xs-right">
                        <li class="nav-item dropdown<?php if($nav_account){ ?> active<?php } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="https://crafatar.com/avatars/<?php print $currentPlayer->getUUID(); ?>?overlay" width="16" height="16"/> <?php print $currentPlayer->getUsername(); ?></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
                                <a class="dropdown-item" href="/logout">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>