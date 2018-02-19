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

$sel = mysqli_query($link,"SELECT * FROM `translations` WHERE `id` = " . mysqli_real_escape_string($link,$id));
if(mysqli_num_rows($sel) > 0){
    $row = mysqli_fetch_array($sel);
    $id = $row["id"];
    $en = $row["EN"];
    $originalEN = $en;
    $de = $row["DE"];
} else {
    echo "Invalid phrase ID.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Edit phrase</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["EN"]) && isset($_POST["DE"])){
            $en = $_POST["EN"];
            $de = $_POST["DE"];

            if(is_null($en) || empty($en) || $en == "") $en = null;
            if(is_null($de) || empty($de) || $de == "") $de = null;

            if($en != null){
                if($en == $originalEN || ($en != $originalEN && mysqli_num_rows(mysqli_query($link,"SELECT * FROM `translations` WHERE `EN` = '" . mysqli_real_escape_string($link,$en) . "'")) == 0)){
                    if($de != null){
                        $s = mysqli_query($link,"UPDATE `translations` SET `EN` = '" . mysqli_real_escape_string($link,$en) . "', `DE` = '" . mysqli_real_escape_string($link,$de) . "' WHERE `id` = " . mysqli_real_escape_string($link,$id) . ";");
                        if($s){
                            $successMsg = "The phrase has been updated.";
                        } else {
                            $errorMsg = mysqli_error($link);
                        }
                    } else if($de == null){
                        $s = mysqli_query($link,"UPDATE `translations` SET `EN` = '" . mysqli_real_escape_string($link,$en) . "', `DE` = NULL WHERE `id` = " . mysqli_real_escape_string($link,$id) . ";");
                        if($s){
                            $successMsg = "The phrase has been updated.";
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

        if($de == null) $de = "";
        if($en == null) $en = "";

    ?>
    <form action="/content/translations/edit/?id=<?php print $id; ?>" method="post">
        <input type="text" class="form-control" name="EN" placeholder="English" value="<?php print $en; ?>"/><br/>
        <input type="text" class="form-control" name="DE" placeholder="German" value="<?php print $de; ?>"/><br/>

        <button type="submit" class="btn btn-success">Save changes</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>