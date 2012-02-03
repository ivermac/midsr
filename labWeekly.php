<?php
	session_start(); 
	include 'connection/config.php';
	if(isset($_SESSION['session'])){
		$epiweek=$_SESSION['epiweek'] ;
		$facilitycode=$_SESSION['facilitycode'] ;
		if ($_REQUEST['submit']) {
			$epiweek=$_SESSION['epiweek'];
			
			$district_name=$_SESSION['district_name'];
			$sql="select id from districts where name='$district_name'";
			$result=mysql_query($sql);
			if($row=mysql_fetch_array($result)){
				$district=$row["id"];
			}
			
			$data=$_POST['data'];	
			$sql="UPDATE lab_weekly SET district='$district',malaria_above_5='$data[0]',malaria_below_5='$data[1]',positive_above_5='$data[2]',positive_below_5='$data[3]' WHERE epiweek='$epiweek'";
			$result=mysql_query($sql)or die(mysql_error());
			header('location:home.php');
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
			//zero reporting script
			$(document).ready(function(){
				$("#zeroreport").change(function(){
				 	if($("#zeroreport").is(':checked')){
					 	document.getElementById("testAbove").value=0;
					 	document.getElementById("testBelow").value=0;
					 	document.getElementById("postAbove").value=0;
					 	document.getElementById("postBelow").value=0;
					}
					else{
						document.getElementById("testAbove").value="";
					 	document.getElementById("testBelow").value="";
					 	document.getElementById("postAbove").value="";
					 	document.getElementById("postBelow").value="";
					}
				 });
			});
	
			//validation
			function validate(){			
				var testAbove=document.getElementById("testAbove").value;
				var testBelow=ocument.getElementById("testBelow").value;
				var postAbove=document.getElementById("postAbove").value;
				var postBelow=ddocument.getElementById("postBelow").value;
				alert(testAbove+" "+testBelow+" "+postAbove+" "+postBelow)
			 	if(testAbove==""||testBelow==""||postAbove==""||postBelow==""){
					document.getElementById("input_error").innerHTML="please fill all text fields";
					return false;
				}
				
			}
		</script>
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
			<br><br> Lab Weekly Malaria Info
		</div>	
		<div class="fb3"><a href="home.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
		<form action="labWeekly.php" method="post">
			<div class="fb3" align="center"><font color="#ff0000"><p name="input_error" id="input_error" ></p></font>Total No. Tested</div>	
			<div class="fb3" align="center">Above 5  <input type="text" name="data[]" id="testAbove" class="formbox" /></div>	
			<div class="fb3" align="center">Below 5  <input type="text" name="data[]" id="testBelow" class="formbox" /></div>
			<div class="fb3" align="center">Total No. Positive</div>	
			<div class="fb3" align="center">Above 5  <input type="text" name="data[]" id="postAbove" class="formbox" /></div>	
			<div class="fb3" align="center">Below 5  <input type="text" name="data[]" id="postBelow" class="formbox" /></div>
			<div class="fb3" align="center"><input type="checkbox" name="zeroreport" id="zeroreport" class="formbox" />zero reporting</div>
			<div class="fb3" align="center">  <input type="submit" name="submit" value="Save"  class="formbutton" onclick="return validate()"/></div>
		</form>
	</body>
</html>
<?php 
}
else{
	header('location:index.php');
} 
?>