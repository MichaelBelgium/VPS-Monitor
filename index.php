<?php
	define("DISPLAY", "knob");
	
	exec(" free | grep \"Mem:\" | awk {'print $2\" \"$3\" \"$4'}",$mem);
	exec("cat /proc/loadavg | awk {'print $1\" \"$4'}",$cpu); //or "ps -aux | awk {'print $3'}"
	exec("cat /proc/cpuinfo | grep \"model name\"",$cpuinfo);
	exec("df | grep /dev/simfs | awk {'print $2\" \"$3\" \"$4'}df",$storage);
	exec("/usr/bin/cut -d. -f1 /proc/uptime",$uptime);

	$cpu = explode(" ", $cpu[0]);
	$processes = explode("/", $cpu[1]);
	$uptime = $uptime[0];
	$mem = explode(" ", $mem[0]);
	$storage = explode(" ", $storage[0]);

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
			h2 {font-size: 30px; margin-bottom: 70px; margin-top: 0;}
			span { vertical-align: middle; }
			label { font-size: 25px; }
			section { display: inline-block;}
			div {padding: 10px;}
			progress {width: 1000px; display: block; margin-bottom: 5px;}
		</style>
	</head>

	<body>
		<div>
			<?php if(DISPLAY == "knob"): ?>
			<section id="mem" data-thickness=".26"><h2>RAM usage</h2><label></label></section>
			<section id="hdd" data-thickness=".26"><h2>Disk usage</h2><label></label></section>
			<section id="cpu" data-fgColor="#66CC66" data-thickness=".26"><h2>CPU usage</h2><label></label></section>
			<p style="margin-top: 50px;">
			<?php elseif(DISPLAY == "progress"): ?>
				<progress value="<?php echo $mem[1]; ?>" max="<?php echo $mem[0]; ?>"></progress>
				<progress value="<?php echo $storage[1]; ?>" max="<?php echo $storage[0]; ?>"></progress>
				<progress value="<?php echo $cpu[0]*100; ?>" max="100"></progress>
				<p>
			<?php
			endif; 
				echo "<b>RAM usage</b>:", number_format($mem[1],0,","," ")," kb out of ", number_format($mem[0],0,","," "), " kb used. Free: ",number_format($mem[2],0,","," ")," kb<br>"; 
				echo "<b>HDD usage</b>: ", number_format($storage[1],0,","," ")," kb out of ", number_format($storage[0],0,","," "), " kb used. Free: ",number_format($storage[2],0,","," "), " kb<br>";
				echo "<b>Uptime</b>:", convertSeconds($uptime), "<br>";
				echo "<b>Processes</b>:", $processes[0], " running, ", $processes[1], " sleeping<br>";
				echo "<b>CPU Info</b>:<ul>"; for ($i=0; $i < count($cpuinfo); $i++) echo "<li>", substr($cpuinfo[$i], 12), "</li>";
				echo "</ul></p>";
			?>
		</div>
		<script type="text/javascript">
			$("#mem").knob({
				'readOnly': true,
				'max': <?php echo $mem[0]; ?>
			});

			$("#hdd").knob({
				'readOnly': true,
				'max': <?php echo $storage[0]; ?>
			});
			
			$("#cpu").knob({
				'readOnly': true,
				'max': 100
			});
			
			$("#mem").val(<?php echo $mem[1]; ?>).trigger('change');
			$("#mem label").text(round(<?php echo $mem[1]; ?>/<?php echo $mem[0]; ?>*100) + "%");

			$("#hdd").val( <?php echo $storage[1]; ?>).trigger('change');
			$("#hdd label").text(round( <?php echo $storage[1]; ?>/<?php echo $storage[0]; ?>*100) + "%");

			var cpuusage = round((<?php echo $cpu[0]; ?>*100) / <?php echo count($cpuinfo); ?>,1);
			$("#cpu").val(cpuusage).trigger('change');
			$("#cpu label").text(cpuusage+"%");
			function round(num,fix) 
			{ 
				fix = (typeof fix === 'undefined') ? 3 : fix; 
				return num.toFixed(fix); 
			}

			$(document).ready(function() {
				setTimeout(function(){ location.reload(); }, 1000);
			});
		</script>
	</body>
</html>