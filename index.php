<?php
	define("DISPLAY", "knob"); //knob or meter
	define("REFRESH_TIME", 3); //in seconds
	define("DEBIAN", false); //is this file located on debian or not ?
	
	if(DEBIAN)
		exec(" free | grep \"buffers/cache\" | awk {'print $3+$4\" \"$3\" \"$4'}",$mem);
	else
		exec("free | grep \"Mem:\" | awk {'print $2\" \"$3\" \"$4'}",$mem);
	exec("cat /proc/loadavg | awk {'print $1\" \"$4'}",$cpu);
	exec("cat /proc/cpuinfo | grep \"model name\"",$cpuinfo);
	exec("df | grep ".((DEBIAN) ? "rootfs" : "/dev/simfs")." | awk {'print $2\" \"$3\" \"$4'}",$storage);
	exec("/usr/bin/cut -d. -f1 /proc/uptime",$uptime);
	exec("cat /proc/net/dev | grep ". ((DEBIAN) ? "eth0" : "venet0") ." | ". ((DEBIAN) ? "awk {'print $2\" \"$3\" \"$10\" \"$11'}": "awk {'print $1\" \"$2\" \"$9\" \"$10'}"),$network);
	
	$cpu = explode(" ", $cpu[0]);
	$processes = explode("/", $cpu[1]);
	$uptime = $uptime[0];
	$mem = explode(" ", $mem[0]);
	$storage = explode(" ", $storage[0]);
	$network = explode(" ", $network[0]);

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
		<style type="text/css">
			h2 {font-size: 30px; margin-bottom: 70px; margin-top: 0;}
			span { vertical-align: middle; }
			label { font-size: 25px; }
			section { display: inline-block;}
			div {padding: 10px;}
			meter { width: 100%; height: 30px;}
		</style>
	</head>

	<body>
		<div>
			<p><b>Note:</b>This page refreshes every <?php echo REFRESH_TIME; ?> second(s).</p>
			<?php if(DISPLAY == "knob"): ?>
			<section id="mem" data-thickness=".26"><h2>RAM usage</h2><label></label></section>
			<section id="hdd" data-thickness=".26"><h2>Disk usage</h2><label></label></section>
			<section id="cpu" data-fgColor="#66CC66" data-thickness=".26"><h2>CPU usage</h2><label></label></section>
			<p style="margin-top: 50px;">
			<?php elseif(DISPLAY == "meter"): ?>
				<label for="mem"></label><meter min="0" max="<?php echo $mem[0]; ?>" low="25" high="75" value="<?php echo $mem[1]; ?>"></meter>
				<label for="hdd"></label><meter min="0" max="<?php echo $storage[0]; ?>" low="25" high="75" value="<?php echo $storage[1]; ?>"></meter>
				<label for="cpu"></label><meter min="0" max="100" low="25" high="75" value="<?php echo $cpu[0]; ?>"></meter>
				<p>
			<?php
			endif; 
				echo "<b>RAM usage</b>:", number_format($mem[1],0,","," ")," kb out of ", number_format($mem[0],0,","," "), " kb used. Free: ",number_format($mem[2],0,","," ")," kb<br>"; 
				echo "<b>HDD usage</b>: ", number_format($storage[1],0,","," ")," kb out of ", number_format($storage[0],0,","," "), " kb used. Free: ",number_format($storage[2],0,","," "), " kb<br>";
				echo "<b>Uptime</b>:", convertSeconds($uptime), "<br>";
				echo "<b>Processes</b>:", $processes[0], " running, ", $processes[1], " sleeping<br>";
				echo "<b>CPU Info</b>:<ul>"; for ($i=0; $i < count($cpuinfo); $i++) echo "<li>", substr($cpuinfo[$i], 12), "</li>"; echo "</ul>";
				echo "<b>Network info</b>:<ul><li>Received: ", number_format((DEBIAN) ? $network[0] : substr($network[0], 7),0,","," "), " bytes (", number_format($network[1],0,","," ")," packets)</li><li>Sent: ", number_format($network[2],0,","," ")," bytes (", number_format($network[3],0,","," "), " packets)</li></ul>";
				echo "</ul></p>";
			?>
		</div>
		<script type="text/javascript">
			$("#mem").knob({'readOnly': true, 'max': <?php echo $mem[0]; ?>	});
			$("#hdd").knob({'readOnly': true, 'max': <?php echo $storage[0]; ?>	});
			$("#cpu").knob({'readOnly': true, 'max': 100 });
			
			$("#mem").val(<?php echo $mem[1]; ?>).trigger('change');
			$("#mem label").text(round(<?php echo $mem[1]; ?>/<?php echo $mem[0]; ?>*100) + "%");
			$("label[for=mem]").text(round(<?php echo $mem[1]; ?>/<?php echo $mem[0]; ?>*100) + "%");

			$("#hdd").val( <?php echo $storage[1]; ?>).trigger('change');
			$("#hdd label").text(round( <?php echo $storage[1]; ?>/<?php echo $storage[0]; ?>*100) + "%");
			$("label[for=hdd]").text(round( <?php echo $storage[1]; ?>/<?php echo $storage[0]; ?>*100) + "%");

			var cpuusage = round((<?php echo $cpu[0]; ?>*100) / <?php echo count($cpuinfo); ?>,1);
			$("#cpu").val(cpuusage).trigger('change');
			$("#cpu label").text(cpuusage+"%");
			$("label[for=cpu]").text(cpuusage+"%");

			function round(num,fix) 
			{ 
				fix = (typeof fix === 'undefined') ? 3 : fix; 
				return num.toFixed(fix); 
			}

			$(document).ready(function() {
				setTimeout(function(){ location.reload(); }, <?php echo REFRESH_TIME*1000; ?>);
			});
		</script>
	</body>
</html>
