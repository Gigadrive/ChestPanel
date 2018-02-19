<?php require_once "../../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

if(convertRankToID($currentPlayer->getRank()) < 8){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
<?php

$successMsg = null;
$errorMsg = null;
$search = null;
if(isset($_GET["q"]) && !empty($_GET["q"])) $search = $_GET["q"];

if(isset($_POST["action"]) && isset($_POST["reportID"])){
    $action = $_POST["action"];
    $reportID = $_POST["reportID"];
    $reportData = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM `sn_reports` WHERE `id` = " . mysqli_real_escape_string($link,$reportID)));

    if($reportData != null && count($reportData) > 0){
        if($action == "acceptReport"){
            $s = mysqli_query($link,"UPDATE `sn_reports` SET `solved.status` = 'ACCEPTED', `solved.staff` = '" . mysqli_real_escape_string($link,$currentPlayer->getUUID()) . "', `solved.time` = CURRENT_TIMESTAMP WHERE `id` = " . mysqli_real_escape_string($link,$reportID));
            $ss = mysqli_query($link,"UPDATE `users` SET `acceptedReportCoins` = `acceptedReportCoins`+400 WHERE `uuid` = '" . mysqli_real_escape_string($link,$reportData["reportedBy"]) . "'");
            if($s && $ss){
                $successMsg = "You have accepted the report.";
            } else {
                $errorMsg = mysqli_error($link);
            }
        } else if($action == "denyReport"){
            $s = mysqli_query($link,"UPDATE `sn_reports` SET `solved.status` = 'DENIED', `solved.staff` = '" . mysqli_real_escape_string($link,$currentPlayer->getUUID()) . "', `solved.time` = CURRENT_TIMESTAMP WHERE `id` = " . mysqli_real_escape_string($link,$reportID));
            if($s){
                $successMsg = "You have denied the report.";
            } else {
                $errorMsg = mysqli_error($link);
            }
        }
    } else {
        $errorMsg = "Invalid Report data! " . mysqli_error($link);
    }
}

?>
    <h1>Reports</h1>
    <form id="search-form" method="get" action="/ban-management/reports/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter UUID, reason or name.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		if($search != null){
            $query = "SELECT * FROM `sn_reports` WHERE `reportedPlayer` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `reason` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `nameWhenReported` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' ORDER BY `time` DESC";
        } else {
            $query = "SELECT * FROM `sn_reports` ORDER BY `time` DESC";
        }
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
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
    <table class="table">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Reported by</th>
            <th>Reason</th>
            <th>Server</th>
            <th>Time</th>
            <th width="20%">&nbsp;</th>
        </tr>
        <?php

        if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                $player = MinecraftPlayer::getByUUID($row["reportedPlayer"]);
                $reporter = MinecraftPlayer::getByUUID($row["reportedBy"]);

                ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><img src="https://crafatar.com/avatars/<?php print $player->getUUID(); ?>?overlay&size=30"/></td>
            <td><a href="/profile/?uuid=<?php print $player->getUUID(); ?>"><?php print $player->getUsername(); ?></a></td>
            <td><a href="/profile/?uuid=<?php print $reporter->getUUID(); ?>"><?php print $reporter->getUsername(); ?></a></td>
            <td><?php print $row["reason"]; ?></td>
            <td><?php print $row["server"]; ?></td>
            <td><?php print timeago($row["time"]); ?></td>
            <td width="20%">
                <?php if($row["solved.status"] == "OPEN"){
                    ?>
                <form action="/ban-management/reports/" method="post">
                <input type="hidden" value="acceptReport" name="action"/>
                <input type="hidden" value="<?php print $row["id"]; ?>" name="reportID"/>
                <button type="submit" class="btn btn-sm btn-block btn-success">Accept</button>
                </form>

                <form action="/ban-management/reports/" method="post">
                <input type="hidden" value="denyReport" name="action"/>
                <input type="hidden" value="<?php print $row["id"]; ?>" name="reportID"/>
                <button type="submit" class="btn btn-sm btn-block btn-danger">Deny</button>
                </form>

                <?php
                } else if($row["solved.status"] == "ACCEPTED"){
                    ?>
                <span style="color: green">Accepted by <?php print MinecraftPlayer::getByUUID($row["solved.staff"])->getUsername(); ?> (<?php print timeago($row["solved.time"]); ?>)</span>
                    <?php
                } else if($row["solved.status"] == "DENIED"){
                    ?>
                <span style="color: red">Denied by <?php print MinecraftPlayer::getByUUID($row["solved.staff"])->getUsername(); ?> (<?php print timeago($row["solved.time"]); ?>)</span>
                    <?php
                }
                
                if($row["chatLogID"] != 0){
                        ?>
                <a href="/chatlog/?id=<?php print $row["chatLogID"]; ?>">
                <button type="button" class="btn btn-sm btn-block btn-primary">Chatlog</button>
                </a>
                        <?php
                } ?>
            </td>
        </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Reported by</th>
            <th>Reason</th>
            <th>Server</th>
            <th>Time</th>
            <th width="20%">&nbsp;</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>