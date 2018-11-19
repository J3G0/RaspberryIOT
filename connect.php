<?php
$servername = "localhost"; // this is usually "localhost" unless your database resides on a different server
$username = "pxleai1q_1603121"; // enter your username for mysql
$password = "Qf48tHJGSKmk"; // enter your password for mysql

$conn = new mysqli($servername, $username, $password, 'pxleai1q_1603121'); // connection with my database
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