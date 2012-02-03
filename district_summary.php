<?php 
session_start();
include 'connection/config.php'; 

$year=$_POST['year'];
$epiweek=$_POST['epiweek'];
$district=$_SESSION['district_name'];
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
  		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  		<title>IDSR</title>
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
		<script  type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
    </head>
	<body>
		
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br><font color="#3B5998" >Integrated Disease Surveillance and Response<br></font>
			<?php
				echo "Epiweek:<font color=\"#008000\" ><u>".$epiweek."</u></font>&nbsp;&nbsp;&nbsp; District:<font color=\"#008000\" ><u>".$district."</u></font>";
			?>
		</div>
		<div class="fb3"><a href="post_index.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
		<div class="fb3" align="center">
		<?php 
		$sql="SELECT id FROM districts WHERE name = '$district'";
		$rs=mysql_query($sql);
		if($row=mysql_fetch_array($rs))
			$district=$row['id'];
		
		$sql="SELECT DISTINCT (facility) as facility FROM surveillance WHERE epiweek =$epiweek AND DATE =$year AND district =$district";
		$rst=mysql_query($sql);
		echo "Facilities with data:";
		while($value=mysql_fetch_array($rst)){
			$id=$value['facility'];
			$sql="SELECT name FROM facilitys WHERE facilitycode = $id";
			$res=mysql_query($sql);
			$facility=mysql_fetch_array($res);
			echo "<font color=\"green\">".$facility['name']."</font>";
			echo "<br>";
		}
		echo "<br><br>Facilities WITHOUT data:<br>";
		
		$sql="SELECT name FROM facilitys WHERE facilitycode NOT IN (SELECT DISTINCT (facility) as facility FROM surveillance WHERE epiweek =$epiweek AND DATE =$year AND district =$district) AND district =$district";
		$rst=mysql_query($sql);
		while($value=mysql_fetch_array($rst)){
			$facility_name=$value['name'];
			echo "<font color=\"red\">".$facility_name."</font>";
			echo "<br>";
		}	
		?>
		</div>
	</body>
</html>