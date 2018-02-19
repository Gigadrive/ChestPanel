<?php require_once "../../../assets/incl/top.inc.php"; ?>
<div class="container">
<?php

if(convertRankToID($currentPlayer->getRank()) < 10){
    echo "You are not permitted to view this page.";
    echo "</div>";
    require_once "../../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Add shortened url</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["id"]) && isset($_POST["link"])){
            if(!empty($_POST["id"]) && !empty($_POST["link"])){
                $id = $_POST["id"];
                $urllink = $_POST["link"];

                $s = mysqli_query($link,"INSERT INTO `shortened_urls` (`id`,`link`) VALUES('" . mysqli_real_escape_string($link,$id) . "','" . mysqli_real_escape_string($link,$urllink) . "')");
                if($s){
                    $successMsg = "The shortened url has been added!";
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
    <form action="/content/urlshortener/add/" method="post" enctype="multipart/form-data">
        <input class="form-control" type="text" name="id" placeholder="ID"<?php if(isset($_POST["id"])){ ?> value="<?php print $_POST["id"]; ?>"<?php } ?>/><br/>
        <input class="form-control" type="text" name="link" placeholder="Link to shorten"<?php if(isset($_POST["link"])){ ?> value="<?php print $_POST["link"]; ?>"<?php } ?>/><br/>
        <button type="submit" class="btn btn-success">Add</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>