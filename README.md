# VPS Resource Viewer (PHP)

If you ever have a website on your VPS or dedicated server, you could use this repo to view useful information about your system.

# What does it show ?
* Graph and sections with: HDD, CPU and RAM usage
* Uptime
* Network usage: recieved/sent data and amount of packages
* Operating system

# Dependencies
These dependencies are requireds but already included in the index file.

* jQuery
* Highcharts

# Compatibility
This php page has been tested on `CentOS Linux release 7.4.1708 (Core)` and `CentOS release 6.9 (Final)`
There's a chance you might need to edit the `getData.php` for your system.

# Config
This is located in the `script.js`

```javascript
var refresh_time = 3; //every x seconds the page get refreshed
```

# Example

If you want you can have yours below too. Just do a pull request and i'll merge it with pleasure.

* [Click](http://exp-gaming.net/vps/)
* [Click](http://michaelbelgium.me/vps/)

![Image](https://puu.sh/zvpAR.png)