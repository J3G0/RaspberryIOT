<?php 
	ob_start();
	session_start();
	if(!isset($_SESSION['username']))
{
    // not logged in
    header('Location: index.php');
}



    // Start MySQL Connection
    include('connect.php'); 

	$user_ip = getenv('REMOTE_ADDR');
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
	$country = $geo["geoplugin_countryName"];
	$city = $geo["geoplugin_city"];
?>

<html>
<link rel="icon" href="http://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/extra/favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="http://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/extra/favicon.ico" type="image/x-icon"/>
<wallpaper>
	<head>
    <title>Raspberry Pi Data Logger</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<header>
		<div class = "headerwrapper">
		<h1 >IOT - Pi Weather Station - Data view</h1>
		</div>
	</header>
	
	<br>
	
	<center>
	<h3>
	<?php
	echo "Hello visitor from: " .$country ." (city if known: ".$city .")<br>";
	echo "Your IP Address seems to be: " .$user_ip;
	?>
	</h3>
	</center>
	<br>
	
    <body>
	<center>
	<fieldset>
        <legend>Raspberry Pi Data Logger</legend>
		
    <!--Div that will hold the dashboard-->
    <h2 style="margin-left: 40px">Graph</h2>
	<div id="google">
	<?php
	$query= "SELECT id, temp, date, humidity, pressure, cpu_temp FROM pidata ORDER BY id";

