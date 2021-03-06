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
    <h1>Nicklist</h1>
    <?php

        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["action"])){
            $action = $_POST["action"];

            if($action == "add"){
                if(isset($_POST["nick"])){
                    $nick = $_POST["nick"];

                    $s = mysqli_query($link,"INSERT INTO `nicks` (`name`,`addedBy`) VALUES('" . mysqli_real_escape_string($link,$nick) . "','" . mysqli_real_escape_string($link,$currentPlayer->getUUID()) . "')");
                    if($s){
                        $successMsg = "The name has been added to the list.";
                    } else {
                        $errorMsg = mysqli_error($link);
                    }
                }
            } else if($action == "delete"){
                if(isset($_POST["nick"])){
                    $nick = $_POST["nick"];

                    $s = mysqli_query($link,"DELETE FROM `nicks` WHERE `name` = '" . mysqli_real_escape_string($link,$nick) . "'");
                    if($s){
                        $successMsg = "The name has been removed from the list.";
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
        }

        if($errorMsg != ""){
            ?>
    <div class="alert alert-danger" role="alert"><?php print $errorMsg; ?></div>
            <?php
        }

    ?>
    <div class="card my-2">
        <div class="card-header">
            Add nick to list
        </div>
        <div class="card-block">
            <form action="/content/nicklist/" method="post">
                <input type="hidden" name="action" value="add"/>
                <input type="text" class="form-control" name="nick" maxlength="16" minlength="3" placeholder="Enter name to add" autofocus/><br/>

                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <form id="search-form" method="get" action="/content/nicklist/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter name.." autofocus autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>

            <?php
		
                $limit      = 25;
                $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;

                if($search != null){
                    $query = "SELECT * FROM `nicks` WHERE `name` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' ORDER BY `name` ASC";
                } else {
                    $query = "SELECT * FROM `nicks` ORDER BY `name` ASC";
                }
                
                $Paginator  = new Paginator( $link, $query );
                $results    = $Paginator->getData( $limit, $page );

            ?>
    <div class="card">
        <div class="card-header">
            Results (<?php print $results->total; ?>)
        </div>
        <div class="card-block">
            <?php
		
                echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
                
            ?>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Added by</th>
                    <th>Date</th>
                    <th>&nbsp;</th>
                </tr>

                <?php

                    if(count( $results->data ) > 0){
                        for( $i = 0; $i < count( $results->data ); $i++ ){
                            $row = $results->data[$i];
                            $player = MinecraftPlayer::getByUUID($row["addedBy"]);
                            ?>
                <tr>
                    <td><?php print $row["name"]; ?></td>
                    <td><span style="color: <?php print getRankColor($player->getRank()); ?>"><?php print $player->getUsername(); ?></span></td>
                    <td><?php print timeago($row["time"]); ?></td>
                    <td><form action="/content/nicklist/" method="post"><button type="submit" class="btn btn-danger">Delete</button><input type="hidden" name="nick" value="<?php print $row["name"]; ?>"/><input type="hidden" name="action" value="delete"/></form></td>
                </tr>
                            <?php
                        }
                    }

                ?>
            </table>
            <?php
		
                echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
                
            ?>
        </div>
    </div>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>