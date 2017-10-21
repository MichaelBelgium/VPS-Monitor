<?php
	$tmp = null;

	$data = array(
		"memory" => array_map('intval',explode(" ", exec("free | grep 'Mem:' | awk {'print $2\" \"$3\" \"$4'}"))),
		"CPUDetail" => explode(" ", exec("cat /proc/loadavg | awk {'print $1\" \"$4'}")),
		"CPU" => array(),
		"storage" => array("total" => disk_total_space("/"), "free" => disk_free_space("/"), "used" => disk_total_space("/") - disk_free_space("/")),
		"network" => array_map('intval', explode(" ",exec("cat /proc/net/dev | grep 'eth0:' | awk {'print $2\" \"$3\" \"$10\" \"$11'}"))),
		"uptime" => (int)exec("cut -d. -f1 /proc/uptime")
	);

	exec("cat /proc/cpuinfo | grep -i 'model name\|cpu cores\|cpu mhz'", $tmp);

	foreach($tmp as $line)
	{
		list($key, $value) = explode(":", $line);
		$data["CPU"][] = array(trim($key), trim($value));
	}

	echo json_encode($data);
?>
