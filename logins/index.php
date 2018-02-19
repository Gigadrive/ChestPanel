<?php require_once "../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

if(convertRankToID($currentPlayer->getRank()) < 8){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../assets/incl/bottom.inc.php";
    exit();
}

?>
<?php

$search = null;
if(isset($_GET["q"]) && !empty($_GET["q"])) $search = $_GET["q"];

?>
    <h1>Server Logins</h1>
    <form id="search-form" method="get" action="/logins/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter UUID, IP or server IP.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		if($search != null){
            $query = "SELECT * FROM `logins` WHERE `ip` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `usedIP` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `uuid` = '" . mysqli_real_escape_string($link,$search) . "' ORDER BY `time` DESC";
        } else {
            $query = "SELECT * FROM `logins` ORDER BY `time` DESC";
        }
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>IP</th>
            <th>Time</th>
            <th>Used server IP</th>
        </tr>
        <?php

        if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                $player = MinecraftPlayer::getByUUID($row["uuid"]);

                ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><img src="https://crafatar.com/avatars/<?php print $player->getUUID(); ?>?overlay&size=30"/></td>
            <td><a href="/profile/?uuid=<?php print $player->getUUID(); ?>"><?php print $player->getUsername(); ?></a></td>
            <td><a href="/ipinfo/?ip=<?php print $row["ip"]; ?>"><?php print $row["ip"]; ?></a></td>
            <td><?php print timeago($row["time"]); ?></td>
            <td><?php print $row["usedIP"]; ?></td>
        </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>IP</th>
            <th>Time</th>
            <th>Used server IP</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../assets/incl/bottom.inc.php"; ?>