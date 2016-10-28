<?php
	define("REFRESH_TIME", 3); //in seconds
?>
<!DOCTYPE html>
<html>
	<head>
		<title>VPS Usage</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="https://rawgit.com/aterrien/jQuery-Knob/master/js/jquery.knob.js"></script>
		<style type="text/css">
			article { display: inline-block; }
			article h2 	{ font-size: 30px;	margin: 0;	}
			article span { display: inline-block;  margin-top: 75px; font-size: 20px;}
			#content { margin-top: 40px; }
		</style>
	</head>

	<body>
		<div class="main">
			<p>
				<b>Note:</b>This page refreshes every <?php echo REFRESH_TIME; ?> second(s).
			</p>

			<article id="mem" data-thickness=".26">
				<h2>RAM usage</h2>
				<span></span>
			</article>
			<article id="hdd" data-thickness=".26">
				<h2>HDD Usage</h2>
				<span></span>
			</article>
			<article id="cpu" data-fgColor="#66CC66" data-thickness=".26">
				<h2>CPU Load</h2>
				<span></span>
			</article>

			<p id="content"></p>
		</div>
		<script type="text/javascript">
		$(document).ready(function() 
		{
			setInterval(refresh, <?php echo REFRESH_TIME*1000; ?>);
			refresh();

			$("#mem").knob();
			$("#hdd").knob();
			$("#cpu").knob({'readOnly': true, 'max': 100 });
		});

		function refresh()
		{
			$.getJSON("getData.php", "", success);
		}

		function success(data, textStatus, jqXHR)
		{
			$("#mem").trigger("configure",{"min":0, "max": data.mem[0] });
			$("#hdd").trigger("configure",{"min":0, "max": data.storage[0] });

			$("#mem").val(data.mem[1]).trigger("change");
			$("#mem span").text(((data.mem[1] / data.mem[0]) * 100).toFixed(2) + "%");

			$("#hdd").val(data.storage[1]).trigger("change");
			$("#hdd span").text( ((data.storage[1] / data.storage[0]) * 100).toFixed(2) + "%" );

			$("#cpu").val(data.cpu_load * 100.0).trigger("change");
			$("#cpu span").text($("#cpu").val() + "%");

			$("#content").empty();
			$("#content").html("<b>RAM usage:</b> "+data.mem[1]+" kb out of "+data.mem[0]+" kb used. Free: " + data.mem[2] + "<br />" +
				"<b>HDD usage:</b> "+data.storage[1]+" out of "+data.storage[0]+" kb used. Free: "+data.storage[2] + "<br />" + 
				"<b>Uptime:</b> "+ getTime(data.uptime) + "<br />" +
				"<b>Processes:</b> " + data.cpu_processes[0] + " running, " + data.cpu_processes[1] + " idle <br/>" +
				"<b>Network stats</b>: <ul><li>Recieved: " + data.network[0] + " bytes (" + data.network[1] + " packets)</li><li>Sent: " + data.network[2] + " bytes (" + data.network[3] + " packets)</li></ul><b>CPU info:</b><p>");

			for (var i = 0; i < data.cpu_info.length; i++) 
				$("#content").append(data.cpu_info[i] + "<br />");

			$("#content").append("</p>");
		}

		function getTime(seconds) 
		{
		    var leftover = seconds;

		    var days = Math.floor(leftover / 86400);
		    leftover = leftover - (days * 86400);

		    var hours = Math.floor(leftover / 3600);
		    leftover = leftover - (hours * 3600);

		    var minutes = Math.floor(leftover / 60);
		    leftover = leftover - (minutes * 60);

		    return days + " days, " + hours + " hours, " + minutes + " minutes, " + leftover + " seconds";
		}
		</script>
	</body>
</html>
