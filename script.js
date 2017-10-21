var mainchart;

var refresh_time = 3;

$(document).ready(function() 
{
	$("#refresh_time").text(refresh_time);
	Highcharts.setOptions({ global: { useUTC: false } });
	mainchart = new Highcharts.stockChart({
		rangeSelector: {
			buttons: [{
			count: 30,
			type: 'second',
			text: '30S'
		},{
			count: 1,
			type: 'minute',
			text: '1M'
		},{
			count: 5,
			type: 'minute',
			text: '5M'
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
		xAxis: { title: "Time", type: "datetime", },

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


    setInterval(refresh, refresh_time * 1000); 

});

function getDummyData()
{
	var chartdata = new Array(), curtime = new Date().getTime();;

	for (i = -399; i <= 0; i++)	chartdata.push([curtime + i * 1000, 0]);
		chartdata.push([curtime, 0]);

		return chartdata;
}

function refresh()
{
	$.getJSON("getData.php", null, function(data, textStatus, jqXHR) {
		var time = (new Date()).getTime();

		var currentram = ((data.memory[1] / data.memory[0]) * 100).toFixed(2);
		var currenthdd = ((data.storage["used"] / data.storage["total"]) * 100).toFixed(2);
		var currentcpu = (data.CPUDetail[0] * 100).toFixed(2);

		mainchart.series[0].addPoint([time, parseFloat(currentram)], false, true);
		mainchart.series[1].addPoint([time, parseFloat(currenthdd)], false, true);
		mainchart.series[2].addPoint([time, parseFloat(currentcpu)], true, true);

		$("#content").empty();

		$("#content").html("<b>RAM usage:</b> "+formatNumber(data.memory[1])+" kb out of "+formatNumber(data.memory[0])+" kb used. Free: " + formatNumber(data.memory[2]) + " kb<br />" +
			"<b>HDD usage:</b> " + formatNumber(data.storage["used"]) + " bytes out of " + formatNumber(data.storage["total"]) + " bytes used. Free: " + formatNumber(data.storage["free"]) + " bytes <br />" + 
			"<b>Uptime:</b> "+ getTime(data.uptime) + "<br />" +
			"<b>Processes running/idle:</b> " + data.CPUDetail[1] + "<br/>" +
			"<b>Network stats</b>: <ul><li>Recieved: " + formatNumber(data.network[0]) + " bytes (" + formatNumber(data.network[1]) + " packets)</li><li>Sent: " + formatNumber(data.network[2]) + " bytes (" + formatNumber(data.network[3]) + " packets)</li></ul><b>CPU info:</b><br />");

		for (var i = 0; i < data.CPU.length; i++) 
		{
			if(i % 3 === 0)
			{
				var div = $("<div>", {"class": "info_box"});
				$("#content").append(div);
				div.append(data.CPU[i][1] + "<br />" + data.CPU[i + 1][0] + ": " + data.CPU[i + 1][1] + "<br />" + data.CPU[i + 2][0] + ": " + data.CPU[i + 2][1]);
			}
		}
	});
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