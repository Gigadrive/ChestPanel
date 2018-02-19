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
    <h1>Shortened URLs</h1>
    <form id="search-form" method="get" action="/content/urlshortener/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter link or ID.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		if($search != null){
            $query = "SELECT * FROM `shortened_urls` WHERE `id` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `link` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `id` = '" . mysqli_real_escape_string($link,$search) . "' ORDER BY `time` DESC";
        } else {
            $query = "SELECT * FROM `shortened_urls` ORDER BY `time` DESC";
        }
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Link</th>
            <th>Time added</th>
            <th><a href="/content/urlshortener/add/" style="width: 100%" class="btn btn-success">Add</a></th>
        </tr>

        <?php

            if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                    ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><?php print $row["link"]; ?></td>
            <td><?php print timeago($row["time"]); ?></td>
            <td><a style="width: 100%" href="/content/urlshortener/edit/?id=<?php print $row["id"]; ?>" class="btn btn-warning">Edit</a> <a style="width: 100%" href="/content/urlshortener/delete/?id=<?php print $row["id"]; ?>" class="btn btn-danger">Delete</a></td>
        </tr>
                    <?php
                }
            }

        ?>
        <tr>
            <th>ID</th>
            <th>Link</th>
            <th>Time added</th>
            <th><a href="/content/urlshortener/add/" style="width: 100%" class="btn btn-success">Add</a></th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>