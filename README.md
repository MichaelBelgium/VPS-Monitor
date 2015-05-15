# VPS Resource Viewer (PHP)

If you ever have a website on your VPS or dedicated server, you could use this repo to view useful information about your system.

<h1>What does it show ?</h1>
* HDD usage
* Uptime
* RAM usage
* CPU info
* Processes
* Network info

<h1>Dependencies</h1>
Only one, you need the <a href="https://github.com/aterrien/jQuery-Knob">this knob library</a>.

<h1>Compatibility</h1>
This php page has been tested on:
* Centos (5 and 7)
* Debian

For debian change the "DEBIAN" define to true:
```PHP
define("DEBIAN", true); 
```

<h1>Config</h1>
```PHP
define("DISPLAY", "knob"); //knob or progress
define("REFRESH_TIME", 3); //every x seconds the page get refreshed
define("DEBIAN", false); //is this file located on debian or not ?
```

<h1>Example</h1>
<img src="http://puu.sh/hObiN.png"></img>