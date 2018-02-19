<?php require_once "../assets/incl/top.inc.php";

if(convertRankToID($currentPlayer->getRank()) < 8){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

if(!isset($_GET["ip"]) || (isset($_GET["ip"]) && empty($_GET["ip"]))){
    echo "Invalid IP.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

$ip = $_GET["ip"];

$sql = mysqli_query($link,"SELECT * FROM `ip_info` WHERE `ip` = '" . mysqli_real_escape_string($link,$ip) . "'");
if(mysqli_num_rows($sql) == 0){
    echo "Invalid IP.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

$data = mysqli_fetch_array($sql);

?>
<div class="container">
    <h1>IP Info: <?php print $ip; ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card my-2 py-0">
                <table class="table">
                    <tr>
                        <td width="50%" valign="top">
                            <b>IP</b>
                        </td>

                        <td width="50%" valign="top">
                            <?php print $ip; ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" valign="top">
                            <b>Country</b>
                        </td>

                        <td width="50%" valign="top">
                            <?php if($data["country"] != null){ echo countryCodeToName($data["country"]); } else { echo '<i>N/A</i>'; } ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" valign="top">
                            <b>Time found</b>
                        </td>

                        <td width="50%" valign="top">
                            <?php print timeago($data["time_added"]); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <?php

                $s = mysqli_query($link,"SELECT u.username,u.uuid,u.rank FROM `users` AS u INNER JOIN `logins` AS l ON u.uuid = l.uuid WHERE l.ip = '" . mysqli_real_escape_string($link,$ip) . "' GROUP BY u.uuid ORDER BY u.username ASC");
                if(mysqli_num_rows($s) > 0){
                    ?>
            <div class="card my-2 py-0">
                <div class="card-header"><i class="fa fa-users"></i> Users</div>

                <table class="table">
                    <tr>
                        <th>&nbsp;</th>
                        <th>Username</th>
                        <th>Rank</th>
                    </tr>
                    <?php

                    while($row = mysqli_fetch_array($s)){
                        ?>
                    <tr>
                        <td><img src="https://crafatar.com/avatars/<?php print $row["uuid"]; ?>?overlay&size=30"/></td>
                        <td><a href="/profile/?uuid=<?php print $row["uuid"]; ?>"><?php print $row["username"]; ?></a></td>
                        <td><span style="color: <?php print getRankColor($row["rank"]); ?>"><?php print getRankName($row["rank"]); ?></span></td>
                    </tr>
                        <?php
                    }

                    ?>
                </table>
            </div>
                    <?php
                }

            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            
                $s = mysqli_query($link,"SELECT * FROM `sn_ipBans` WHERE `ip` = '" . mysqli_escape_string($link,$ip) . "' ORDER BY `time` DESC");
                if(mysqli_num_rows($s) > 0){
                    ?>
                    <div class="card my-3">
                        <div class="card-header">IP Bans</div>

                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Banned by</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Unban Info</th>
                            </tr>
                            <?php

                            while($row = mysqli_fetch_array($s)){
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
                                <td><?php if($row["reason"] == null){ echo '<i>none</i>'; } else { echo $row["reason"]; } ?></td>
                                <td><?php print timeago($row["time"]); ?></td>
                                <td><?php if($row["active"] == true){ print 'active'; } else { print '<span style="color:green">unbanned</span>'; } ?></td>
                                <td><?php
                                
                                    if($row["active"] == true){
                                        ?>
                                <form action="/ban-management/ip-bans/" method="post">
                                    <input type="hidden" name="unbanIP" value="<?php print $row["ip"]; ?>"/>
                                    <button type="submit" class="btn btn-success btn-block">Unban</button>
                                </form>
                                        <?php
                                    } else {
                                        if($row["unban.staff"] == null){
                                            echo "(expired)";
                                        } else {
                                            $unbanStaff = MinecraftPlayer::getByUUID($row["unban.staff"]); 

                                            ?>
                                    by <a href="/profile/?uuid=<?php print $unbanStaff->getUUID(); ?>"><?php print $unbanStaff->getUsername(); ?></a> (<?php print timeago($row["unban.time"]); ?>)
                                            <?php
                                        }
                                    }

                                ?></td>
                            </tr>
                                    <?php
                            }
                            ?>
                            <tr>
                                <th>#</th>
                                <th>Banned by</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Unban Info</th>
                            </tr>
                        </table>
                    </div>
                    <?php
                }

            ?>
        </div>
    </div>
</div>
<?php require_once "../assets/incl/bottom.inc.php"; ?>