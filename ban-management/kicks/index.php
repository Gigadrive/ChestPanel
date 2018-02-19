<?php require_once "../../assets/incl/top.inc.php"; ?>
<div class="container">
    <h1>Kicks</h1>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		$query = "SELECT * FROM `sn_kicks` ORDER BY `time` DESC";
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Kicked by</th>
            <th>Reason</th>
            <th>Name when Kicked</th>
            <th>Server</th>
            <th>Date</th>
        </tr>
        <?php

        if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                $player = MinecraftPlayer::getByUUID($row["kickedPlayer"]);
                if($row["kickedBy"] != null) $enforcer = MinecraftPlayer::getByUUID($row["kickedBy"]);

                ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><img src="https://crafatar.com/avatars/<?php print $player->getUUID(); ?>?overlay&size=30"/></td>
            <td><a href="/profile/?uuid=<?php print $player->getUUID(); ?>"><?php print $player->getUsername(); ?></a></td>
            <?php

            if($row["kickedBy"] != null){
                ?>
            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                <?php
            } else {
                ?>
            <td>BungeeConsole</td>
                <?php
            }

            ?>
            <td><?php print $row["reason"]; ?></td>
            <td><?php print $row["nameWhenKicked"]; ?></td>
            <td><?php print $row["server"]; ?></td>
            <td><?php print timeago($row["time"]); ?></td>
        </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Kicked by</th>
            <th>Reason</th>
            <th>Name when Kicked</th>
            <th>Server</th>
            <th>Date</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>