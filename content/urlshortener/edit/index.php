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

$sel = mysqli_query($link,"SELECT * FROM `shortened_urls` WHERE `id` = '" . mysqli_real_escape_string($link,$id) . "'");
if(mysqli_num_rows($sel) > 0){
    $row = mysqli_fetch_array($sel);
    $id = $row["id"];
    $urllink = $row["link"];
} else {
    echo "Invalid url ID.";
    echo "</div>";
    require_once "../../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Edit shortened URLs</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["id"]) && isset($_POST["link"])){
            if(!empty($_POST["id"]) && !empty($_POST["link"])){
                $id = $_POST["id"];
                $urllink = $_POST["link"];

                $s = mysqli_query($link,"UPDATE `shortened_urls` SET `id` = '" . mysqli_real_escape_string($link,$id) . "',`link` = '" . mysqli_real_escape_string($link,$urllink) . "' WHERE `id` = '" . mysqli_real_escape_string($link,$id) . "';");
                if($s){
                    $successMsg = "The URL has been updated!";
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
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?><meta http-equiv="refresh" content="1; URL=/content/urlshortener/"/></div>
            <?php
        }

    ?>
    <form action="/content/urlshortener/edit/?id=<?php print $id; ?>" method="post" enctype="multipart/form-data">
        <input class="form-control" type="text" name="id" placeholder="ID" value="<?php print $id; ?>"/><br/>
        <input class="form-control" type="text" name="link" placeholder="Link" value="<?php print $urllink; ?>"/><br/>
        <button type="submit" class="btn btn-success">Save changes</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>