<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>VPS Usage</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.7/css/mdb.min.css" />
	</head>

	<body>
		<div class="container-fluid py-2">
			<div class="row">
				<div class="col">
					<div class="alert alert-warning" role="alert"><b>Note:</b> Content refreshes every <span id="refresh_time"></span> second(s).</div>
					<div class="alert alert-info" role="alert" id="general_info">-</div>
					<section id="mainchart"></section>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="card" id="ram">
						<div class="card-header">
							RAM usage
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item list-group-item-danger">Usage: <span class="usage">0</span></li>
							<li class="list-group-item list-group-item-info">Total: <span class="total">0</span> kb</li>
							<li class="list-group-item list-group-item-success">Free: <span class="free">0</span> kb</li>
						</ul>
						<div class="card-body">
							<canvas id="doughnut-ram"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="card" id="hdd">
						<div class="card-header">
							HDD usage
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item list-group-item-danger">Usage: <span class="usage">0</span> GB</li>
							<li class="list-group-item list-group-item-info">Total: <span class="total">0</span> GB</li>
							<li class="list-group-item list-group-item-success">Free: <span class="free">0</span> GB</li>
						</ul>
						<div class="card-body">
							<canvas id="doughnut-hdd"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="card" id="network">
						<div class="card-header">
							Network usage
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">Received: <span class="rec">0</span></li>
							<li class="list-group-item">Sent: <span class="sent">0</span></li>
						</ul>
						<div class="card-body">
							<h2 class="card-text">Cpu usage</h2>
							<canvas id="doughnut-cpu"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card" id="cpu">
						<div class="card-header">
							CPU's
						</div>
						<ul class="list-group list-group-flush"></ul>
					</div>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.7/js/mdb.min.js"></script>
		<script src="https://code.highcharts.com/stock/highstock.js"></script>
		<script type="text/javascript" src="script.js"></script>
	</body>
</html>