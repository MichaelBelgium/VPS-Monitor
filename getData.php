<?php
	require "viewer.class.php";

	$vps_viewer = new Viewer();

	$ar = array(
		"mem" => $vps_viewer->getMemory(),
		"cpu_load" => $vps_viewer->getCpuLoad(),
		"cpu_processes" => $vps_viewer->getProcesses(),
		"cpu_info" => $vps_viewer->getCpuInfo(),
		"storage" => $vps_viewer->getStorage(),
		"network" => $vps_viewer->getNetwork(),
		"uptime" => $vps_viewer->getUptime()
	);

	header('Content-Type: application/json');
	echo json_encode($ar);
?>
