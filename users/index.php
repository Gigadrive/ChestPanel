<?php require_once "../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

$search = null;
if(isset($_GET["q"]) && !empty($_GET["q"])) $search = $_GET["q"];

?>
    <h1>Server Users</h1>
    <form id="search-form" method="get" action="/users/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter username or UUID.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		if($search != null){
            $query = "SELECT * FROM `users` WHERE `username` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `uuid` = '" . mysqli_real_escape_string($link,$search) . "' ORDER BY `username` ASC";
        } else {
            $query = "SELECT * FROM `users` ORDER BY `firstjoin` DESC";
        }
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

        ?>
    <table class="table">
        <tr>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Rank</th>
            <th>Coins</th>
            <th>Country</th>
            <th>First join</th>
        </tr>
        <?php

        if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];

                ?>
        <tr>
            <td><img src="https://crafatar.com/avatars/<?php print $row["uuid"]; ?>?overlay&size=30"/></td>
            <td><a href="/profile/?uuid=<?php print $row["uuid"]; ?>"><?php print $row["username"]; ?></a></td>
            <td><span style="color: <?php print getRankColor($row["rank"]); ?>"><?php print getRankName($row["rank"]); ?></span></td>
            <td><?php print $row["coins"]; ?></td>
            <td><?php if($row["country"] == null){ echo '<i>N/A</i>'; } else { echo countryCodeToName($row["country"]); } ?></td>
            <td><?php print timeago($row["firstjoin"]); ?></td>
        </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Rank</th>
            <th>Coins</th>
            <th>Country</th>
            <th>First join</th>
        </tr>
    </table>
        <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' ); ?>
</div>
<?php require_once "../assets/incl/bottom.inc.php"; ?>