<?php require_once "assets/incl/func.inc.php";

if(isset($_SESSION["uuid"])){
    header("Location: /dashboard");
    exit();
}

$errorMsg = "";

if(isset($_POST["ign"]) && isset($_POST["password"])){
    $username = $_POST["ign"];
    $password = $_POST["password"];
    $query = "SELECT webinterface_users.mcUUID as wi_uuid, webinterface_users.passwordHash as password, users.username as username FROM users, webinterface_users WHERE users.uuid = webinterface_users.mcUUID AND users.username='" . mysqli_real_escape_string($link, $username) . "'";
    $row = mysqli_fetch_assoc(mysqli_query($link,$query));
    if(isset($row["password"])){
        if(hash("sha512",$password) == $row["password"]){
            $uuid = $row["wi_uuid"];
            $username = $row["username"];
            $player = MinecraftPlayer::getByUUID($uuid);

            if(convertRankToID($player->getRank()) >= 7){
                $_SESSION["uuid"] = $uuid;
                header("Location: /dashboard");
                exit();
            } else {
                $errorMsg = "You are not allowed to login here.";
            }
        } else {
            $errorMsg = "Wrong password.";
        }
    } else {
        $errorMsg = "That account does not exist. Create one with /wiaccess";
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="/vendor/fortawesome/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet"/>

        <style type="text/css">
            body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
            }

            .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
            margin-bottom: 10px;
            }
            .form-signin .checkbox {
            font-weight: normal;
            }
            .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
                    box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
            }
            .form-signin .form-control:focus {
            z-index: 2;
            }
            .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            }
            .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            }

        </style>
    </head>

    <body>
        <div class="container">
            <?php

                if($errorMsg != ""){
                    ?>
            <div class="alert alert-danger" role="alert"><?php print $errorMsg; ?></div>
                    <?php
                }

            ?>

            <form class="form-signin" method="post">
                <h2 class="form-signin-heading">ChestPanel v4</h2>
                <label for="inputEmail" class="sr-only">Minecraft username</label>
                <input type="text" id="ign" name="ign" class="form-control" placeholder="Minecraft username" required autofocus>
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            </form>

        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/assets/js/jquery.timeago.js"></script>
    </body>
</html>