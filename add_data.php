<?php
    // Connect to MySQL
    include("connect.php");
	
	// Function to get the client IP address
	function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

    // Prepare the SQL statement
	$ipaddress = get_client_ip();
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipaddress"));
	$country = $geo["geoplugin_countryName"];
	echo $ipaddress;
	echo "\n <br>";
    //date_default_timezone_set('Europe/Brussels');
    $dateS = date('Y/d/m H:i:s', time()); //Turning it into the right format, cuz 42. Jk Jk
    echo $dateS;
	echo "\n";
    //$SQL = "INSERT INTO pidata (date,temp,cpu_temp,humidity,pressure) VALUES ('$dateS','".$_GET["temp"]."','".$_GET["cputemp"]."','".$_GET["hum"]."','".$_GET["pr"]."')";     
	$SQL = "INSERT INTO pidata (date,temp,cpu_temp,humidity,pressure,ip_address) VALUES ('$dateS','".$_GET["temp"]."','".$_GET["cputemp"]."','".$_GET["hum"]."','".$_GET["pr"]."','$ipaddress')";     
	
	//Ik wil temp en cpu temp altijd samen doorsturen. Ik wil humidity en pressure los van de rest appart doorsturen
	//enkel pressure doorgestuurd -> pressure updaten
	if($_GET["temp"] == 0 && $_GET["cputemp"] == 0 && $_GET["hum"] == 0 && $_GET["pr"] !== 0){
	$SQL2 = "UPDATE sensorID SET LastKnownIP='$ipaddress', LastChanged='$dateS', country='$country' WHERE sensorID=4";
	mysqli_query($conn,$SQL2);	
	}
	
	//enkel humidity doorgestuurd -> humidity updaten
	if($_GET["temp"] == 0 && $_GET["cputemp"] == 0 && $_GET["hum"] !== 0 && $_GET["pr"] == 0){
	$SQL2 = "UPDATE sensorID SET LastKnownIP='$ipaddress', LastChanged='$dateS', country='$country' WHERE sensorID=3";
	mysqli_query($conn,$SQL2);	
	}
	
	//temp en cpu temp doorgestuurd -> temp en cpu temp updaten
	if($_GET["temp"] !== 0 && $_GET["cputemp"] !== 0 && $_GET["hum"] == 0 && $_GET["pr"] == 0){
	$SQL2 = "UPDATE sensorID SET LastKnownIP='$ipaddress', LastChanged='$dateS', country='$country' WHERE sensorID=1";
	mysqli_query($conn,$SQL2);
	$SQL3 = "UPDATE sensorID SET LastKnownIP='$ipaddress', LastChanged='$dateS', country='$country' WHERE sensorID=2";
	mysqli_query($conn,$SQL3);
	}
	
    // Execute SQL statement
    mysqli_query($conn,$SQL);
	

    // Go to the review_data.php (optional)
    //header("Location: indexofzo.php");
?>