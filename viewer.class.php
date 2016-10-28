<?php
class Viewer
{
	private $memory = null, $cpudetail = null, $cpu = null, $storage = null, $network = null, $uptime = null;
	private $exclude_cached_mem = false;

	function __construct($exclude_cached_mem = true)
	{
		$this->exclude_cached_mem = $exclude_cached_mem;
		$this->refreshData();
	}

	public function getMemory()
	{
		return $this->memory;
	}

	public function getCpuLoad()
	{
		return (float)$this->cpudetail[0];
	}

	public function getCpuInfo()
	{
		return $this->cpu;
	}

	public function getProcesses()
	{
		return array_map("intval",explode("/", $this->cpudetail[1]));
	}

	public function getStorage()
	{
		return $this->storage;
	}

	public function getNetwork()
	{
		return $this->network;
	}

	public function getUptime()
	{
		return $this->uptime;
	}

	public function refreshData()
	{
		$this->memory = explode(" ", exec("free | grep ".Viewer_Settings::_MEM." | awk {'print $2\" \"$3".($this->exclude_cached_mem ? "-$7" : "")."\" \"$4'}"));
		$this->memory = array_map("intval",$this->memory);

		$this->cpudetail = explode(" ", exec("cat /proc/loadavg | awk {'print $1\" \"$4'}"));
		
		exec("cat /proc/cpuinfo | grep -i 'model name\|cpu cores\|cpu mhz'",$this->cpu);

		$this->storage = explode(" ",exec("df | grep ".Viewer_Settings::_FS." | awk {'print $2\" \"$3\" \"$4'}"));
		$this->storage = array_map("intval", $this->storage);

		$this->network = explode(" ",exec("cat /proc/net/dev | grep ".Viewer_Settings::_NET." | awk {'print $2\" \"$3\" \"$10\" \"$11'}"));
		$this->network = array_map("intval", $this->network);

		$this->uptime = (int)exec("cut -d. -f1 /proc/uptime");
	}
}

abstract class Viewer_Settings
{
	const _MEM = "Mem:";
	const _FS = "/dev/vda1";
	const _NET = "eth0";
}

?>
