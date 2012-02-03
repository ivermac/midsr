<?php 
session_start(); 
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
  		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  		<title>IDSR</title>
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
    </head>
	<body>
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br><font color="#3B5998" >Integrated Disease Surveillance and Response<br></font></div>
		<div class="fb3" align="center">
			<font color="#008000"> 
			<?php
				$district_name = $_SESSION['district_name'];
				echo "Welcome user from " . $district_name . " district.";
			?>
			</font>
		</div>
		<div class="fb3" >
			<form action="district_summary.php" method="post">
			<table>
					<tr>
						<td>Epiweek</td>
						<td><input type="text" id="epiweek" name="epiweek"  /></td>
					</tr>
					<tr>
						<td>Year</td>
						<td><input type="text" id="year" name="year"  /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" id="submit" name="submit" value="District Summary" class="formbutton" onclick="show();"/></td>
					</tr>
				</table>
			</form>
			</div>
		<div class="fb3"><a href="epiSelection.php"  accesskey="3">Episelection <img src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<div class="fb3"><a href="index.php"  accesskey="3"><font color="#ff0000">Log Out</font><img src="images/larrow.png" width="8" height="13" alt="" align="right" /></a></div>
	</body>
</html>