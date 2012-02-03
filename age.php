<?php 
	session_start();
	include 'connection/config.php';
	if(isset($_SESSION['session'])){
		$epiweek=$_SESSION['epiweek'] ;
		$facilitycode=$_SESSION['facilitycode'] ;
		if(isset($_GET['diseaseid'])){
		$_SESSION['disease'] = $_GET['diseaseid'];
		}
		$diseaseid = $_SESSION['disease'];
	}
	else{
		header('location:index.php');
	} 
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
  		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  		<title>IDSR</title>
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
    </head>
	<body>
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br><font color="#3B5998" >Intergratead Disease Surveillance and Response<br></font> 
			<?php
				$sql="SELECT name FROM facilitys WHERE facilitycode=$facilitycode";
				$rs=mysql_query($sql);
				$value=mysql_fetch_array($rs);
				$facilitycode=$value['name'];
				echo "Epiweek:<font color=\"#008000\" ><u>".$epiweek."</u></font>&nbsp;&nbsp;&nbsp; Facility:<font color=\"#008000\" ><u>".$facilitycode."</u></font>";
			?>
		</div>
		<div class="fb3"><a href="diseases.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
		<div class="fb3" ><a href="dataEntry.php?diseaseid=<?php echo $diseaseid?>&age=2"  accesskey="3">Below 5 years <img src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<div class="fb3"><a href="dataEntry.php?diseaseid=<?php echo $diseaseid?>&age=1"  accesskey="3">Above 5 years <img src="images/arrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<input type="hidden" name = "diseaseid" value = "<?php echo $_GET['diseaseid']?>"/>
	</body>
</html>