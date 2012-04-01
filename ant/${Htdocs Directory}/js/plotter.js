function round(x, n) {
  var a = Math.pow(10, n);
  return (Math.round(x * a) / a);
}

function checkFileUpload() {
	
	var token = (document.form.file.value).split(".");
	
    switch(token[token.length - 1].toLowerCase()){
    	case "txt" : break;
    	case "zip" : break;
    	case ""	   : alert("Please choose a file."); break;
    	default    : alert("Extension not supported.");
    }
}

function json_decode(string) {

	var arr = new Array();
	var token = new Array();
	
	string = string.slice(1, token.length - 2);
	string = string.split(","); 

	for(var i=0; i<string.length; i++ )
	{
		token = string[i].split(":"); 
		arr[i] = new Array(token[0], parseFloat(token[1]));
	}
	
	return arr;	
}

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
		return;
	}
	else if (str=="1") 
	{	
		xmlhttp.onreadystatechange=function() {
	
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Done By Ability Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="2")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Taken By Source Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);	
	}
	else if(str=="3")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Healing Done By Ability Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="4")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Healing Received By Source Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="5")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Damage Types Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="6")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Attack Types Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="7")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotPieChart("Avoidance and Mitigation Breakdown", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	else if(str=="8")
	{
		xmlhttp.onreadystatechange=function() {
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				plotAreaChart("DPS", json_decode(xmlhttp.responseText));
	    	}
		};
		
		xmlhttp.open("GET","parser.php?file=" + document.form.uploadFile.value +  "&query=" + str, true);
	}
	
	xmlhttp.send();
}

function plotPieChart(title, data) {
    
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'highchartsContainer',
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
            name: 'Browser share',
            data: data
        }]
    });
}

function plotAreaChart(title, data) {
	
	chart = new Highcharts.Chart({
	    chart: {
	        renderTo: 'highchartsContainer2',
	        type: 'areaspline'
	    },
	    title: {
	        text: title
	    },
	    xAxis: {
	        labels: {
	            formatter: function() {
	                return this.value; // clean, unformatted number for year
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
	        data: [6 , 11, 32, 110, 235, 369, 640,
	            1005, 1436, 2063, 3057, 4618, 6444, 9822, 15468, 20434, 24126,
	            27387, 29459, 31056, 31982, 32040, 31233, 29224, 27342, 26662,
	            26956, 27912, 28999, 28965, 27826, 25579, 25722, 24826, 24605,
	            24304, 23464, 23708, null, 24357, null, 24401, 24344, 23586,
	            22380, 21004, 17287, 14747, 13076, 12555, 12144, 11009, 10950,
	            10871, 10824, 10577, 10527, 10475, 10421, 10358, 10295, 10104 ]
	    }]
	});
};
     
