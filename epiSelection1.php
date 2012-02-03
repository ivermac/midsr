<?php 
session_start();
	include 'connection/config.php';
	
	if ($_REQUEST['submit']) {
		$_SESSION['epiweek']=$_POST['epiweek'];
		$_SESSION['weekending'] = $_POST['weekending'];
		$_SESSION['submitted']=$_POST['submitted'];
		$_SESSION['expected'] = $_POST['expected'];
		
		header('location:home.php');
			
		$epiweek=$_POST['epiweek'];
		$weekending=$_POST['weekending'];
		$today = date("Y-n-j"); 
		$sql="INSERT INTO lab_weekly (epiweek,weekending,datecreated) VALUES ('$epiweek','$weekending','$today')";
		//$result=mysql_query($sql)or die(mysql_error());
	}
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>IDSR</title>
  	<script  type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
	<script>
		function validate(){
			var epiweek=document.getElementById("epiweek").value;
			var weekending=document.getElementById("weekending").value;
			if(epiweek==""||weekending==""){
				document.getElementById("input_error").innerHTML="please fill all text fields";
				return false;
			}
			else if(epiweek<1 || epiweek>53){
				document.getElementById("input_error").innerHTML="epiweek ranges between week 1 and 52/53.";
				return false;
			}
		}
	</script>
	<link href="css/idsr.css" type="text/css" rel="stylesheet" />
	<link rel="STYLESHEET" type="text/css" href="css/dhtmlxcalendar.css">
	<script>
		window.dhx_globalImgPath = "images/dhtmlx/imgs/";
	</script>
	<script  src="scripts/dhtmlxcommon.js"></script>
	<script  src="scripts/dhtmlxcalendar.js"></script>
	<script>
	var cal1;
		window.onload = function() {
    	cal1 = new dhtmlxCalendarObject('weekending');
	}
	</script>
	
  </head>
<body>
<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br>Intergratead Disease Surveillance and Response</div>
<form action="epiSelection.php" method="post">

<div class="fb3" align="center">
	<p><font color="#008000">
		<?php
			$districtID=$_SESSION['district_id']; 
			$sql="select name from districts where id='$districtID'";
			$results=mysql_query($sql);
			$value=mysql_fetch_array($results);	
			$value = $value['name'];
			echo "Welcome user from ".$value." district. Proceed to enter the data required.";
		?>
	</font></p>
	<font color="#ff0000"><p name="input_error" id="input_error" ></p></font>
</div>
<!-- <p name="input_error" id="input_error" ><font color="#ff0000"></p> -->
<div class="fb3" align="center">
	<table>
		<tr><td>Epiweek</td><td><input type="text" id="epiweek" name="epiweek" class="formbox" /></td></tr>
		<tr><td>Week Ending</td><td><input type="text" id="weekending" name="weekending" class="formbox" readonly="true"/></td></tr>
	</table>
	<input type="submit" name="submit" value="proceed"  class="formbutton" onclick="return validate()"/>
</div>	
</form>

</body>
<!-- InstanceEnd --></html>