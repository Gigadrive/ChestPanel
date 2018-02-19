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

$sel = mysqli_query($link,"SELECT * FROM `achievements` WHERE `id` = " . mysqli_real_escape_string($link,$id));
if(mysqli_num_rows($sel) > 0){
    $row = mysqli_fetch_array($sel);
    $id = $row["id"];
    $title = $row["title"];
    $description = $row["description"];
    $category = $row["category"];
} else {
    echo "Invalid achievement ID.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Edit achievement</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["category"])){
            if(!empty($_POST["title"]) && !empty($_POST["description"]) && !empty($_POST["description"]) && ($_POST["category"] == "GENERAL" || $_POST["category"] == "LOBBY" || $_POST["category"] == "SURVIVAL_GAMES" || $_POST["category"] == "KITPVP" || $_POST["category"] == "DEATHMATCH" || $_POST["category"] == "MUSICALGUESS" || $_POST["category"] == "BUILD_GUESS" || $_POST["category"] == "SOCCER" || $_POST["category"] == "GUNGAME" || $_POST["category"] == "SGDUELS" || $_POST["category"] == "INFECTIONWARS" || $_POST["category"] == "TOBIKO")){
                $title = $_POST["title"];
                $description = $_POST["description"];
                $category = $_POST["category"];

                $s = mysqli_query($link,"UPDATE `achievements` SET `title` = '" . mysqli_real_escape_string($link,$title) . "',`description` = '" . mysqli_real_escape_string($link,$description) . "',`category` = '" . mysqli_real_escape_string($link,$category) . "' WHERE `id` = " . $id . ";");
                if($s){
                    $successMsg = "The achievement has been updated!";
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
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?><meta http-equiv="refresh" content="1; URL=/content/achievements/"/></div>
            <?php
        }

    ?>
    <form action="/content/achievements/edit/?id=<?php print $id; ?>" method="post" enctype="multipart/form-data">
        <input class="form-control" type="text" name="title" placeholder="Title" value="<?php print $title; ?>"/><br/>
        <input class="form-control" type="text" name="description" placeholder="Description" value="<?php print $description; ?>"/><br/>
        <select name="category" class="form-control">
            <option value="GENERAL"<?php if($category == "GENERAL"){ ?> selected<?php } ?>>General</option>
            <option value="LOBBY"<?php if($category == "LOBBY"){ ?> selected<?php } ?>>Hub</option>
            <option value="SURVIVAL_GAMES"<?php if($category == "SURVIVAL_GAMES"){ ?> selected<?php } ?>>Survival Games</option>
            <option value="KITPVP"<?php if($category == "KITPVP"){ ?> selected<?php } ?>>KitPvP</option>
            <option value="DEATHMATCH"<?php if($category == "DEATHMATCH"){ ?> selected<?php } ?>>DeathMatch</option>
            <option value="MUSICALGUESS"<?php if($category == "MUSICALGUESS"){ ?> selected<?php } ?>>Musical Guess</option>
            <option value="BUILD_GUESS"<?php if($category == "BUILD_GUESS"){ ?> selected<?php } ?>>Build &amp; Guess</option>
            <option value="SOCCER"<?php if($category == "SOCCER"){ ?> selected<?php } ?>>SoccerMC</option>
            <!--<option value="GUNGAME"<?php if($category == "GUNGAME"){ ?> selected<?php } ?>>GunGame</option>-->
            <option value="SGDUELS"<?php if($category == "SGDUELS"){ ?> selected<?php } ?>>Survival Games: Duels</option>
            <option value="INFECTIONWARS"<?php if($category == "INFECTIONWARS"){ ?> selected<?php } ?>>Infection Wars</option>
            <option value="TOBIKO"<?php if($category == "TOBIKO"){ ?> selected<?php } ?>>Tobiko</option>
        </select><br/>
        <button type="submit" class="btn btn-success">Save changes</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>