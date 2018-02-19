<?php require_once "../../assets/incl/top.inc.php"; ?>
<div class="container">
    <h1>Mutes</h1>
    <?php
		
		$limit      = 50;
    	$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		
		$query = "SELECT * FROM `sn_mutes` ORDER BY `time` DESC";
		
		$Paginator  = new Paginator( $link, $query );
	 	$results    = $Paginator->getData( $limit, $page );

        echo $Paginator->createLinks( 7, 'pagination justify-content-center' );

    ?>
    <table class="table">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Muted by</th>
            <th>Reason</th>
            <th>Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Unban Info</th>
        </tr>
        <?php

        if(count( $results->data ) > 0){
            for( $i = 0; $i < count( $results->data ); $i++ ){
                $row = $results->data[$i];
                $player = MinecraftPlayer::getByUUID($row["mutedPlayer"]);
                if($row["mutedBy"] != null) $enforcer = MinecraftPlayer::getByUUID($row["mutedBy"]);

                ?>
        <tr>
            <td><?php print $row["id"]; ?></td>
            <td><img src="https://crafatar.com/avatars/<?php print $player->getUUID(); ?>?overlay&size=30"/></td>
            <td><a href="/profile/?uuid=<?php print $player->getUUID(); ?>"><?php print $player->getUsername(); ?></a></td>
            <?php

            if($row["mutedBy"] != null){
                ?>
            <td><a href="/profile/?uuid=<?php print $enforcer->getUUID(); ?>"><?php print $enforcer->getUsername(); ?></a></td>
                <?php
            } else {
                ?>
            <td>BungeeConsole</td>
                <?php
            }

            ?>
            <td><?php if($row["reason"] == null){ echo '<i>none</i>'; } else { echo $row["reason"]; } ?></td>
            <td><?php print timeago($row["time"]); ?></td>
            <td><?php if($row["expiry"] != null){ print timeago($row["expiry"]); } else { print "N/A"; } ?></td>
            <td><?php if($row["active"] == true){ print 'active'; } else { print '<span style="color:green">unmuted</span>'; } ?></td>
            <td><?php
            
                if($row["active"] == true){
                    echo "-";
                } else {
                    if($row["unmute.staff"] == null){
                        echo "(expired)";
                    } else {
                        $unbanStaff = MinecraftPlayer::getByUUID($row["unmute.staff"]); 

                        ?>
                by <a href="/profile/?uuid=<?php print $unbanStaff->getUUID(); ?>"><?php print $unbanStaff->getUsername(); ?></a> (<?php print timeago($row["unmute.time"]); ?>)
                        <?php
                    }
                }

            ?></td>
        </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Player</th>
            <th>Muted by</th>
            <th>Reason</th>
            <th>Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Unmute Info</th>
        </tr>
    </table>
    <?php
		
		echo $Paginator->createLinks( 7, 'pagination justify-content-center' );
        
    ?>
</div>
<?php require_once "../../assets/incl/bottom.inc.php"; ?>