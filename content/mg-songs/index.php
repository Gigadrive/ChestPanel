<?php require_once "../../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

if(convertRankToID($currentPlayer->getRank()) < 10){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Musical Guess: Song Manager</h1>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		$query = "SELECT * FROM `mg_songs` ORDER BY `time` DESC";
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Artist</th>
            <th>Added By</th>
            <th>Time</th>
            <th><a href="/content/mg-songs/add/" style="width: 100%" class="btn btn-success">Add</a></th>
        </tr>

        <?php

            if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                $player = MinecraftPlayer::getByUUID($row["addedBy"]);
                    ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><?php print $row["title"]; ?></td>
            <td><?php print $row["artist"]; ?></td>
            <td><span style="color: <?php print getRankColor($player->getRank()); ?>"><?php print $player->getUsername(); ?></span></td>
            <td><?php print timeago($row["time"]); ?></td>
            <td><a href="/content/mg-songs/edit/?id=<?php print $row["id"]; ?>" style="width: 100%" class="btn btn-warning">Edit</a> <a style="width: 100%" href="/content/mg-songs/delete/?id=<?php print $row["id"]; ?>" class="btn btn-danger">Delete</a></td>
        </tr>
                    <?php
                }
            }

        ?>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Artist</th>
            <th>Added By</th>
            <th>Time</th>
            <th><a href="/content/mg-songs/add/" style="width: 100%" class="btn btn-success">Add</a></th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>