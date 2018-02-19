<?php require_once "../../assets/incl/top.inc.php"; ?>
<div class="container">
    <h1>IP Bans</h1>
    <?php

        $successMsg = null;
        $errorMsg = null;

        if(isset($_POST["ip"]) && $_POST["reason"]){
            $ip = $_POST["ip"];
            $reason = $_POST["reason"];

            if(empty($reason)){
                $reason = null;
            } else {
                $reason = trim($reason);
            }

            if(!empty($ip)){
                $ip = trim($ip);

                if(filter_var($ip,FILTER_VALIDATE_IP)){
                    if(mysqli_num_rows(mysqli_query($link,"SELECT * FROM `sn_ipBans` WHERE `ip` = '" . mysqli_escape_string($link,$ip) . "' AND `active` = 1")) == 0){
                        $s;

                        if($reason != null){
                            $s = mysqli_query($link,"INSERT INTO `sn_ipBans` (`ip`,`reason`,`bannedBy`) VALUES('" . mysqli_escape_string($link,$ip) . "','" . mysqli_escape_string($link,$reason) . "','" . mysqli_escape_string($link,$currentPlayer->getUUID()) . "')");
                        } else {
                            $s = mysqli_query($link,"INSERT INTO `sn_ipBans` (`ip`,`bannedBy`) VALUES('" . mysqli_escape_string($link,$ip) . "','" . mysqli_escape_string($link,$currentPlayer->getUUID()) . "')");
                        }

                        if($s){
                            $successMsg = "Successfully created 1 new ban rule!";
                        } else {
                            $errorMsg = "An error occured! " . mysqli_error($link);
                        }
                    } else {
                        $errorMsg = "That IP is already banned.";
                    }
                } else {
                    $errorMsg = "Please enter a valid IPv4 address.";
                }
            } else {
                $errorMsg = "Please enter a valid IP.";
            }
        } else if(isset($_POST["unbanIP"])){
            if(!empty($_POST["unbanIP"])){
                $ip = $_POST["unbanIP"];

                if(filter_var($ip,FILTER_VALIDATE_IP)){
                    if(mysqli_num_rows(mysqli_query($link,"SELECT * FROM `sn_ipBans` WHERE `ip` = '" . mysqli_escape_string($link,$ip) . "' AND `active` = 1")) > 0){
                        $s = mysqli_query($link,"UPDATE `sn_ipBans` SET `active` = 0, `unban.staff` = '" . mysqli_escape_string($link,$currentPlayer->getUUID()) . "', `unban.time` = CURRENT_TIMESTAMP WHERE `ip` = '" . mysqli_escape_string($link,$ip) . "' AND `active` = 1");

                        if($s){
                            $successMsg = "Successfully deactivated 1 new ban rule!";
                        } else {
                            $errorMsg = "An error occured! " . mysqli_error($link);
                        }
                    } else {
                        $errorMsg = "That IP is not banned.";
                    }
                } else {
                    $errorMsg = "Please enter a valid IPv4 address.";
                }
            } else {
                $errorMsg = "Please submit the IP you want to unban correctly.";
            }
        }

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
    <div class="alert alert-info my-2" role="alert">
        Please only ban static IPs. In order to ban users with dynamic IP addresses, use the ingame command <b>/punish</b>.<br/>
        Only IPv4 addresses may be banned.
    </div>

    <div class="card my-2">
        <div class="card-header">
            Ban IP
        </div>
        <div class="card-block">
            <form action="/ban-management/ip-bans/" method="post">
                <input type="hidden" name="action" value="add"/>
                <input type="text" class="form-control" name="ip" placeholder="IP (v4 only!)" autofocus<?php if($errorMsg != null && isset($ip) && $ip != null && !empty($ip)){ ?> value="<?php print $ip; ?>"<?php } ?>/><br/>
                <input type="text" class="form-control" name="reason" placeholder="Reason (optional)"<?php if($errorMsg != null && isset($ip) && $reason != null && !empty($reason)){ ?> value="<?php print $reason; ?>"<?php } ?>/><br/>

                <button type="submit" class="btn btn-danger">Create Ban rule</button>
            </form>
        </div>
    </div>

    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		$query = "SELECT * FROM `sn_ipBans` ORDER BY `time` DESC";
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>IP</th>
            <th>Banned by</th>
            <th>Reason</th>
            <th>Date</th>
            <th>Status</th>
            <th>Unban Info</th>
        </tr>
        <?php

        if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                if($row["bannedBy"] != null) $enforcer = MinecraftPlayer::getByUUID($row["bannedBy"]);

                ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><a href="/ipinfo/?ip=<?php print $row["ip"]; ?>"><?php print $row["ip"]; ?></a></td>
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
        }
        ?>
        <tr>
            <th>#</th>
            <th>IP</th>
            <th>Banned by</th>
            <th>Reason</th>
            <th>Date</th>
            <th>Status</th>
            <th>Unban Info</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>