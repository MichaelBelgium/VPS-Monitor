var mainchart;

var refresh_time = 3;

var prev_total = prev_idle = 0;
var chart_ram, chart_hdd, chart_cpu;

$(document).ready(function() 
{
	$("#refresh_time").text(refresh_time);
	
	highChartsInit();
	
	chart_ram = createChart("doughnut-ram", ["Free RAM", "Used ram"]);
	chart_hdd = createChart("doughnut-hdd", ["Free space", "Used space"]);
	chart_cpu = createChart("doughnut-cpu", ["Unused load", "Cpu load"]);

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
			count: 25,
			type: 'minute',
			text: '25M'
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
}

function createChart(forid, labelarray) {
	return new Chart(document.getElementById(forid).getContext('2d'), {
		type: 'doughnut',
		data: {
			labels: labelarray,
			datasets: [{
				data: [0, 0],
				backgroundColor: ["#c3e6cb", "#f5c6cb" ],
				hoverBackgroundColor: ["#155724", "#721c24"]
			}]
		},
		options: {
			responsive: true,
			legend: {
				display: false
			}
		}
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
	$.getJSON("getData.php", null, function(data, textStatus, jqXHR) {
		var time = (new Date()).getTime();

		var cpuload = getCpuLoad(data.CPUDetail);
		var currentram = ((data.memory[1] / data.memory[0]) * 100).toFixed(2);
		var currenthdd = ((data.storage["used"] / data.storage["total"]) * 100).toFixed(2);
		var currentcpu = cpuload > 100 ? 100 : cpuload;

		mainchart.series[0].addPoint([time, parseFloat(currentram)], false, true);
		mainchart.series[1].addPoint([time, parseFloat(currenthdd)], false, true);
		mainchart.series[2].addPoint([time, parseFloat(currentcpu)], true, true);

		$("#ram .usage").html(formatNumber(data.memory[1]) + " kb<br/>Cache: " + formatNumber(data.memory[3]) + " kb");
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

		chart_ram.data.datasets[0].data = [data.memory[1], data.memory[2]];
		chart_hdd.data.datasets[0].data = [data.storage["free"], data.storage["used"]];
		chart_cpu.data.datasets[0].data = [100.0 - currentcpu, currentcpu];
		chart_ram.update();
		chart_hdd.update();
		chart_cpu.update();

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