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
This php page has been tested on:
* Google Chrome
* Centos 6.8 and 7.2 

There are settings which u can change if you are using anything else than Centos 6.8, for other dists you might wanna dig in even further.


<h1>Config</h1>
```PHP
define("REFRESH_TIME", 3); //every x seconds the page get refreshed
```

<h1>Example</h1>
<a href="http://exp-gaming.net/vps/">Click</a>
