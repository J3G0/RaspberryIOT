<?php
   ob_start();
   session_start();
?>

<html>
<link rel="icon" href="http://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/extra/favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="http://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/extra/favicon.ico" type="image/x-icon"/>


   <head>
      <title>Login</title>
      <link rel="stylesheet" type="text/css" href="css/style.css">
   </head>

  <header>
		<div class = "headerwrapper">
		<h1>IOT - Pi Weather Station - Security</h1>
		</div>
  </header>
  
    <div class = "background">
	  
	<?php
        $msg = '';
            
        if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
				
            if ($_POST['username'] == 'PiStation' && 
                $_POST['password'] == '42') {
                $_SESSION['valid'] = true;
                $_SESSION['timeout'] = time();
                $_SESSION['username'] = IOT;
                  
                echo 'You have entered valid use name and password';
				header ("location: dataViewIndex.php");
               }
			else {
                  $msg = 'Wrong username or password';
               }
            }
    ?>  
	  
		<!--login fieldset-->
        <div class="formlogin">
			<fieldset>
				<h4>Log in here</h4>
				<form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
					<h4><?php echo $msg; ?></h4>
					<input type = "text" name = "username" placeholder = "Name" required autofocus></br>
					<input type = "password" name = "password" placeholder = "Pass" required> <br>
					<button type = "submit" name = "login">Login</button
				</form>
				<p><a style="color: #000000;" href = "logout.php" tite = "Logout">Click here to log out.</a></p>
			</fieldset>
	   </div>
	   
		<!--photo container-->
		<div class="polaroid">
			<img src="http://11603121.pxl-ea-ict.be/EA-ICT-BA2/project/extra/rpi.jpg" alt="Raspberry Pi with sensehat installed on it for use as weather station" style="width:100%">
			<div class="container">
			<p>Raspberry Pi Weatherstation Setup</p>
			</div>
		</div>
	   	   
    </div>
	
   	<!--footer container-->
	<footer>
	<p>Made by: Jeffrey Gorissen</p>
	<p>Student @ PXL University College</p>
	<p>Contact information: <a href="mailto:jeffrey.gorissen@student.pxl.be">
	jeffrey.gorissen@student.pxl.be</a></p>
	</footer>
   
</html>
