<?php require_once "../assets/incl/top.inc.php";

if(!isset($_GET["uuid"]) || (isset($_GET["uuid"]) && empty($_GET["uuid"]))){
    echo "Invalid UUID.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

$uuid = $_GET["uuid"];

$successMsg = null;
$errorMsg = null;

if(isset($_POST["coinsToGive"])){
    $coinsToGive = $_POST["coinsToGive"];

    if($coinsToGive > 0){
        $s = mysqli_query($link,"UPDATE `users` SET `coins`=`coins`+" . $coinsToGive . " WHERE `uuid` = '" . mysqli_real_escape_string($link,$uuid) . "'");
        if($s){
            $successMsg = "Coins given!";
        } else {
            $errorMsg = mysqli_error($link);
        }
    } else {
        $errorMsg = "Value must be greater than 0!";
    }
}

if(isset($_POST["keysToGive"])){
    $keysToGive = $_POST["keysToGive"];

    if($keysToGive > 0){
        $s = mysqli_query($link,"UPDATE `users` SET `keys`=`keys`+" . $keysToGive . " WHERE `uuid` = '" . mysqli_real_escape_string($link,$uuid) . "'");
        if($s){
            $successMsg = "Keys given!";
        } else {
            $errorMsg = mysqli_error($link);
        }
    } else {
        $errorMsg = "Value must be greater than 0!";
    }
}

$s = mysqli_query($link,"SELECT * FROM `users` WHERE `uuid` = '" . mysqli_real_escape_string($link,$uuid) . "'");
if(mysqli_num_rows($s) == 0){
    echo "Invalid UUID.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

$data = mysqli_fetch_array($s);

if(convertRankToID($currentPlayer->getRank()) >= 10){
    ?>
<div class="modal fade" id="coinsModal" tabindex="-1" role="dialog" aria-labelledby="coinsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coinsModalLabel">Give coins to <?php print $data["username"]; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form action="/profile/?uuid=<?php print $uuid; ?>" method="post">
                <div class="modal-body">
                    <input type="number" name="coinsToGive" class="form-control" placeholder="Coin amount (must be more than 0)" min="1"/>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Give</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="keysModal" tabindex="-1" role="dialog" aria-labelledby="keysModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keysModalLabel">Give keys to <?php print $data["username"]; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <form action="/profile/?uuid=<?php print $uuid; ?>" method="post">
                <div class="modal-body">
                    <input type="number" name="keysToGive" class="form-control" placeholder="Key amount (must be more than 0)" min="1"/>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Give</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <?php
}

?>
<div class="container">
    <?php

        if($successMsg != null){
            ?>
    <div class="alert alert-success my-2" role="alert">
        <?php print $successMsg; ?>
    </div>
            <?php
        }

    ?>

    <?php

        if($errorMsg != null){
            ?>
    <div class="alert alert-danger my-2" role="alert">
        <?php print $errorMsg; ?>
    </div>
            <?php
        }

    ?>
    <h1>Profile: <?php print $data["username"]; ?><?php if(convertRankToID($currentPlayer->getRank()) >= 10){ ?><span style="float: right"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#coinsModal">Give coins</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#keysModal">Give keys</button></span><?php } ?></h1>
    <form id="search-form" method="get" action="/users/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter username or UUID.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>
    <hr/>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-block text-center">
                    <img src="https://crafatar.com/renders/body/<?php print $data["uuid"]; ?>?overlay"/>
                    <h3><?php print $data["username"]; ?></h3>
                    <small><a href="https://mcskinhistory.com/player/<?php print $data["uuid"]; ?>" target="_blank">(click to view skin history)</a></small><br/>
                    <small><a href="/logins/?q=<?php print $data["uuid"]; ?>">(click to view logins)</a></small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <table class="table my-0">
                    <tr>
                        <td><b>Username</b></td>
                        <td><?php print $data["username"]; ?></td>
                    </tr>
                    <tr>
                        <td><b>UUID</b></td>
                        <td><?php print $data["uuid"]; ?></td>
                    </tr>
                    <tr>
                        <td><b>&nbsp;</b></td>
                        <td><?php print str_replace("-","",$data["uuid"]); ?></td>
                    </tr>
                    <tr>
                        <td><b>Rank</b></td>
                        <td><span style="color: <?php print getRankColor($data["rank"]); ?>"><?php print getRankName($data["rank"]); ?></span></td>
                    </tr>
                    <tr>
                        <td><b>Coins</b></td>
                        <td><?php print $data["coins"]; ?></td>
                    </tr>
                    <tr>
                        <td><b>Reset-Tokens</b></td>
                        <td><?php print $data["resetTokens"]; ?></td>
                    </tr>
                    <tr>
                        <td><b>Keys</b></td>
                        <td><?php print $data["keys"]; ?></td>
                    </tr>
                    <tr>
                        <td><b>Language</b></td>
                        <td><?php print $data["language"]; ?></td>
                    </tr>
                    <tr>
                        <td><b>First join</b></td>
                        <td><?php print $data["firstjoin"]; ?> (<?php print timeago($data["firstjoin"]); ?>)</td>
                    </tr>
                    <tr>
                        <td><b>Country</b></td>
                        <td><?php if($data["country"] != null){ echo countryCodeToName($data["country"]); } else { echo '<i>N/A</i>'; } ?></td>
                    </tr>
                    <tr>
                        <td><b>Playtime</b></td>
                        <td><?php
                        
                        $playtime = $data["playtime"];
                        if($playtime > 0){
                            $playtime /= 60;
                            $playtime /= 60;
                        }

                        echo round($playtime,1) . " hours";

                        ?></td>
                    </tr>
                    <tr>
                        <td><b>Last MC Version</b></td>
                        <td><?php print convertMCVersion($data["lastMCVersion"]); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#bans" role="tab">Bans</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#mutes" role="tab">Mutes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kicks" role="tab">Kicks</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#reports" role="tab">Reports</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#rankUpdates" role="tab">Rank Updates</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- BANS -->
                <div class="tab-pane active" id="bans" role="tabpanel">
                    <?php

                    $sql = mysqli_query($link,"SELECT * FROM `sn_bans` WHERE `bannedPlayer` = '" . mysqli_real_escape_string($link,$data["uuid"]) . "' ORDER BY `time` DESC");

                    ?>
                    <div class="card my-3">
                        <div class="card-header">Bans</div>
                        <table class="table my-0">
                            <tr>
                                <th>#</th>
                                <th>Banned By</th>
                                <th>Reason</th>
                                <th>Name when Banned</th>
                                <th>Server</th>
                                <th>Date</th>
                                <th>End Date</th>
                            </tr>
                            <?php

                            while($row = mysqli_fetch_array($sql)){
                                $player = MinecraftPlayer::getByUUID($row["bannedPlayer"]);
                                if($row["bannedBy"] != null) $enforcer = MinecraftPlayer::getByUUID($row["bannedBy"]);

                                ?>
                        <tr>
                            <td><?php print $row["id"]; ?></td>
                            <?php

                            if($row["bannedBy"] != null){
                                ?>
                            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                                <?php
                            } else {
                                ?>
                            <td>BungeeConsole</td>
                                <?php
                            }

                            ?>
                            <td><?php print $row["reason"]; ?></td>
                            <td><?php print $row["nameWhenBanned"]; ?></td>
                            <td><?php print $row["server"]; ?></td>
                            <td><?php print timeago($row["time"]); ?></td>
                            <td><?php if($row["expiry"] != null){ print timeago($row["expiry"]); } else { print "N/A"; } ?></td>
                        </tr>
                                <?php
                            }

                            ?>
                        </table>
                    </div>
                </div>

                <!-- MUTES -->
                <div class="tab-pane" id="mutes" role="tabpanel">
                    <?php

                    $sql = mysqli_query($link,"SELECT * FROM `sn_mutes` WHERE `mutedPlayer` = '" . mysqli_real_escape_string($link,$data["uuid"]) . "' ORDER BY `time` DESC");

                    ?>
                    <div class="card my-3">
                        <div class="card-header">Mutes</div>
                        <table class="table my-0">
                            <tr>
                                <th>#</th>
                                <th>Muted By</th>
                                <th>Reason</th>
                                <th>Name when Muted</th>
                                <th>Server</th>
                                <th>Date</th>
                                <th>End Date</th>
                            </tr>
                            <?php

                            while($row = mysqli_fetch_array($sql)){
                                $player = MinecraftPlayer::getByUUID($row["mutedPlayer"]);
                                if($row["mutedBy"] != null) $enforcer = MinecraftPlayer::getByUUID($row["mutedBy"]);

                                ?>
                        <tr>
                            <td><?php print $row["id"]; ?></td>
                            <?php

                            if($row["mutedBy"] != null){
                                ?>
                            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                                <?php
                            } else {
                                ?>
                            <td>BungeeConsole</td>
                                <?php
                            }

                            ?>
                            <td><?php print $row["reason"]; ?></td>
                            <td><?php print $row["nameWhenMuted"]; ?></td>
                            <td><?php print $row["server"]; ?></td>
                            <td><?php print timeago($row["time"]); ?></td>
                            <td><?php if($row["expiry"] != null){ print timeago($row["expiry"]); } else { print "N/A"; } ?></td>
                        </tr>
                                <?php
                            }

                            ?>
                        </table>
                    </div>
                </div>

                <!-- KICKS -->
                <div class="tab-pane" id="kicks" role="tabpanel">
                    <?php

                    $sql = mysqli_query($link,"SELECT * FROM `sn_kicks` WHERE `kickedPlayer` = '" . mysqli_real_escape_string($link,$data["uuid"]) . "' ORDER BY `time` DESC");

                    ?>
                    <div class="card my-3">
                        <div class="card-header">Kicks</div>
                        <table class="table my-0">
                            <tr>
                                <th>#</th>
                                <th>Kicked by</th>
                                <th>Reason</th>
                                <th>Name when Kicked</th>
                                <th>Server</th>
                                <th>Date</th>
                            </tr>
                            <?php

                            while($row = mysqli_fetch_array($sql)){
                                $player = MinecraftPlayer::getByUUID($row["kickedPlayer"]);
                                if($row["kickedBy"] != null) $enforcer = MinecraftPlayer::getByUUID($row["kickedBy"]);

                                ?>
                        <tr>
                            <td><?php print $row["id"]; ?></td>
                            <?php

                            if($row["kickedBy"] != null){
                                ?>
                            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                                <?php
                            } else {
                                ?>
                            <td>BungeeConsole</td>
                                <?php
                            }

                            ?>
                            <td><?php print $row["reason"]; ?></td>
                            <td><?php print $row["nameWhenKicked"]; ?></td>
                            <td><?php print $row["server"]; ?></td>
                            <td><?php print timeago($row["time"]); ?></td>
                        </tr>
                                <?php
                            }

                            ?>
                        </table>
                    </div>
                </div>

                <!-- REPORTS -->
                <div class="tab-pane" id="reports" role="tabpanel">
                    <?php

                    $sql = mysqli_query($link,"SELECT * FROM `sn_reports` WHERE `reportedPlayer` = '" . mysqli_real_escape_string($link,$data["uuid"]) . "' ORDER BY `time` DESC");

                    ?>
                    <div class="card my-3">
                        <div class="card-header">Reports</div>
                        <table class="table my-0">
                            <tr>
                                <th>#</th>
                                <th>Reported by</th>
                                <th>Reason</th>
                                <th>Name when Reported</th>
                                <th>Server</th>
                                <th>Date</th>
                            </tr>
                            <?php

                            while($row = mysqli_fetch_array($sql)){
                                $enforcer = MinecraftPlayer::getByUUID($row["reportedBy"]);

                                ?>
                        <tr>
                            <td><?php print $row["id"]; ?></td>
                            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                            <td><?php print $row["reason"]; ?></td>
                            <td><?php print $row["nameWhenReported"]; ?></td>
                            <td><?php print $row["server"]; ?></td>
                            <td><?php print timeago($row["time"]); ?></td>
                        </tr>
                                <?php
                            }

                            ?>
                        </table>
                    </div>
                </div>

                <!-- RANK UPDATES -->
                <div class="tab-pane" id="rankUpdates" role="tabpanel">
                    <?php

                    $sql = mysqli_query($link,"SELECT * FROM `rankUpdates` WHERE `player` = '" . mysqli_real_escape_string($link,$data["uuid"]) . "' ORDER BY `time` DESC");

                    ?>
                    <div class="card my-3">
                        <div class="card-header">Rank Updates</div>
                        <table class="table my-0">
                            <tr>
                                <th>#</th>
                                <th>Updated by</th>
                                <th>New Rank</th>
                                <th>Date</th>
                            </tr>
                            <?php

                            while($row = mysqli_fetch_array($sql)){
                                if($row["executor"] != null) $enforcer = MinecraftPlayer::getByUUID($row["executor"]);

                                ?>
                        <tr>
                            <td><?php print $row["id"]; ?></td>
                            <?php

                            if($row["executor"] != null){
                                ?>
                            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                                <?php
                            } else {
                                ?>
                            <td>BungeeConsole</td>
                                <?php
                            }

                            ?>
                            <td><span style="color: <?php print getRankColor($row["newRank"]); ?>"><?php print getRankName($row["newRank"]); ?></span></td>
                            <td><?php print timeago($row["time"]); ?></td>
                        </tr>
                                <?php
                            }

                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "../assets/incl/bottom.inc.php"; ?>