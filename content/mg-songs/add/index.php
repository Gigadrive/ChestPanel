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
    <h1>Add song</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["title"]) && isset($_POST["artist"])){
            if(!empty($_POST["title"]) && !empty($_POST["artist"])){
                if($_FILES["file"]["error"] == UPLOAD_ERR_OK){
                    $tmpName = realpath($_FILES["file"]["tmp_name"]);
                    $fileName = $_FILES["file"]["name"];

                    if(is_uploaded_file($tmpName)){
                        $title = $_POST["title"];
                        $artist = $_POST["artist"];
                        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

                        if($ext == 'nbs'){
                            $sql = mysqli_query($link,"INSERT INTO `mg_songs` (`title`,`artist`,`addedBy`,`songFile`) VALUES('" . mysqli_real_escape_string($link,$title) . "','" . mysqli_real_escape_string($link,$artist) . "','" . mysqli_real_escape_string($link,$currentPlayer->getUUID()) . "','" . mysqli_real_escape_string($link,file_get_contents($tmpName)) . "')");
                            if($sql){
                                $successMsg = "The file has been added!";
                            } else {
                                $errorMsg = mysqli_error($link);
                            }
                        } else {
                            $errorMsg = "Invalid file extension: " . $ext . "! Only .nbs files are allowed.";
                        }
                    } else {
                        $errorMsg = "That file was not uploaded via the form.";
                    }
                } else {
                    $errorMsg = "Uploading error: " . $_FILES["file"]["error"];
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
    <div class="alert alert-success" role="alert"><?php print $successMsg; ?><meta http-equiv="refresh" content="1; URL=/content/mg-songs/"/></div>
            <?php
        }

    ?>
    <form action="/content/mg-songs/add/" method="post" enctype="multipart/form-data">
        <input class="form-control" type="text" name="title" placeholder="Title"<?php if(isset($_POST["title"])){ ?> value="<?php print $_POST["title"]; ?>"<?php } ?>/><br/>
        <input class="form-control" type="text" name="artist" placeholder="Artist"<?php if(isset($_POST["artist"])){ ?> value="<?php print $_POST["artist"]; ?>"<?php } ?>/><br/>
        <small>.nbs Files only!</small><input class="form-control" type="file" name="file"/><br/>
        <button type="submit" class="btn btn-success">Add</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>