<?php require_once "../../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

$search = null;
if(isset($_GET["q"]) && !empty($_GET["q"])) $search = $_GET["q"];

?>
<?php

if(convertRankToID($currentPlayer->getRank()) < 10){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Map pool</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["action"])){
            $action = $_POST["action"];

            if($action == "enable"){
                if(isset($_POST["mapID"])){
                    $mapID = $_POST["mapID"];

                    $s = mysqli_query($link,"UPDATE `maps` SET `active` = 1 WHERE `id` = " . mysqli_real_escape_string($link,$mapID));
                    if($s){
                        $successMsg = "Enabled map.";
                    } else {
                        $errorMsg = mysqli_error($link);
                    }
                }
            } else if($action == "disable"){
                if(isset($_POST["mapID"])){
                    $mapID = $_POST["mapID"];

                    $s = mysqli_query($link,"UPDATE `maps` SET `active` = 0 WHERE `id` = " . mysqli_real_escape_string($link,$mapID));
                    if($s){
                        $successMsg = "Disabled map.";
                    } else {
                        $errorMsg = mysqli_error($link);
                    }
                }
            }
        }

		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
        if($search != null){
            $query = "SELECT `id`,`name`,`author`,`link`,`mapType`,`time_added`,`addedBy`,`active`,`worldName` FROM `maps` WHERE `name` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `id` = '" . mysqli_real_escape_string($link,$search) . "' ORDER BY `id` ASC";
        } else {
            $query = "SELECT `id`,`name`,`author`,`link`,`mapType`,`time_added`,`addedBy`,`active`,`worldName` FROM `maps` ORDER BY `time_added` DESC";
        }
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );
    ?>
    <form id="search-form" method="get" action="/content/maps/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter name or ID.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>
    <?php

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

        if($errorMsg != ""){
            ?>
    <div class="alert alert-danger" role="alert"><?php print $errorMsg; ?></div>
            <?php
        }

        if($successMsg != ""){
            ?>
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?></div>
            <?php
        }

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Added By</th>
            <th>Time</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>

        <?php

            if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                $player = MinecraftPlayer::getByUUID($row["addedBy"]);
                    ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><?php print $row["name"]; ?></td>
            <td><?php print $row["mapType"]; ?></td>
            <td><span style="color: <?php print getRankColor($player->getRank()); ?>"><?php print $player->getUsername(); ?></span></td>
            <td><?php print timeago($row["time_added"]); ?></td>
            <td><?php if($row["active"] == 1){ ?><span class="badge badge-success">Enabled</span><?php } else { ?><span class="badge badge-danger">Disabled</span><?php } ?></td>
            <td>
                <?php if($row["active"] == 1){
                    ?>
                <form action="/content/maps/" method="post"><input type="hidden" value="disable" name="action"/><input type="hidden" value="<?php print $row["id"]; ?>" name="mapID"/><button type="submit" style="width: 100%" class="btn btn-danger">Disable</button></form>
                    <?php
                } else {
                    ?>
                <form action="/content/maps/" method="post"><input type="hidden" value="enable" name="action"/><input type="hidden" value="<?php print $row["id"]; ?>" name="mapID"/><button type="submit" style="width: 100%" class="btn btn-success">Enable</button></form>
                    <?php
                }
                ?>

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" style="width: 100%" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/content/maps/edit/?id=<?php print $row["id"]; ?>">Edit</a>
                        <a class="dropdown-item" href="/content/maps/statistics/?id=<?php print $row["id"]; ?>">Statistics</a>
                        <a class="dropdown-item" href="/content/maps/spawnpoints/?id=<?php print $row["id"]; ?>">Spawnpoints</a>
                    </div>
                </div>
            </td>
        </tr>
                    <?php
                }
            }

        ?>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Added By</th>
            <th>Time</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>