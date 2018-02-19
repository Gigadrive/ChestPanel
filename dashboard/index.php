<?php require_once "../assets/incl/top.inc.php"; ?>
<div class="container">
	<?php

		$openReports = 0;
		$s = mysqli_query($link,"SELECT * FROM `sn_reports` WHERE `solved.status` = 'OPEN'");
		if($s){
			$openReports = mysqli_num_rows($s);
		}

		if($openReports > 0){
			?>
	<div class="alert alert-danger my-2" role="alert">
		<?php
		
		if($openReports == 1){
			print 'There is <b>' . $openReports . '</b> open report! <a href="/ban-management/reports" class="alert-link">Click here to check it</a>.';
		} else {
			print 'There are <b>' . $openReports . '</b> open reports! <a href="/ban-management/reports" class="alert-link">Click here to check them</a>.';
		} ?>
	</div>
			<?php
		}

	?>
    <div class="row my-3">
		<div class="col-lg-3">
			<div class="card card-inverse card-primary text-center">
				<div class="card-header">New users today</div>
				<h3><?php

				$cacheTime = 60;
				
				$count = 0;
				$n = "dashboard_newUsersToday";
				if(existsInCache($n)){
					$count = getFromCache($n);
				} else {
					$s = mysqli_query($link,"SELECT * FROM `users` WHERE DATE(`firstjoin`) = CURDATE()");
					if($s){
						$count = mysqli_num_rows($s);
						setToCache($n,$count,$cacheTime);
					}
				}

				$count = number_format($count, 0, '', '.');;

				echo $count;

				?></h3>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="card card-inverse card-success text-center">
				<div class="card-header">Logins today</div>
				<h3><?php
				
				$count = 0;
				$n = "dashboard_loginsToday";
				if(existsInCache($n)){
					$count = getFromCache($n);
				} else {
					$s = mysqli_query($link,"SELECT * FROM `logins` WHERE DATE(`time`) = CURDATE()");
					if($s){
						$count = mysqli_num_rows($s);
						setToCache($n,$count,$cacheTime);
					}
				}

				$count = number_format($count, 0, '', '.');;

				echo $count;

				?></h3>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="card card-inverse card-info text-center">
				<div class="card-header">Players online</div>
				<h3><?php
				
				$info = null;
				$n = "dashboard_serverPing";
				if(existsInCache($n)){
					$info = getFromCache($n);
				} else {
					$ping = new xPaw\MinecraftPing("thechest.eu",25565,1);
					$info = $ping->Query();

					setToCache($n,$info,$cacheTime);
				}

				if($info != null && $info != false){
					if(array_key_exists("players",$info)){
						$playerInfo = $info["players"];

						if(array_key_exists("max",$playerInfo) && array_key_exists("online",$playerInfo)){
							echo $playerInfo["online"] . "/" . $playerInfo["max"];
						} else {
							echo "-/-";
						}
					} else {
						echo "-/-";
					}
				} else {
					echo "-/-";
				}

				?></h3>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="card card-inverse card-danger text-center">
				<div class="card-header">Player peak today</div>
				<h3><?php
				
				$count = 0;
				$n = "dashboard_playerPeakToday";
				if(existsInCache($n)){
					$count = getFromCache($n);
				} else {
					$s = mysqli_query($link,"SELECT * FROM `playerCountPeaks` WHERE `date` = '" . mysqli_real_escape_string($link,date("Y") . "-" . date("m") . "-" . date("d")) . "'");
					if($s){
						$r = mysqli_fetch_array($s);
						$count = $r["count"];

						setToCache($n,$count,$cacheTime);
					}
				}

				$count = number_format($count, 0, '', '.');;

				echo $count;

				?></h3>
			</div>
		</div>
	</div>

	<hr/>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-inverse card-info">
                <div class="card-header"><i class="fa fa-map-marker"></i> Player Locations</div>
                <div id="world-map" style="width: 100%; height: 250px; margin: 0px"></div>
            </div>

			<div class="card my-3">
				<div class="card-header"><i class="fa fa-user"></i> Player peaks</div>
				<div class="chart" id="peak-chart" style="position: relative; height: 300px;"></div>
			</div>
        </div>

        <div class="col-lg-4">
			<div class="card my-3">
				<div class="card-header"><i class="fa fa-user"></i> User-gamemode spread</div>
				<canvas id="user-chart"></canvas>
			</div>

			<div class="card my-3">
				<div class="card-header"><i class="fa fa-clock-o"></i> Version uses</div>
				<canvas id="version-chart"></canvas>
			</div>
        </div>
    </div>
</div>

