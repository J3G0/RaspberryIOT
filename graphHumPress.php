<?php
	include('connect.php'); 
	$query= "SELECT id, date, humidity, pressure FROM pidata ORDER BY id";

$result = $conn->query($query);
//omzetting raw -> JSON voor grafiek
$table = array();
$table['cols'] = array(
  array('label' => 'date', 'type' => 'date'),
  array('label' => 'Humidity', 'type' => 'number'),
  array('label' => 'Pressure', 'type' => 'number'),
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
 
   $temp[] = array('v' => (float) $row['pressure']);
  $rows[] = array('c' => $temp);
 
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);
	
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
                                  min:0
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
                   1:{targetAxisIndex:1},
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
				title: 'Humidity (%) and Pressure (hPa)',
				hAxis: {
						title: 'Date',         
				},
				vAxes: {	0: {title: 'Humidity in %',
								viewWindowMode:'explicit',
								viewWindow:{
                                  max:100,
                                  min:0
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
                   1:{targetAxisIndex:1},
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
  
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){  
        $("#Updated").fadeOut(3000);
	});
	</script>
	
	<div id="Updated" style="color: red; margin-left: 40px;">Updated!!!</div>
	
	<div id="dashboard">
        <div id="chart_div"></div>
        <div id="range_filter_div"></div>
    </div>