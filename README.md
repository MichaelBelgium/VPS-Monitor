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
It uses <a href="https://github.com/aterrien/jQuery-Knob">this knob library</a> which is linked towards it already in the file(s).

<h1>Compatibility</h1>
This php page has been tested on `CentOS Linux release 7.4.1708 (Core)`
There's a chance you might need to edit the `getData.php` for your system.


<h1>Config</h1>
```PHP
define("REFRESH_TIME", 3); //every x seconds the page get refreshed
```

<h1>Example</h1>
<a href="http://exp-gaming.net/vps/">Click</a>