<script type="text/javascript">
	<?php

		$cacheTime = 60*3;

		$sgUsers = 0;
		$mgUsers = 0;
		$dmUsers = 0;
		$kpvpUsers = 0;
		$soccerUsers = 0;
		$bgUsers = 0;
		$infwUsers = 0;
		$tkUsers = 0;

		$n = "userAmount_sg";
		if(existsInCache($n)){
			$sgUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `sg_stats`");
			if($s){
				$sgUsers = mysqli_num_rows($s);
				setToCache($n,$sgUsers,$cacheTime);
			}
		}

		$n = "userAmount_mg";
		if(existsInCache($n)){
			$mgUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `mg_stats`");
			if($s){
				$mgUsers = mysqli_num_rows($s);
				setToCache($n,$mgUsers,$cacheTime);
			}
		}

		$n = "userAmount_dm";
		if(existsInCache($n)){
			$dmUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `dm_stats`");
			if($s){
				$dmUsers = mysqli_num_rows($s);
				setToCache($n,$dmUsers,$cacheTime);
			}
		}

		$n = "userAmount_kpvp";
		if(existsInCache($n)){
			$kpvpUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `kpvp_stats`");
			if($s){
				$kpvpUsers = mysqli_num_rows($s);
				setToCache($n,$kpvpUsers,$cacheTime);
			}
		}

		$n = "userAmount_soccer";
		if(existsInCache($n)){
			$soccerUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `soccer_stats`");
			if($s){
				$soccerUsers = mysqli_num_rows($s);
				setToCache($n,$soccerUsers,$cacheTime);
			}
		}

		$n = "userAmount_bg";
		if(existsInCache($n)){
			$bgUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `bg_stats`");
			if($s){
				$bgUsers = mysqli_num_rows($s);
				setToCache($n,$bgUsers,$cacheTime);
			}
		}

		$n = "userAmount_infw";
		if(existsInCache($n)){
			$infwUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `infw_stats`");
			if($s){
				$infwUsers = mysqli_num_rows($s);
				setToCache($n,$infwUsers,$cacheTime);
			}
		}

		$n = "userAmount_tk";
		if(existsInCache($n)){
			$tkUsers = getFromCache($n);
		} else {
			$s = mysqli_query($link,"SELECT * FROM `tk_stats`");
			if($s){
				$tkUsers = mysqli_num_rows($s);
				setToCache($n,$tkUsers,$cacheTime);
			}
		}

	?>

	new Chart(document.getElementById("user-chart").getContext('2d'),{
		type: 'pie',
		data: {
			datasets: [{
				data: [<?php print $sgUsers; ?>,<?php print $mgUsers; ?>,<?php print $kpvpUsers; ?>,<?php print $soccerUsers; ?>,<?php print $dmUsers; ?>,<?php print $bgUsers; ?>,<?php print $tkUsers; ?>,<?php print $infwUsers; ?>],
				label: "# of Players",
				backgroundColor: [
					"#c10909", "#072f8e", "#e59109", "#08c0e5", "#1862a3", "#640582", "#F266F0", "#095b0a"
				]
			}],

			labels: [
				"Survival Games",
				"Musical Guess",
				"KitPvP",
				"SoccerMC",
				"DeathMatch",
				"Build & Guess",
				"Tobiko",
				"Infection Wars"
			]
		},
		options: {
            responsive: true
        }
	});

	<?php

		function g($v){
			global $link;
			$n = "userVersionSpread_" . $v;

			if(existsInCache($n)){
				return getFromCache($n);
			} else {
				$s = mysqli_query($link,"SELECT * FROM `users` WHERE `lastMCVersion` = " . $v);
				if($s){
					$num = mysqli_num_rows($s);

					setToCache($n,$num,5*60);
					return $num;
				} else {
					return 0;
				}
			}
		}

	?>

	new Chart(document.getElementById("version-chart").getContext('2d'),{
		type: 'pie',
		data: {
			datasets: [{
				data: [<?php print g(47); ?>,<?php print g(107); ?>,<?php print g(108); ?>,<?php print g(109); ?>,<?php print g(110); ?>,<?php print g(310); ?>,<?php print g(315); ?>,<?php print g(316); ?>,<?php print g(335); ?>,<?php print g(338); ?>],
				label: "# of Players",
				backgroundColor: [
					"#1C7C9C", "#071785", "#07854A", "#4C8507", "#856707", "#4EE7F2", "#F24EE5", "#6B1919", "#F50F0F", "#2CE85E"
				]
			}],

			labels: [
				"1.8.X",
				"1.9",
				"1.9.1",
				"1.9.2",
				"1.9.4",
				"1.10.X",
				"1.11",
				"1.11.2",
				"1.12",
				"1.12.1"
			]
		},
		options: {
            responsive: true
        }
	});

	Morris.Area({
		element: 'peak-chart',
		data: [
			<?php

				$s = mysqli_query($link,"SELECT * FROM `playerCountPeaks` ORDER BY `date` DESC LIMIT 7");
				if(mysqli_num_rows($s) > 0){
					while($row = mysqli_fetch_array($s)){
						?>
			{ date: '<?php print $row["date"]; ?>', peak: <?php print $row["count"]; ?> }, 
						<?php
					}
				}

			?>
		],
		xkey: 'date',
		ykeys: ['peak'],
		labels: ['Player Peak']
	});

    var visitorsData = {
	<?php 
	foreach ($countrycodes as $key => $countryCode) {
		print '"' . $countryCode . '": ' . getVisitoryFromCountry($countryCode) . ",";  
	}
	?>
    };
    $('#world-map').vectorMap({
	    map: 'world_mill_en',
	    backgroundColor: "transparent",
	    regionStyle: {
	      initial: {
	        fill: 'white',
	        "fill-opacity": 1,
	        stroke: 'none',
	        "stroke-width": 0,
	        "stroke-opacity": 1
	      },
	      hover: {
		    "fill-opacity": 0.8,
		    cursor: 'pointer'
		  },
		  selected: {
		    fill: 'yellow'
		  },
		  selectedHover: {
		  }
	    },
	    series: {
	      regions: [{
	          values: visitorsData,
	          scale: ['#C8EEFF', '#0071A4'],
              normalizeFunction: 'polynomial'
	        }]
	    },
	    onRegionLabelShow: function (e, el, code) {
	      if (typeof visitorsData[code] != "undefined")
	        el.html(el.html() + ': ' + visitorsData[code] + ' total players');
	    }
	  });
</script>
<?php require_once "../assets/incl/bottom.inc.php"; ?>