<?php require_once "../../../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

if(convertRankToID($currentPlayer->getRank()) < 10){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../../../assets/incl/bottom.inc.php";
    exit();
}

$id = $_GET["id"];

$sel = mysqli_query($link,"SELECT * FROM `maps` WHERE `id` = " . mysqli_real_escape_string($link,$id));
if(mysqli_num_rows($sel) > 0){
    $row = mysqli_fetch_array($sel);
    $id = $row["id"];
    $name = $row["name"];
    $author = $row["author"];
    $website = $row["link"];
    $mapType = $row["mapType"];
    $worldName = $row["worldName"];
} else {
    echo "Invalid map ID.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Locations: <?php print $name; ?></h1>
    <?php

        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["action"])){
            $action = $_POST["action"];

            if($action == "delete"){
                if(isset($_POST["id"])){
                    $sid = $_POST["id"];

                    $s = mysqli_query($link,"DELETE FROM `map_locations` WHERE `id` = '" . mysqli_real_escape_string($link,$sid) . "'");
                    if($s){
                        $successMsg = "The location has been deleted.";
                    } else {
                        $errorMsg = mysqli_error($link);
                    }
                }
            }
        }

        if($successMsg != ""){
            ?>
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?></div>
            <?php
        } else if($errorMsg != ""){
            ?>
    <div class="alert alert-danger" role="alert"><?php print $errorMsg; ?></div>
            <?php
        } else {
            ?>
    <div class="alert alert-info" role="alert">Add a location with /addmaplocation <?php print $id; ?></div>
            <?php
        }

		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		$query = "SELECT * FROM `map_locations` WHERE `mapID` = " . $id . " ORDER BY `time_added` DESC";
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>X</th>
            <th>Y</th>
            <th>Z</th>
            <th>Yaw</th>
            <th>Pitch</th>
            <th>Type</th>
            <th>Added By</th>
            <th>Time</th>
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
            <td><?php print $row["x"]; ?></td>
            <td><?php print $row["y"]; ?></td>
            <td><?php print $row["z"]; ?></td>
            <td><?php print $row["yaw"]; ?></td>
            <td><?php print $row["pitch"]; ?></td>
            <td><?php print $row["type"]; ?></td>
            <td><span style="color: <?php print getRankColor($player->getRank()); ?>"><?php print $player->getUsername(); ?></span></td>
            <td><?php print timeago($row["time_added"]); ?></td>
            <td><form action="/content/maps/spawnpoints/?id=<?php print $id; ?>" method="post"><button type="submit" class="btn btn-danger">Delete</button><input type="hidden" name="id" value="<?php print $row["id"]; ?>"/><input type="hidden" name="action" value="delete"/></form></td>
        </tr>
                    <?php
                }
            }

        ?>
        <tr>
            <th>#</th>
            <th>X</th>
            <th>Y</th>
            <th>Z</th>
            <th>Yaw</th>
            <th>Pitch</th>
            <th>Type</th>
            <th>Added By</th>
            <th>Time</th>
            <th>&nbsp;</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>