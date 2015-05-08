# VPS Resource Viewer (PHP)

If you ever have a website on your VPS or dedicated server, you could use this repo to view useful information about your system.

<h1>What does it show ?</h1>
* HDD usage
* Uptime
* RAM usage
* CPU info
* Processes

<h1>Dependencies</h1>
Only one, you need the <a href="https://github.com/aterrien/jQuery-Knob">this knob library</a>.

<h1>Compatibility</h1>
This php page has been tested on:
* Centos 7

<h1>Config</h1>
```PHP
define("DISPLAY", "knob"); //knob or progress
```