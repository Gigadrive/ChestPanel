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

$sel = mysqli_query($link,"SELECT * FROM `mg_songs` WHERE `id` = " . mysqli_real_escape_string($link,$id));
if(mysqli_num_rows($sel) > 0){
    $row = mysqli_fetch_array($sel);
    $id = $row["id"];
    $title = $row["title"];
    $artist = $row["artist"];
} else {
    echo "Invalid song ID.";
    echo "</div>";
    require_once "../../assets/incl/bottom.inc.php";
    exit();
}

?>
    <h1>Edit song</h1>
    <?php
        $successMsg = "";
        $errorMsg = "";

        if(isset($_POST["title"]) && isset($_POST["artist"])){
            if(!empty($_POST["title"]) && !empty($_POST["artist"])){
                if(isset($_POST["file"]) && !empty($_POST["file"])){
                    if($_FILES["file"]["error"] == UPLOAD_ERR_OK){
                        $tmpName = realpath($_FILES["file"]["tmp_name"]);
                        $fileName = $_FILES["file"]["name"];

                        if(is_uploaded_file($tmpName)){
                            $title = $_POST["title"];
                            $artist = $_POST["artist"];
                            $ext = pathinfo($fileName, PATHINFO_EXTENSION);

                            if($ext == 'nbs'){
                                $sql = mysqli_query($link,"UPDATE `mg_songs` SET `title` = '" . mysqli_real_escape_string($link,$title) . "', `artist` = '" . mysqli_real_escape_string($link,$artist) . "', `songFile` = '" . mysqli_real_escape_string($link,file_get_contents($tmpName)) . "' WHERE `id` = " . $id . ";");
                                if($sql){
                                    $successMsg = "The song has been updated!";
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
                    // user decided not to change file
                    $title = $_POST["title"];
                    $artist = $_POST["artist"];

                    $sql = mysqli_query($link,"UPDATE `mg_songs` SET `title` = '" . mysqli_real_escape_string($link,$title) . "', `artist` = '" . mysqli_real_escape_string($link,$artist) . "' WHERE `id` = " . $id . ";");
                    if($sql){
                        $successMsg = "The song has been updated!";
                    } else {
                        $errorMsg = mysqli_error($link);
                    }
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
    <form action="/content/mg-songs/edit/?id=<?php print $id; ?>" method="post" enctype="multipart/form-data">
        <input class="form-control" type="text" name="title" placeholder="Title" value="<?php print $title; ?>"/><br/>
        <input class="form-control" type="text" name="artist" placeholder="Artist" value="<?php print $artist; ?>"/><br/>
        <small>.nbs Files only!</small><input class="form-control" type="file" name="file"/><br/>
        <button type="submit" class="btn btn-success">Save changes</button>
    </form>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>