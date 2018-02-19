<?php require_once "../assets/incl/top.inc.php";

function s($i){
    global $link;
    $name = "statsQuery_" . $i;

    if(existsInCache($name)){
        return getFromCache($name);
    } else {
        $s = "";
        $result = false;

        switch($i){
            case 1:
                $s = "SELECT * FROM `users`";
                break;
            case 2:
                $s = "SELECT * FROM `sn_bans`";
                break;
            case 3:
                $s = "SELECT * FROM `sn_mutes`";
                break;
            case 4:
                $s = "SELECT * FROM `sn_bans` WHERE `active` = 1";
                break;
            case 5:
                $s = "SELECT * FROM `sn_mutes` WHERE `active` = 1";
                break;
            case 6:
                $s = "SELECT * FROM `sn_kicks`";
                break;
            case 7:
                $s = "SELECT * FROM `sg_stats`";
                break;
            case 8:
                $s = "SELECT * FROM `games` WHERE `game` = 'SURVIVAL_GAMES'";
                break;
            case 9:
                $s = "SELECT * FROM `maps` WHERE `mapType` = 'SURVIVAL_GAMES'";
                break;
            case 10:
                $s = "SELECT * FROM `logins`";
                break;
            case 11:
                $s = "SELECT * FROM `crews`";
                break;
            case 12:
                $s = "SELECT * FROM `crew_members`";
                break;
            case 13:
                $s = "SELECT * FROM `discord_connections`";
                break;
            case 14:
                $s = "SELECT * FROM `teamspeak_verify`";
                break;
            case 15:
                $s = "SELECT * FROM `alphaUsers`";
                break;
            case 16:
                $s = "SELECT * FROM `friendships`";
                break;
            case 17:
                $s = "SELECT * FROM `chatlogs`";
                break;
            case 18:
                $s = "SELECT * FROM `friend_requests`";
                break;
            case 19:
                $s = "SELECT * FROM `parties`";
                break;
            case 20:
                $s = "SELECT * FROM `unlocked_achievements`";
                break;
            case 21:
                $s = "SELECT * FROM `lobbyShop_boughtItems`";
                break;
            case 22:
                $s = "SELECT * FROM `games`";
                break;
            case 23:
                $s = "SELECT * FROM `mg_stats`";
                break;
            case 24:
                $s = "SELECT * FROM `games` WHERE `game` = 'MUSICAL_GUESS'";
                break;
            case 25:
                $s = "SELECT * FROM `soccer_stats`";
                break;
            case 26:
                $s = "SELECT * FROM `games` WHERE `game` = 'SOCCER'";
                break;
            case 27:
                $s = "SELECT * FROM `bg_stats`";
                break;
            case 28:
                $s = "SELECT * FROM `games` WHERE `game` = 'BUILD_AND_GUESS'";
                break;
            case 29:
                $s = "SELECT * FROM `bg_words`";
                break;
            case 30:
                $s = "SELECT * FROM `servers`";
                break;
            case 31:
                $s = "SELECT * FROM `kpvp_stats`";
                break;
            case 32:
                $s = "SELECT * FROM `maps` WHERE `mapType` = 'KITPVP'";
                break;
            case 33:
                $s = "SELECT sum(`points`) AS `result` FROM `kpvp_stats`";
                $result = true;
                break;
            case 34:
                $s = "SELECT sum(`kills`) AS `result` FROM `kpvp_stats`";
                $result = true;
                break;
            case 35:
                $s = "SELECT sum(`goals`) AS `result` FROM `soccer_stats`";
                $result = true;
                break;
            case 36:
                $s = "SELECT sum(`points`) AS `result` FROM `sg_stats`";
                $result = true;
                break;
            case 37:
                $s = "SELECT sum(`kills`) AS `result` FROM `sg_stats`";
                $result = true;
                break;
            case 38:
                $s = "SELECT `id` FROM `mg_songs`";
                break;
            case 39:
                $s = "SELECT * FROM `tk_stats`";
                break;
            case 40:
                $s = "SELECT * FROM `games` WHERE `game` = 'TOBIKO'";
                break;
            case 41:
                $s = "SELECT sum(`hits`) AS `result` FROM `tk_stats`";
                $result = true;
                break;
            case 42:
                $s = "SELECT sum(`finalHits`) AS `result` FROM `tk_stats`";
                $result = true;
                break;
            case 43:
                $s = "SELECT sum(`points`) AS `result` FROM `tk_stats`";
                $result = true;
                break;
            case 44:
                $s = "SELECT sum(`kills`) AS `result` FROM `tk_stats`";
                $result = true;
                break;
        }

        if($s != ""){
            if($result){
                $c = number_format(mysqli_fetch_array(mysqli_query($link,$s))["result"], 0, '', '.');
            } else {
                $c = number_format(mysqli_num_rows(mysqli_query($link,$s)), 0, '', '.');
            }

            setToCache($name,$c,5*60);

            return $c;
        } else {
            return 0;
        }
    }
}

