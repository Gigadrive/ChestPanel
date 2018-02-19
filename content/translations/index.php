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
    <h1>Translations</h1>
    <?php

        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["EN"]) && isset($_POST["DE"])){
            $en = $_POST["EN"];
            $de = $_POST["DE"];

            if(is_null($en) || empty($en) || $en == "") $en = null;
            if(is_null($de) || empty($de) || $de == "") $de = null;

            if($en != null){
                if(mysqli_num_rows(mysqli_query($link,"SELECT * FROM `translations` WHERE `EN` = '" . mysqli_real_escape_string($link,$en) . "'")) == 0){
                    if($de != null){
                        $s = mysqli_query($link,"INSERT INTO `translations` (`EN`,`DE`) VALUES('" . mysqli_real_escape_string($link,$en) . "','" . mysqli_real_escape_string($link,$de) . "')");
                        if($s){
                            $successMsg = "The phrase has been added to the list.";
                        } else {
                            $errorMsg = mysqli_error($link);
                        }
                    } else if($de == null){
                        $s = mysqli_query($link,"INSERT INTO `translations` (`EN`) VALUES('" . mysqli_real_escape_string($link,$en) . "')");
                        if($s){
                            $successMsg = "The phrase has been added to the list.";
                        } else {
                            $errorMsg = mysqli_error($link);
                        }
                    }
                } else {
                    $errorMsg = "That phrase has already been registered.";
                }
            } else {
                $errorMsg = "You have to specify the english phrase content!";
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
            Add phrase
        </div>
        <div class="card-block">
            <form action="/content/translations/" method="post">
                <input type="text" class="form-control" name="EN" placeholder="English" autofocus/><br/>
                <input type="text" class="form-control" name="DE" placeholder="German"/><br/>

                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <form id="search-form" method="get" action="/content/translations/">
		<div class="input-group input-group-lg my-2">
            <input id="search-box" name="q" type="search" placeholder="Enter word.." autocomplete="off" spellcheck="false" class="form-control minecraft-name"<?php if($search != null){ ?> value="<?php print $search; ?>"<?php } ?>><span class="input-group-btn">
			<button type="submit" class="btn btn-primary">Search</button></span>
		</div>
	</form>

            <?php
		
                $limit      = 25;
                $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;

                if($search != null){
                    $query = "SELECT * FROM `translations` WHERE `EN` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' OR `DE` LIKE '%" . mysqli_real_escape_string($link,$search) . "%' ORDER BY `EN` ASC";
                } else {
                    $query = "SELECT * FROM `translations` ORDER BY `EN` ASC";
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
                    <th>English</th>
                    <th>German</th>
                    <th>Date</th>
                    <th>&nbsp;</th>
                </tr>

                <?php

                    if(count( $results->data ) > 0){
                        for( $i = 0; $i < count( $results->data ); $i++ ){
                            $row = $results->data[$i];
                            ?>
                <tr>
                    <td><?php print $row["EN"]; ?></td>
                    <td><?php print $row["DE"]; ?></td>
                    <td><?php print timeago($row["date_added"]); ?></td>
                    <td><a href="/content/translations/edit/?id=<?php print $row["id"]; ?>" style="width: 100%" class="btn btn-warning">Edit</a> <a style="width: 100%" href="/content/translations/delete/?id=<?php print $row["id"]; ?>" class="btn btn-danger">Delete</a></td>
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