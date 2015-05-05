<?php

	exec("free | tail -2 | head -1 | awk {'print $3\" \"$4\" \"$7'}",$mem);
	exec("df",$storage);
	exec("/usr/bin/cut -d. -f1 /proc/uptime",$uptime);

	$uptime = $uptime[0];
	$mem = explode(" ", $mem[0]);
	$storage = explode(" ", $storage[5]);
	$storage = array($storage[6], $storage[7], $storage[9]);

	function convertSeconds($ss) 
	{
		$s = $ss%60;
		$m = floor(($ss%3600)/60);
		$h = floor(($ss%86400)/3600);
		$d = floor(($ss%2592000)/86400);
		$M = floor($ss/2592000);

		return "$M months, $d days, $h hours, $m minutes, $s seconds";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>VPS Usage</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="knob.js"></script>
		<script type="text/javascript">
			
		</script>
		<style type="text/css">
			* { padding: 0; margin: 0; }
			h2 {margin-bottom: 70px;}
			span { vertical-align: middle; }
			section { display: inline-block;}
			p { margin-top: 70px; }
			div {padding: 10px;}
		</style>
	</head>

	<body>
		<div>
			<section id="mem" data-fgColor="#66CC66" data-thickness=".26"><h2>RAM usage</h2><label></label></section>
			<section id="hdd" data-fgColor="#66CC66" data-thickness=".26"><h2>Disk usage</h2><label></label></section>
			<?php 
				echo "<p>";
				echo "RAM usage: ", number_format($mem[0],0,","," ")," kb out of ", number_format($mem[2],0,","," "), " kb used. Free: ",number_format($mem[1],0,","," ")," kb<br>"; 
				echo "HDD usage: ", number_format($storage[1],0,","," ")," kb out of ", number_format($storage[0],0,","," "), " kb used. Free: ",number_format($storage[2],0,","," "), " kb<br>";
				echo "Uptime:", convertSeconds($uptime);
				echo "</p>";
			?>
		</div>
		<script type="text/javascript">
			$("#mem").knob({
				'readOnly': true,
				'max': <?php echo $mem[2]; ?>
			});

			$("#hdd").knob({
				'readOnly': true,
				'max': <?php echo $storage[0]; ?>
			});

			$("#mem").val(<?php echo $mem[0]; ?>).trigger('change');
			$("#mem label").text(round(<?php echo $mem[0]; ?>/<?php echo $mem[2]; ?>*100) + "%");

			$("#hdd").val( <?php echo $storage[1]; ?>).trigger('change');
			$("#hdd label").text(round( <?php echo $storage[1]; ?>/<?php echo $storage[0]; ?>*100) + "%");

			function round(num) { return num.toFixed(3); }
		</script>
	</body>
</html>