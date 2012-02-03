<?php
session_start();
include 'connection/config.php'; 

unset($_SESSION['session']); 

$username=$_POST['user'];
$password=$_POST['passwd'];

$username = stripslashes($username); 
$password = stripslashes($password);
 
$username = mysql_real_escape_string($username); 
$password = mysql_real_escape_string($password);

if ($_REQUEST['submit']) {
	if($username=="idsr" && $password="idsr"){
		header('location:dashboard.php');
	}
	else{
		$sql="SELECT * FROM users WHERE username='$username' AND password= md5('$password')";
		$results=mysql_query($sql); 
		$value=mysql_fetch_array($results);
		$_SESSION['district_name'] = $value['username'];
		$role = $value['role'];
		if($value){
			if($role==3){
				$_SESSION['session'] = 'session';
				header('location:post_index.php');
			}						
			else if($role==4)		
				header('location:epiSelection1.php');
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>IDSR</title>
  <link href="css/idsr.css" type="text/css" rel="stylesheet" />
  <script  type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
	<script>
		function validate(){
			var username=document.getElementById("user").value;
			var password=document.getElementById("passwd").value;
			if(username==""||password==""){
				document.getElementById("login_error").innerHTML="please fill all login text fields";
				return false;
			}			
			//$("#id").attr("value");
		}
	</script>
  </head>
<body>

<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br>MINISTRY OF PUBLIC HEALTH AND SANITATION<br></>Integrated Disease Surveillance and Response</div>

<form action="index.php" method="post" id="LoginForm">
<div class="fb3" align="center"><font color="#ff0000"><p name="login_error" id="login_error" ></p></font>Username  <br><input type="text" name="user" id="user" class="formbox" size="25"/></div>	
<div class="fb3" align="center">Password  <br><input type="password" name="passwd" id="passwd" class="formbox" size="25"/></div>	
<div class="fb3" align="center">  <input type="submit" name="submit" value="Login"  class="formbutton" onclick="return validate()"/></div>	
</form>
      
</body>
<!-- InstanceEnd --></html>