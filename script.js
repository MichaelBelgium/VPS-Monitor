var mainchart, gauge;

var refresh_time = 3;

var prev_total = prev_idle = 0;

$(document).ready(function() 
{
    $("#refresh_time").text(refresh_time);
    
    highChartsInit();

    setInterval(refresh, refresh_time * 1000); 
});

function highChartsInit() 
{
    Highcharts.setOptions({ global: { useUTC: false } });
    mainchart = new Highcharts.stockChart({
        rangeSelector: {
            buttons: [{
            count: 1,
            type: 'minute',
            text: '1M'
        },{
            count: 5,
            type: 'minute',
            text: '5M'
        },{
            count: 10,
            type: 'minute',
            text: '10M'
        },{
            count: 15,
            type: 'minute',
            text: '15M'
        },{
            type: 'all',
            text: 'All'
        }],
            inputEnabled: false,
            selected: 0
        },

        tooltip: { pointFormat: '{series.name}: <b>{point.y}%</b>' },
        chart: { renderTo: 'mainchart' },
        title: { text: 'Hardware usage' },
        xAxis: { title: "Time", type: "datetime" },

        yAxis: {
            title: 'Percentage',
            max: 100,
            min: 0,
            tickInterval:10
        },

        series: [
            { name: 'RAM', data: getDummyData() },
            { name: 'HDD', data: getDummyData() },
            { name: 'CPU', data: getDummyData() }
        ]
    });

    gauge = Highcharts.chart('gaugeUsage', {
    
        chart: {
            type: 'solidgauge'
        },
    
        title: {
            text: 'Usage',
            style: {
                fontSize: '24px'
            }
        },
    
        tooltip: {
            borderWidth: 0,
            backgroundColor: 'none',
            shadow: false,
            style: {
                fontSize: '16px'
            },
            valueSuffix: '%',
            pointFormat: '{series.name}<br><span style="font-size:1.5em; color: {point.color}; font-weight: bold">{point.y}</span>',
            positioner: function (labelWidth) {
                return {
                    x: (this.chart.chartWidth - labelWidth) / 2,
                    y: (this.chart.plotHeight / 2) + 15
                };
            }
        },
    
        pane: {
            startAngle: 0,
            endAngle: 360,
            background: [{ //CPU
                outerRadius: '112%',
                innerRadius: '88%',
                backgroundColor: Highcharts.color(Highcharts.getOptions().colors[0])
                    .setOpacity(0.3)
                    .get(),
                borderWidth: 0
            }, { //RAM
                outerRadius: '87%',
                innerRadius: '63%',
                backgroundColor: Highcharts.color(Highcharts.getOptions().colors[1])
                    .setOpacity(0.3)
                    .get(),
                borderWidth: 0
            }, { //HDD
                outerRadius: '62%',
                innerRadius: '38%',
                backgroundColor: Highcharts.color(Highcharts.getOptions().colors[2])
                    .setOpacity(0.3)
                    .get(),
                borderWidth: 0
            }]
        },
    
        yAxis: {
            min: 0,
            max: 100,
            lineWidth: 0,
            tickPositions: []
        },
    
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    enabled: false
                },
                rounded: true
            }
        },
    
        series: [{
            name: 'CPU',
            data: [{
                color: Highcharts.getOptions().colors[0],
                radius: '112%',
                innerRadius: '88%',
                y: 0
            }]
        }, {
            name: 'RAM',
            data: [{
                color: Highcharts.getOptions().colors[1],
                radius: '87%',
                innerRadius: '63%',
                y: 0
            }]
        }, {
            name: 'HDD',
            data: [{
                color: Highcharts.getOptions().colors[2],
                radius: '62%',
                innerRadius: '38%',
                y: 0
            }]
        }]
    });
}

function getDummyData()
{
    var chartdata = new Array(), curtime = new Date().getTime();

    for (i = -399; i <= 0; i++)	chartdata.push([curtime + i * 1000, 0]);
    
    chartdata.push([curtime, 0]);

    return chartdata;
}

function refresh()
{
    $.getJSON("getData.php", null, function(data) {
        var time = (new Date()).getTime();

        var cpuload = getCpuLoad(data.CPUDetail);
        var currentram = ((data.memory[1] / data.memory[0]) * 100).toFixed(2);
        var currenthdd = ((data.storage["used"] / data.storage["total"]) * 100).toFixed(2);
        var currentcpu = cpuload > 100 ? 100 : cpuload;

        mainchart.series[0].addPoint([time, parseFloat(currentram)], false, true);
        mainchart.series[1].addPoint([time, parseFloat(currenthdd)], false, true);
        mainchart.series[2].addPoint([time, parseFloat(currentcpu)], true, true);

        $("#ram .usage").html(formatNumber(data.memory[1]) + " GB<br/>Cache: " + formatNumber(data.memory[3]) + " GB");
        $("#ram .total").text(formatNumber(data.memory[0]));
        $("#ram .free").text(formatNumber(data.memory[2]));

        $("#hdd .usage").text(formatNumber(data.storage["used"]));
        $("#hdd .total").text(formatNumber(data.storage["total"]));
        $("#hdd .free").text(formatNumber(data.storage["free"]));

        $("#network .rec").html(formatNumber(data.network[0]) + " bytes <br/>Packets: " + formatNumber(data.network[1]));
        $("#network .sent").html(formatNumber(data.network[2]) + " bytes <br/>Packets: " + formatNumber(data.network[3]));

        var info = "Uptime: " + getTime(data.uptime) + "<br />Operating System: " + data.OS;
        $("#general_info").html(info);

        $("#cpu .list-group").empty();

        gauge.series[0].points[0].update(parseFloat(currentcpu));
        gauge.series[1].points[0].update(parseFloat(currentram));
        gauge.series[2].points[0].update(parseFloat(currenthdd));

        for (var i = 0; i < data.CPU.length; i++) 
        {
            if(i % 3 === 0)
            {
                var listitem = $("<li>", {"class": "list-group-item"});
                listitem.html(data.CPU[i][1] + "<br />" + data.CPU[i + 1][0] + ": " + data.CPU[i + 1][1] + "<br />" + data.CPU[i + 2][0] + ": " + data.CPU[i + 2][1]);
                $("#cpu .list-group").append(listitem);
            }
        }
    });
}

//Calculation by https://github.com/Leo-G/DevopsWiki/wiki/How-Linux-CPU-Usage-Time-and-Percentage-is-calculated
function getCpuLoad(input)
{
    var cpuload = input.split(' ');
    var sum = 0;

    for (var i = 0; i < cpuload.length; i++) {
        sum += parseInt(cpuload[i]);	
    }

    var idlecpuload = cpuload[3];
    var diff_idle = idlecpuload - prev_idle;
    var diff_total = sum - prev_total;
    var diff_usage = (1000 * (diff_total - diff_idle) / diff_total + 5) / 10;

    prev_total = sum;
    prev_idle = idlecpuload;
    
    return diff_usage.toFixed(2);
}

function formatNumber(number)
{
    return number.toLocaleString("nl").replace(/\./g, " ");
}

function getTime(seconds) 
{
    var leftover = seconds;

    var days = Math.floor(leftover / 86400);
    leftover = leftover - (days * 86400);

    var hours = Math.floor(leftover / 3600);
    leftover = leftover - (hours * 3600);

    var minutes = Math.floor(leftover / 60);
    leftover = leftover - (minutes * 60);

    return days + " days, " + hours + " hours, " + minutes + " minutes, " + leftover + " seconds";
}