$result = $conn->query($query);
//omzetting raw -> JSON voor grafiek
$table = array();
$table['cols'] = array(
  array('label' => 'date', 'type' => 'date'),
  array('label' => 'Temperature', 'type' => 'number'),
  array('label' => 'Humidity', 'type' => 'number'),
  array('label' => 'Pressure', 'type' => 'number'),
  array('label' => 'CPU Temperature', 'type' => 'number'),
);
while($row = mysqli_fetch_array($result))
{
  $temp = array();
  if($row['date'])
  {
      //zet "date":"2017-11-03 03:14:34" om naar "v":"Date(2017 10, 03, 03, 14, 34)"
      $date1 = new DateTime($row['date']);
      $date2 = "Date(".date_format($date1, 'Y').",".((int)date_format($date1, 'd')-1).", ".((int) date_format($date1, 'm')).",  ".date_format($date1, 'H').", ".date_format($date1, 'i').", ".date_format($date1, 's').")";
      $temp[] = array('v' => (string) $date2);
  }
  
  if($row['temp'] == 0)
  {
    $temp[] = array('v' => null);
  }
  else
  {
    $temp[] = array('v' => (float) $row['temp']);
  }
  
  if($row['humidity'] == 0)
  {
    $temp[] = array('v' => null);
  }
  else
  {
    $temp[] = array('v' => (float) $row['humidity']);
  }
  
  if($row['pressure'] == 0)
  {
    $temp[] = array('v' => null);
  }
  else
  {
    $temp[] = array('v' => (float) $row['pressure']);
  }
  
  if($row['cpu_temp'] == 0)
  {
    $temp[] = array('v' => null);
  }
  else
  {
    $temp[] = array('v' => (float) $row['cpu_temp']);
  }
  
  $temp[] = array('v' => (float) $row['cpu_temp']);
  $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

$file = 'data.txt';
$currentData = "$jsonTable\n";
// Write the contents to the file, 
// using the LOCK_EX flag to prevent anyone else writing to the file at the same time
file_put_contents($file, $currentData, LOCK_EX);
//echo $jsonTable; //for debug purpose
	
	?>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="//www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load('visualization', '1', {packages: ['controls']});
    google.setOnLoadCallback(drawChart); //

    function drawChart () {
		
        var data = new google.visualization.DataTable( <?php echo $jsonTable; ?> );
		
        var rangeFilter = new google.visualization.ControlWrapper({
            controlType: 'ChartRangeFilter',
            containerId: 'range_filter_div',
            interpolateNulls: true,
            options: {
                filterColumnIndex: 0,
                ui: {
                    chartOptions: {
						backgroundColor: { fill:'transparent' },
                        interpolateNulls: true,
                        height: 70,
						width: window.innerWidth*0.90,
						vAxes: {	0: {
								viewWindowMode:'explicit',
								viewWindow:{
                                  max:100,
                                  min:-20
                                },
								gridlines: {color: 'transparent'},
								},
							1: {
								viewWindowMode:'explicit',
								viewWindow:{
                                  max:1100,
                                  min:900
                                },
								gridlines: {color: 'transparent'},
								},
                },
          series: {0: {targetAxisIndex:0},
                   1:{targetAxisIndex:0},
				   2:{targetAxisIndex:1},
                   3:{targetAxisIndex:0},
                  },
                        chartArea: {
                            width: '75%'
                        }
                    },
                }
            },
        });

        var chart = new google.visualization.ChartWrapper({
            chartType: 'LineChart',
            containerId: 'chart_div',
            options: {
                // width and chartArea.width should be the same for the filter and chart
				height: 400,
				width: window.innerWidth*0.90,
				title: '(CPU)Temperature (°C), humidity (%) and pressure (hPa) over time',
				hAxis: {
						title: 'Date',         
				},
				vAxes: {	0: {title: 'Temperature in °C and Humidity in %',
								viewWindowMode:'explicit',
								viewWindow:{
                                  max:100,
                                  min:-20
                                },
								gridlines: {color: 'transparent'},
								},
							1: {title: 'Pressure in hPa',
								viewWindowMode:'explicit',
								viewWindow:{
                                  max:1100,
                                  min:900
                                },
								gridlines: {color: 'transparent'},
								},
                },
          series: {0: {targetAxisIndex:0},
                   1:{targetAxisIndex:0},
				   2:{targetAxisIndex:1},
                   3:{targetAxisIndex:0},
                  },
                backgroundColor: { fill:'transparent' },
				interpolateNulls: true,
                chartArea: {
                    width: '75%'
                }
            }
        });
 

        // Create the dashboard
        var dash = new google.visualization.Dashboard(document.getElementById('dashboard'));
        // bind the chart to the filter
        dash.bind([rangeFilter], [chart]);
        // draw the dashboard
        dash.draw(data);
		
		

    }
    </script>
  
	<div id="dashboard">
        <div id="chart_div"></div>
        <div id="range_filter_div"></div>
    </div>
	
	</div>
	
	<br>
	<center>
	<button id="graphUpdate" style="margin-left: 40px">Update graph (all data)</button>
	<span></span>
	<button id="temp" style="margin-left: 40px">Only Temperature Data</button>
	<span></span>
	<button id="HumPress" style="margin-left: 40px">Only Humidity and Pressure data</button>
	</center>
	<br>
	<br>
	
	<h2 style="margin-left: 40px">Sensors and info</h2>
	<button id="reloadDensorID" style="margin-left: 40px" type="button">Update table</button>
	<br>
	<center>
	<div id=sensorID>
	<table border="0" cellspacing="0" cellpadding="4">
    <tr>
    <td class="table_titles">ID</td>
    <td class="table_titles">Sensor</td>
    <td class="table_titles">IP adress from last update</td>
	<td class="table_titles">Last update from this sensor</td>
	<td class="table_titles">Country based on last IP</td>
    </tr>
	<?php
	// Retrieve all records and display them
    $result2 = mysqli_query($conn,"SELECT * FROM sensorID");

    // Used for row color toggle
    $oddrow = true;

    // process every record
    while( $row = mysqli_fetch_array($result2) )
    {
        if ($oddrow) 
        { 
            $css_class=' class="table_cells_odd"'; 
        }
        else
        { 
            $css_class=' class="table_cells_even"'; 
        }

        $oddrow = !$oddrow;

        echo '<tr>';
        echo '   <td'.$css_class.'>'.$row["sensorID"].'</td>';
        echo '   <td'.$css_class.'>'.$row["name"].'</td>';
        echo '   <td'.$css_class.'>'.$row["LastKnownIP"].'</td>';
		echo '   <td'.$css_class.'>'.$row["lastChanged"].'</td>';
		echo '   <td'.$css_class.'>'.$row["country"].'</td>';
        echo '</tr>';
    }
	
$result2->close();

	?>	
	</table>
	</div>
	</center>
	
	
	<h2 style="margin-left: 40px">All data in table</h2>
	<button style="margin-left: 40px" onclick="hide()">Show/Hide Table</button><br><br>
	<div id="tableAllData" style="display: none;">
	<button id="loadTabel" style="margin-left: 40px" type="button">Load table with all the data</button>
	</div>
	
	<h2 style="margin-left: 40px">Load Raw Data from data.txt</h2>
	
	<button style="margin-left: 40px" onclick="show()">Show/Hide RAW data</button><br><br>
	<div id="ajaxData" style="margin-left: 40px; display: none;">
	<button id="loadData" type="button">Load Raw (JSON) Data</button>
	</div>
	
	
	</fieldset><br>
	
	
	<p>Click on the icon to download the RAW data in txt format:<p>
	
	<a href="https://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/data.txt" download="data.txt">
		<img border="0" src="http://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/extra/txt.png" alt="Txt file icon" width="100" height="100">
	</a>
	
	<br><br>
	<button type="submit" onclick="window.open('data.txt')">Open RAW data on new tab</button>

	<p><a style="color: #000000;" href = "logout.php" tite = "Logout">Click here to log out.</a></p>

	
	</center>
    </body>
	
	<footer>
	<p>Made by: Jeffrey Gorissen</p>
	<p>Student @ PXL University College</p>
	<p>Contact information: <a href="mailto:jeffrey.gorissen@student.pxl.be">
	jeffrey.gorissen@student.pxl.be</a></p>
	</footer>

<script>
	<!--javascript normaal gezien in aparte file steken -->
		
		function hide(){
			var menu = document.getElementById("tableAllData");
			if(menu.style.display === 'none') 
			{
				menu.style.display = 'inline';
			}
			else
			{
				menu.style.display = 'none';
			}
		}
	</script>
	

	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
		$("#loadData").click(function(){
			$.ajax({url: "data.txt", success: function(result){
				$("#ajaxData").html(result);
				}});
		});
	});
	</script>
	
	<script>
	$(document).ready(function(){
		$("#reloadDensorID").click(function(){
			$.ajax({url: "sensorID.php", success: function(result){
				$("#sensorID").html(result);
				}});
		});
	});
	</script>
	
	<script>
	$(document).ready(function(){
		$("#graphUpdate").click(function(){
			$.ajax({url: "graph.php", success: function(result){
				$("#google").html(result);
				drawChart();
				}});
		});
	});
	</script>
	
	<script>
	$(document).ready(function(){
		$("#temp").click(function(){
			$.ajax({url: "graphTemp.php", success: function(result){
				$("#google").html(result);
				drawChart();
				}});
		});
	});
	</script>
	
	<script>
	$(document).ready(function(){
		$("#HumPress").click(function(){
			$.ajax({url: "graphHumPress.php", success: function(result){
				$("#google").html(result);
				drawChart();
				}});
		});
	});
	</script>
	
		<script>
	$(document).ready(function(){
		$("#loadTabel").click(function(){
			$.ajax({url: "tableAllData.php", success: function(result){
				$("#tableAllData").html(result);
				drawChart();
				}});
		});
	});
	</script>
	
	<script>
	<!--javascript normaal gezien in aparte file steken -->
		
		function show(){
			var menu = document.getElementById("ajaxData");
			if(menu.style.display === 'none') 
			{
				menu.style.display = 'inline';
			}
			else
			{
				menu.style.display = 'none';
			}
		}
	</script>
	
</wallpaper>	
</html>