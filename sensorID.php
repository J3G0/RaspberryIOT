	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){  
        $("#Updated2").fadeOut(3000);
	});
	</script>
	
	<div id="Updated2" style="color: red; margin-left: 40px;">Updated!!!</div>
	
	<table border="0" cellspacing="0" cellpadding="4">
    <tr>
    <td class="table_titles">ID</td>
    <td class="table_titles">Sensor</td>
    <td class="table_titles">IP adress from last update</td>
	<td class="table_titles">Last update from this sensor</td>
	<td class="table_titles">Country based on last IP</td>
    </tr>
	<?php
	include('connect.php');
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