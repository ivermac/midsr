<?php 
session_start();
include 'connection/config.php'; 
if(isset($_SESSION['session'])){
	$epiweek=$_SESSION['epiweek'] ;
	$facilitycode=$_SESSION['facilitycode'] ;?>
	
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
		 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		 <title>IDSR</title>
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
  	</head>
	<body>
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br><font color="#3B5998" >Integrated Disease Surveillance and Response<br></font> 
		<?php
			$sql="SELECT name FROM facilitys WHERE facilitycode=$facilitycode";
			$rs=mysql_query($sql);
			$value=mysql_fetch_array($rs);
			$facilitycode=$value['name'];
			echo "Epiweek:<font color=\"#008000\" ><u>".$epiweek."</u></font>&nbsp;&nbsp;&nbsp; Facility:<font color=\"#008000\" ><u>".$facilitycode."</u></font>";
		?>
		</div>
		<div class="fb3"><a href="diseases.php"  accesskey="1">Diseases Data Input <img name="accounts" src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<!--<div class="fb3"><a href="labWeekly.php"  accesskey="3">Lab Weekly Malaria Info <img src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>-->
		<div class="fb3"><a href="moreInfo.php"  accesskey="3">More Information <img src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<div class="fb3"><a href="summary.php"  accesskey="3">Summary<img src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<div class="fb3"><a href="epiSelection.php"  accesskey="3">Facility/Epiweek selection<img src="images/larrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<div class="fb3"><a href="index.php"  accesskey="3"><font color="#ff0000">Log Out</font><img src="images/larrow.png" width="8" height="13" alt="" align="right" /></a></div>
	</body>
</html>
<?php	
}
else{
	header('location:index.php');
} 
//echo "session:".$sess;
?>
