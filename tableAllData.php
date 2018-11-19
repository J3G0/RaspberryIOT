<center>
    <table border="0" cellspacing="0" cellpadding="4">
      <tr>
            <td class="table_titles">ID</td>
            <td class="table_titles">Date and Time</td>
            <td class="table_titles">Temperature</td>
			<td class="table_titles">CPU Temperature</td>
            <td class="table_titles">Humidity</td>
            <td class="table_titles">Pressure</td>
			<td class="table_titles">IP Address</td>
          </tr>
		  
<?php
//Tabel 
	include('connect.php'); 
    // Retrieve all records and display them
    $result = mysqli_query($conn,"SELECT * FROM pidata");

    // Used for row color toggle
    $oddrow = true;

    // process every record
    while( $row = mysqli_fetch_array($result) )
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
        echo '   <td'.$css_class.'>'.$row["id"].'</td>';
        echo '   <td'.$css_class.'>'.$row["date"].'</td>';
        echo '   <td'.$css_class.'>'.$row["temp"].'</td>';
		echo '   <td'.$css_class.'>'.$row["cpu_temp"].'</td>';
        echo '   <td'.$css_class.'>'.$row["humidity"].'</td>';
        echo '   <td'.$css_class.'>'.$row["pressure"].'</td>';
		echo '   <td'.$css_class.'>'.$row["ip_address"].'</td>';
        echo '</tr>';
    }
	
$result->close();
$conn->close();
?>
    </table>
	</center>