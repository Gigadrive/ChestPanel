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
    <h1>Update Server List MotD</h1>
    <div class="card">
        <div class="card-header">
            MotD Manager
        </div>
        <div class="card-block">
            <?php

                $line1 = "&eplay.&6&lTheChest&e.eu";
                $line2 = "";

                if(isset($_POST["line1"]) && isset($_POST["line2"])){
                    $line1 = $_POST["line1"];
                    $line2 = $_POST["line2"];

                    $updateSQL = mysqli_query($link, "INSERT INTO `motd_manager` (`first_line`,`second_line`,`updatedBy`) VALUES('" . $line1 . "','" . $line2 . "','" . $currentPlayer->getUUID() . "');");
						
					if($updateSQL){
						echo '<span style="color: green">Updated MotD. (It may take a while to show up ingame)</span>';
					} else {
						echo '<span style="color: red">' . mysqli_error($link) . '</span>';
					}
                } else {
                    $sql = mysqli_query($link, "SELECT * FROM `motd_manager` ORDER BY `time` DESC LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$row = mysqli_fetch_array($sql);
						
						$line1 = $row["first_line"];
						$line2 = $row["second_line"];
					}
                }

            ?>
            <form action="/content/motd/" method="post">
                <input name="line1" type="text" class="form-control" placeholder="Line 1" value="<?php print $line1; ?>"/><br/>
		    	<input name="line2" type="text" class="form-control" placeholder="Line 2" value="<?php print $line2; ?>"/><br/>

                <button type="submit" class="btn btn-success">Save changes</button>
            </form>
        </div>
    </div>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>