<?php require_once "../../../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

if(convertRankToID($currentPlayer->getRank()) < 10){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../../../assets/incl/bottom.inc.php";
    exit();
}

if(!isset($_GET["id"]) || (isset($_GET["id"]) && empty($_GET["id"]))){
    echo "Invalid phrase ID.";
    echo "</div>";
    require_once "../../../assets/incl/bottom.inc.php";
    exit();
}

$id = $_GET["id"];
?>
    <h1>Delete phrase</h1>
    <?php

        $successMsg = "";
        $errorMsg = "";

        $s = mysqli_query($link,"DELETE FROM `translations` WHERE `id` = " . mysqli_real_escape_string($link,$id));
        if($s){
            $successMsg = "Phrase deleted.";
        } else {
            $errorMsg = mysqli_error($link);
        }

        if($errorMsg != ""){
            ?>
    <div class="alert alert-danger" role="alert"><?php print $errorMsg; ?></div>
            <?php
        }

        if($successMsg != ""){
            ?>
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?><meta http-equiv="refresh" content="1; URL=/content/translations/"/></div>
            <?php
        }

    ?>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>