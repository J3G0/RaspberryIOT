<?php
$servername = "localhost"; // this is usually "localhost" unless your database resides on a different server
$username = "No Hacks For You"; // enter your username for mysql
$password = "No Hacks For You"; // enter your password for mysql

$conn = new mysqli($servername, $username, $password, 'No Hacks For You'); // connection with my database
    if ($conn == TRUE) {
        if ($conn->connect_error) {
            exit("<span style='color: red'>Can't connect to the MySQL database. Please contact the webmaster.</body></html>");
		}
	} 
	else { //Can't connect to the MSQL Server.
        exit("<span style='color: red'>Can't connect to the MySQL server. Please contact the webmaster.</body></html>");
	}

$select = mysqli_select_db($conn,"pidata"); //which table; oh yes pidata
//echo "connected\n <br>"; used for debug in beginning
?>