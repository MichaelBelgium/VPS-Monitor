<!DOCTYPE html>
<html>
	<head>
		<title>VPS Usage</title>
		<style type="text/css">
			article { display: inline-block; }
			article h2 	{ font-size: 30px;	margin: 0;	}
			article span { display: inline-block;  margin-top: 75px; font-size: 20px;}
			#content { margin-top: 40px; }
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

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="https://code.highcharts.com/stock/highstock.js"></script>
		<script type="text/javascript" src="script.js"></script>
	</body>
</html>
