/**
 * Chart Plotting for Swtor Parser v2 using Highcharts
 * @author Frank Schiemenz
 */

////////////////////////////////////////// AJAX MAGIC HAPPENS HERE //////////////////////////////////////////

function showPlot(str, mod) {
	
	if (window.XMLHttpRequest)
	{	// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{	// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if (str=="")
	{
		plotPieChart("", false);
		plotAreaChart("", false);
		document.getElementById ('highcharts_container').style.visibility = 'hidden';
		document.getElementById ('another_highcharts_container').style.visibility = 'hidden';
	}
	else if (str=="1")
	{	
		xmlhttp.onreadystatechange=function() {
	
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Healing Done Ability Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="2")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Healing Done Target Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);	
	}
	else if(str=="3")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Healing Received Ability Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="4")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Healing Received Source Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="5")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Done Ability Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="6")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Done Target Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="7")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Done Type Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="8")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Attack Types", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="9")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotAreaChart("Damage Per Second", JSON.parse(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="10")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Avoidance Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="11")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Thread Done Ability Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="12")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Taken Source Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	
	xmlhttp.send();
}

////////////////////////////////////////// HIGHCHARTS SECTION //////////////////////////////////////////

function plotPieChart(title, data) {
	
	document.getElementById ('highcharts_container').style.visibility = 'visible';
    
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'highcharts_container',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: title
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.point.name + '</b>: ' + round(this.y, 2);
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ round(this.percentage, 2) +' %';
                    }
                }
            }
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        series: [{
            type: 'pie',
            name: '',
            data: data
        }]
    });
}

function plotAreaChart(title, data) {
	
	document.getElementById ('another_highcharts_container').style.visibility = 'visible';
	
	chart = new Highcharts.Chart({
	    chart: {
	        renderTo: 'another_highcharts_container',
	        type: 'areaspline',
	    },
	    title: {
	        text: title
	    },
	    legend: {
			enabled: false
		},
	    xAxis: {
	        labels: {
	            formatter: function() {
	                return this.value;
	            }
	        }
	    },
	    yAxis: {
	        title: {
	            text: ''
	        },
	        labels: {
	            formatter: function() {
	                return this.value;
	            }
	        }
	    },
        credits: {
            enabled: false
        },
	    tooltip: {
	        formatter: function() {
	            return this.y;
	        }
	    },
	    plotOptions: {
	        area: {
	            marker: {
	                enabled: false,
	                symbol: 'circle',
	                radius: 2,
	                states: {
	                    hover: {
	                        enabled: true
	                    }
	                }
	            }
	        }
	    },
	    series: [{
	       name: '',
	        data: data
	    }]
	});
}