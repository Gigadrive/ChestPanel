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
    <h1>Map Statistics: <?php print $name; ?></h1>
    <div class="card">
        <div class="card-header">
            Ratings
        </div>

        <div class="card-block">
            <?php

                $s = mysqli_query($link,"SELECT * FROM `map_ratings` WHERE `mapID` = " . mysqli_real_escape_string($link,$id));
                if($s){
                    $total = mysqli_num_rows($s);
                    $five = 0;
                    $four = 0;
                    $three = 0;
                    $two = 0;
                    $one = 0;

                    while($row = mysqli_fetch_array($s)){
                        if($row["rating"] == 5){
                            $five++;
                        } else if($row["rating"] == 4){
                            $four++;
                        } else if($row["rating"] == 3){
                            $three++;
                        } else if($row["rating"] == 2){
                            $two++;
                        } else if($row["rating"] == 1){
                            $one++;
                        }
                    }
                    ?>
            <p>Total ratings: <b><?php print $total; ?></b></p>

                    <?php
                        $percentage = 0;
                        if($total > 0){
                            $percentage = ($five/$total)*100;
                        }
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php print $percentage; ?>%;" aria-valuenow="<?php print $percentage; ?>" aria-valuemin="0" aria-valuemax="100">5 (<?php print $percentage; ?>%)</div>
                    </div>

                    <?php
                        $percentage = 0;
                        if($total > 0){
                            $percentage = ($four/$total)*100;
                        }
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php print $percentage; ?>%;" aria-valuenow="<?php print $percentage; ?>" aria-valuemin="0" aria-valuemax="100">4 (<?php print $percentage; ?>%)</div>
                    </div>

                    <?php
                        $percentage = 0;
                        if($total > 0){
                            $percentage = ($three/$total)*100;
                        }
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php print $percentage; ?>%;" aria-valuenow="<?php print $percentage; ?>" aria-valuemin="0" aria-valuemax="100">3 (<?php print $percentage; ?>%)</div>
                    </div>

                    <?php
                        $percentage = 0;
                        if($total > 0){
                            $percentage = ($two/$total)*100;
                        }
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php print $percentage; ?>%;" aria-valuenow="<?php print $percentage; ?>" aria-valuemin="0" aria-valuemax="100">2 (<?php print $percentage; ?>%)</div>
                    </div>

                    <?php
                        $percentage = 0;
                        if($total > 0){
                            $percentage = ($one/$total)*100;
                        }
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php print $percentage; ?>%;" aria-valuenow="<?php print $percentage; ?>" aria-valuemin="0" aria-valuemax="100">1 (<?php print $percentage; ?>%)</div>
                    </div>
                    <?php
                } else {
                    echo '<span style="color: red">An error occured: ' . mysqli_error($link) . '</span>';
                }

            ?>
        </div>
    </div>
</div>
<?php require_once "../../../assets/incl/bottom.inc.php"; ?>