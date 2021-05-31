# VPS Resource Monitor

A very simple, single monitoring page that fetches real-time info about your system. Does not save history.

# What does it show ?
* RAM usage
    * Used
    * Cached
    * Total
    * Free
* HDD usage
    * Used
    * Total
    * Free
* CPU specs
    * usage
    * cores
    * speed
    * threads
* System uptime
* Network usage
    * amount and size of recieved packets
    * amount and size of sent packets 
* Operating system

# Dependencies
These dependencies are required but already included.

* jQuery
* Highcharts

# Compatibility
This php page has been tested on `CentOS 7` and `Debian 9`
There's a chance you might need to edit the `getData.php` for your system.

# Configuration
This is located in the `script.js`

```javascript
var refresh_time = 3; //every x seconds the page get refreshed
```

# Example

If you want you can have yours below too. Just do a pull request and i'll merge it with pleasure.

* [Click](https://mg-s.us/vps/)
* [Click](https://michaelbelgium.me/vps/)

![Image](https://i.imgur.com/xD4meAo.png)