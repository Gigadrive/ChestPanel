<?php require_once "../assets/incl/top.inc.php";

if(convertRankToID($currentPlayer->getRank()) < 8){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

if(!isset($_GET["id"]) || (isset($_GET["id"]) && empty($_GET["id"]))){
    echo "Invalid ID.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

$id = $_GET["id"];

$sql = mysqli_query($link,"SELECT c.*,u.username,u.rank FROM chatlogs AS c INNER JOIN users AS u ON c.uuid = u.uuid WHERE c.id = '" . mysqli_real_escape_string($link,$id) . "'");
if(mysqli_num_rows($sql) == 0){
    echo "Invalid ID.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

$data = mysqli_fetch_array($sql);
$uuid = $data["uuid"];
$rank = $data["rank"];
$username = $data["username"];
$messages = json_decode($data["messages"],true);
$time = $data["time"];

?>
<div class="container">
    <h1>Chatlog of <?php print $username; ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <table class="table my-0">
                    <tr>
                        <td><b>Username</b></td>
                        <td><a href="/profile/?uuid=<?php print $uuid; ?>"><?php print $username; ?></a></td>
                    </tr>
                    <tr>
                        <td><b>UUID</b></td>
                        <td><?php print $uuid; ?></td>
                    </tr>
                    <tr>
                        <td><b>&nbsp;</b></td>
                        <td><?php print str_replace("-","",$uuid); ?></td>
                    </tr>
                    <tr>
                        <td><b>Rank</b></td>
                        <td><span style="color: <?php print getRankColor($rank); ?>"><?php print getRankName($rank); ?></span></td>
                    </tr>
                    <tr>
                        <td><b>Time created</b></td>
                        <td><?php print timeago($time); ?></td>
                    </tr>
                    <tr>
                        <td><b>Created By</b></td>
                        <td><a href="/profile/?uuid=<?php print $data["createdBy"]; ?>"><?php print MinecraftPlayer::getByUUID($data["createdBy"])->getUsername(); ?></a></td>
                    </tr>
                    <tr>
                        <td><b>Amount of Messages</b></td>
                        <td><?php print count($messages); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-7">
            <?php

            foreach($messages as $m){
                ?>
            <div class="card">
                <div class="card-block">
                    <?php print $m["message"]; ?>
                </div>

                <div class="card-footer">
                    <i class="fa fa-server"></i> <?php print $m["server"]; ?> | <i class="fa fa-clock-o"></i> <?php print convertTime("H:i:s",$m["time"]); ?>
                </div>
            </div>
                <?php
            }

            ?>
        </div>
    </div>
</div>
<?php require_once "../assets/incl/bottom.inc.php"; ?>