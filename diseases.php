<?php 
	session_start();
	include 'connection/config.php';
	if(isset($_SESSION['session'])){
		$epiweek=$_SESSION['epiweek'] ;
		$facilitycode=$_SESSION['facilitycode'] ;
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>IDSR</title>
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
		<script  type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
		<script>
			$(".fb3").click(function(){
		     	window.location=$(this).find("a").attr("href");
		     	return false;
			});
		</script>
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
		<div class="fb3"><a href="home.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>		
	<?php
		$sql="SELECT id,name FROM diseases WHERE flag=1";
		$rs=mysql_query($sql); 
		while($value=mysql_fetch_array($rs)){
			$disease_id=$value["id"];
			$disease_name=$value["name"];
			$_SESSION['disease']=$disease_id;
			echo "<div class=\"fb3\"><a href=\"dataEntry.php?diseaseid=$disease_id\"  accesskey=\"2\">$disease_name <img src=\"images/arrow.png\" width=\"8\" height=\"13\" alt=\"\" align=\"right\" /></a></div>";
			
		}
	}
	else{
		header('location:index.php');
	} 
	 ?>  
	</body>
</html>