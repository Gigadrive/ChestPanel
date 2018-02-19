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
    <h1>Edit map</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["name"]) && isset($_POST["author"]) && isset($_POST["link"]) && isset($_POST["mapType"])){
            if(!empty($_POST["name"]) && !empty($_POST["author"]) && !empty($_POST["link"]) && !empty($_POST["mapType"])){
                $name = $_POST["name"];
                $author = $_POST["author"];
                $website = $_POST["link"];
                $mapType = $_POST["mapType"];

                $s = mysqli_query($link,"UPDATE `maps` SET `name` = '" . mysqli_real_escape_string($link,$name) . "', `author` = '" . mysqli_real_escape_string($link,$author) . "', `link` = '" . mysqli_real_escape_string($link,$website) . "', `mapType` = '" . mysqli_real_escape_string($link,$mapType) . "' WHERE `id` = " . $id . ";");
                if($s){
                    $successMsg = "The map has been updated!";
                } else {
                    $errorMsg = mysqli_error($link);
                }
            } else {
                $errorMsg = "You have to fill all fields.";
            }
        }

        if($errorMsg != ""){
            ?>
    <div class="alert alert-danger" role="alert"><?php print $errorMsg; ?></div>
            <?php
        }

        if($successMsg != ""){
            ?>
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?><meta http-equiv="refresh" content="1; URL=/content/maps/"/></div>
            <?php
        }

    ?>
    <form action="/content/maps/edit/?id=<?php print $id; ?>" method="post" enctype="multipart/form-data">
        <input class="form-control" type="text" name="name" placeholder="Map name" value="<?php print $name; ?>"/><br/>
        <input class="form-control" type="text" name="author" placeholder="Map author" value="<?php print $author; ?>"/><br/>
        <input class="form-control" type="text" name="link" placeholder="Map website" value="<?php print $website; ?>"/><br/>
        <select class="form-control" name="mapType">
            <option value="SURVIVAL_GAMES"<?php if($mapType == "SURVIVAL_GAMES"){ ?> selected<?php } ?>>Survival Games</option>
            <option value="DEATHMATCH"<?php if($mapType == "DEATHMATCH"){ ?> selected<?php } ?>>Death Match</option>
            <option value="KITPVP"<?php if($mapType == "KITPVP"){ ?> selected<?php } ?>>KitPvP</option>
            <option value="MUSICAL_GUESS"<?php if($mapType == "MUSICAL_GUESS"){ ?> selected<?php } ?>>Musical Guess</option>
            <option value="SG_DUELS"<?php if($mapType == "SG_DUELS"){ ?> selected<?php } ?>>Survival Games: Duels</option>
            <option value="INFECTION_WARS"<?php if($mapType == "INFECTION_WARS"){ ?> selected<?php } ?>>Infection Wars</option>
            <option value="TOBIKO"<?php if($mapType == "TOBIKO"){ ?> selected<?php } ?>>Tobiko</option>
            <option value="SG_SHOWDOWN"<?php if($mapType == "SG_SHOWDOWN"){ ?> selected<?php } ?>>Survival Games (Showdown)</option>
        </select><br/>
        <button type="submit" class="btn btn-success">Save changes</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>