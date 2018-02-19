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
    <h1>Badwords</h1>
    <?php

        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["action"])){
            $action = $_POST["action"];

            if($action == "add"){
                if(isset($_POST["word"])){
                    $word = $_POST["word"];

                    $s = mysqli_query($link,"INSERT INTO `badwords` (`word`,`added_by`) VALUES('" . mysqli_real_escape_string($link,$word) . "','" . mysqli_real_escape_string($link,$currentPlayer->getUUID()) . "')");
                    if($s){
                        $successMsg = "The word has been added to the filter.";
                    } else {
                        $errorMsg = mysqli_error($link);
                    }
                }
            } else if($action == "delete"){
                if(isset($_POST["word"])){
                    $word = $_POST["word"];

                    $s = mysqli_query($link,"DELETE FROM `badwords` WHERE `word` = '" . mysqli_real_escape_string($link,$word) . "'");
                    if($s){
                        $successMsg = "The word has been removed from the filter.";
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
            Add badword to filter
        </div>
        <div class="card-block">
            <form action="/content/badwords/" method="post">
                <input type="hidden" name="action" value="add"/>
                <input type="text" class="form-control" name="word" placeholder="Enter badword to add" autofocus/><br/>

                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Current badwords
        </div>
        <div class="card-block">
            <table class="table">
                <tr>
                    <th>Word</th>
                    <th>Added by</th>
                    <th>Date</th>
                    <th>&nbsp;</th>
                </tr>

                <?php

                    $sql = mysqli_query($link,"SELECT * FROM `badwords` ORDER BY `word` ASC");
                    if(mysqli_num_rows($sql) > 0){
                        while($row = mysqli_fetch_array($sql)){
                            $player = MinecraftPlayer::getByUUID($row["added_by"]);
                            ?>
                <tr>
                    <td><?php print $row["word"]; ?></td>
                    <td><span style="color: <?php print getRankColor($player->getRank()); ?>"><?php print $player->getUsername(); ?></span></td>
                    <td><?php print timeago($row["time_added"]); ?></td>
                    <td><form action="/content/badwords/" method="post"><button type="submit" class="btn btn-danger">Delete</button><input type="hidden" name="word" value="<?php print $row["word"]; ?>"/><input type="hidden" name="action" value="delete"/></form></td>
                </tr>
                            <?php
                        }
                    }

                ?>
            </table>
        </div>
    </div>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>