?>
<div class="container">
    <h1 class="my-2">Statistics</h1>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#global" role="tab">Global</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#sg" role="tab">Survival Games</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#mg" role="tab">Musical Guess</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#kpvp" role="tab">KitPvP</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#bg" role="tab">Build &amp; Guess</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#soccer" role="tab">SoccerMC</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tobiko" role="tab">Tobiko</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#bans" role="tab">Ban System</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- GLOBAL -->
        <div class="tab-pane active" id="global" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Unique Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(1); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Unique Players (Alpha)</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(15); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Server Logins</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(10); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Crews</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(11); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Crew Members</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(12); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Linked Discord Accounts</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(13); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Linked TeamSpeak Identities</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(14); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Friendships</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(16); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Open Friend Requests</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(18); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Chatlogs</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(17); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Parties Created</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(19); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Unlocked Achievements</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(20); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Unlocked Vault Items</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(21); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Played Games</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(22); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SURVIVAL GAMES -->
        <div class="tab-pane" id="sg" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(7); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Played Games</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(8); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Maps</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(9); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Points Collected</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(36); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Kills Made</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(37); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MUSICAL GUESS -->
        <div class="tab-pane" id="mg" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(23); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Played Games</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(24); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Songs</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(38); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KITPVP -->
        <div class="tab-pane" id="kpvp" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(31); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Maps</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(32); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Kills Made</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(34); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Points Collected</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(33); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BUILD AND GUESS -->
        <div class="tab-pane" id="bg" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(27); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Played Games</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(28); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Words</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(29); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SOCCERMC -->
        <div class="tab-pane" id="soccer" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(25); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Played Games</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(26); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total goals</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(35); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TOBIKO -->
        <div class="tab-pane" id="tobiko" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Players</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(39); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Played Games</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(40); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total hits</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(41); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total final hits</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(42); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total points</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(43); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total kills</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(44); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BAN SYSTEM -->
        <div class="tab-pane" id="bans" role="tabpanel">
            <div class="card">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td width="50%" valign="top">
                                <b>Currently Active Bans</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(4); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Currently Active Mutes</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(5); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Bans</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Mutes</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(3); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" valign="top">
                                <b>Total Kicks</b>
                            </td>

                            <td width="50%" valign="top">
                                <?php print s(6); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <canvas id="modbans-barchart"></canvas>
        </div>
    </div>
</div>
<?php

$banCountArray = array();
$n = "modBansBarChartArray";
if(existsInCache($n)){
    $banCountArray = getFromCache($n);
} else {
    $s = mysqli_query($link,"SELECT COUNT(b.id) AS banCount,u.username FROM `users` AS u LEFT JOIN `sn_bans` AS b ON b.bannedBy = u.uuid WHERE u.rank IN ('ADMIN','CM','SR_MOD','MOD') GROUP BY u.uuid ORDER BY banCount DESC LIMIT 10;");
    if($s && mysqli_num_rows($s) > 0){
        while($row = mysqli_fetch_array($s)){
            $banCountArray[$row["username"]] = $row["banCount"];
        }

        setToCache($n,$banCountArray,5*60);
    }
}

?>
<script type="text/javascript">
    var ctx = document.getElementById("modbans-barchart").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
        data: {
            labels: [<?php foreach($banCountArray as $modName => $banCount){ ?>"<?php print $modName; ?>", <?php } ?>],
            datasets: [{
                label: '# of Bans',
                data: [<?php foreach($banCountArray as $modName => $banCount){  print $banCount; ?>, <?php } ?>],
                backgroundColor: '#5bc0de',
                borderColor: '#0275d8',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
            responsive: true
        }
    });
</script>
<?php require_once "../assets/incl/bottom.inc.php"; ?>