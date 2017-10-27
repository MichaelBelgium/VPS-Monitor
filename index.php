<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>VPS Usage</title>
		<style type="text/css">
		div.info_box 
		{ 
			font-family: 'Didact Gothic', sans-serif;
			color:#fff;
			background-color: #66CC66;
			border-radius:5px;
			padding: 10px;
			display: inline-block;
			width: auto;
			margin: 10px;
		}
		</style>
	</head>

	<body>
		<div class="main">
			<p><b>Note:</b> Content refreshes every <span id="refresh_time"></span> second(s).</p>

			<section id="mainchart"></section>
			<section id="content"></section>
		</div>

		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://code.highcharts.com/stock/highstock.js"></script>
		<script type="text/javascript" src="script.js"></script>
	</body>
</html>