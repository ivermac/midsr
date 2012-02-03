<?php
	session_start(); 
	include 'connection/config.php';
	if(isset($_SESSION['session'])){
		$epiweek=$_SESSION['epiweek'];
		$weekending = $_SESSION['weekending'];
		if(isset($_GET['diseaseid'])){
			$diseaseid = $_GET['diseaseid'];
			$_SESSION['disease']=$diseaseid;
		}
		else
			$diseaseid = $_SESSION['disease'];
		$submitted= $_SESSION['submitted'];
		$expected=$_SESSION['expected'];
		$today = date("Y-n-j");
		$facilitycode=$_SESSION['facilitycode'] ;
		
		$district_name=$_SESSION['district_name'];
		
		$sql="select id from districts where name='$district_name'";
		$result=mysql_query($sql);
		if($row=mysql_fetch_array($result)){
			$district=$row["id"];
		}
		
		$db_values=array();
		if(isset($diseaseid)){
			if(isset($epiweek)){
				if(isset($district)){
					$sql="select  *  from surveillance where disease='$diseaseid' and epiweek='$epiweek' and facility='$facilitycode'";
					$rst=mysql_query($sql);
					
					if($row=mysql_fetch_array($rst)){
						$db_values[0]=$row["lmcase"];
						$db_values[1]=$row["lfcase"];
						$db_values[2]=$row["lmdeath"];
						$db_values[3]=$row["lfdeath"];
						$db_values[4]=$row["gmcase"];
						$db_values[5]=$row["gfcase"];
						$db_values[6]=$row["gmdeath"];
						$db_values[7]=$row["gfdeath"];
						
						if($diseaseid==1){
							$query="select * from lab_weekly where epiweek='$epiweek' and facility='$facilitycode'";
							$r=mysql_query($query);
							if($rows=mysql_fetch_array($r)){
								$db_values[8]=$rows["malaria_below_5"];
								$db_values[9]=$rows["malaria_above_5"];
								$db_values[10]=$rows["positive_below_5"];
								$db_values[11]=$rows["positive_above_5"];
							}
						}
					}		
				}
			}
		}
		
		if ($_REQUEST['submit']) {	
			$data=$_POST['data'];
			$sql="SELECT * FROM surveillance WHERE disease='$diseaseid' AND epiweek='$epiweek' and district = '$district' and facility='$facilitycode'";
			$rs=mysql_query($sql) or die(mysql_error());
			$count=mysql_num_rows($rs);
			if($count==1){		
				$sql="UPDATE surveillance SET datemodified='$today',lmcase='$data[0]',lfcase='$data[1]',lmdeath='$data[2]',lfdeath='$data[3]',gmcase='$data[4]',gfcase='$data[5]',gmdeath='$data[6]',gfdeath='$data[7]' WHERE disease='$diseaseid' AND epiweek='$epiweek' AND district = '$district'";
				mysql_query($sql) or die(mysql_error());
				if($diseaseid==1){
					$sql="UPDATE lab_weekly SET district='$district',malaria_below_5='$data[8]',malaria_above_5='$data[9]',positive_below_5='$data[10]',positive_above_5='$data[11]' WHERE epiweek='$epiweek' AND facility='$facilitycode'";
					mysql_query($sql)or die(mysql_error());
				}
				header('location:diseases.php');		
			}
			else{									
				$today = date("Y-n-j");
				$sql="INSERT INTO surveillance (district,facility,lmcase,lfcase,lmdeath,lfdeath,gmcase,gfcase,gmdeath,gfdeath,disease,epiweek,weekending,datecreated,datereportedby,submitted,expected) 
				VALUES ('$district','$facilitycode','$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$diseaseid','$epiweek','$weekending','$today','$today','$submitted','$expected')";
				mysql_query($sql) or die(mysql_error());
				if($diseaseid==1){
					$sql="UPDATE lab_weekly SET district='$district',malaria_below_5='$data[8]',malaria_above_5='$data[9]',positive_below_5='$data[10]',positive_above_5='$data[11]' WHERE epiweek='$epiweek' AND facility='$facilitycode'";
					mysql_query($sql)or die(mysql_error());
				}
				header('location:diseases.php');
			}
			
		}
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>IDSR</title>
<link href="css/idsr.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript"></script>
	<script  type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
	<script>
		//zero reporting script
		$(document).ready(function(){
			$("#zeroreport").change(function(){
			 	if($("#zeroreport").is(':checked')){
				 	document.getElementById("a_malecase").value=0;
				 	document.getElementById("a_femalecase").value=0;
				 	document.getElementById("a_maledeath").value=0;
				 	document.getElementById("a_femaledeath").value=0;
				 	document.getElementById("b_malecase").value=0;
				 	document.getElementById("b_femalecase").value=0;
				 	document.getElementById("b_maledeath").value=0;
				 	document.getElementById("b_femaledeath").value=0;
				 	document.getElementById("testAbove").value=0;
				 	document.getElementById("testBelow").value=0;
				 	document.getElementById("postBelow").value=0;
				 	document.getElementById("postAbove").value=0;
				}
				else{
					document.getElementById("a_malecase").value="";
				 	document.getElementById("a_femalecase").value="";
				 	document.getElementById("a_maledeath").value="";
				 	document.getElementById("a_femaledeath").value="";
				 	document.getElementById("b_malecase").value="";
				 	document.getElementById("b_femalecase").value="";
				 	document.getElementById("b_maledeath").value="";
				 	document.getElementById("b_femaledeath").value="";
				 	document.getElementById("testAbove").value="";
				 	document.getElementById("testBelow").value="";
				 	document.getElementById("postBelow").value="";
				 	document.getElementById("postAbove").value="";
				}
			 });
		});

		//validation
		function validate(){
			var a_malecase=document.getElementById("a_malecase").value;
			var a_femalecase=document.getElementById("a_femalecase").value;
		 	var a_maledeath=document.getElementById("a_maledeath").value;
		 	var a_femaledeath=document.getElementById("a_femaledeath").value;
		 	var b_malecase=document.getElementById("b_malecase").value;
			var b_femalecase=document.getElementById("b_femalecase").value;
		 	var b_maledeath=document.getElementById("b_maledeath").value;
		 	var b_femaledeath=document.getElementById("b_femaledeath").value;
			if(a_malecase==""||a_femalecase==""||a_maledeath==""||a_femaledeath==""||b_malecase==""||b_femalecase==""||b_maledeath==""||b_femaledeath==""){
				document.getElementById("input_error").innerHTML="please fill all text fields";
				return false;
			}
			else if (!a_malecase.match('[0-9]')||!a_femalecase.match('[0-9]')||!a_maledeath.match('[0-9]')||!a_femaledeath.match('[0-9]')||!b_malecase.match('[0-9]')||!b_femalecase.match('[0-9]')||!b_maledeath.match('[0-9]')||!b_femaledeath.match('[0-9]')) {
				document.getElementById("input_error").innerHTML="All values should be numerical";
				return false;
			}
		}
	</script>
  </head>
<body>
<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br><font color="#3B5998" >Integrated Disease Surveillance and Response</font> <br>
	<?php
		$sql="SELECT name FROM facilitys WHERE facilitycode=$facilitycode";
		$rs=mysql_query($sql);
		$value=mysql_fetch_array($rs);
		$facilitycode=$value['name'];
		echo "Epiweek:<font color=\"#008000\" ><u>".$epiweek."</u></font>&nbsp;&nbsp;&nbsp; Facility:<font color=\"#008000\" ><u>".$facilitycode."</u></font><br><br>";
		$sql="select name from diseases where id=$diseaseid";
		$results=mysql_query($sql); 
		if($value=mysql_fetch_array($results)){
			echo $value['name'];
		}
	?>
	</div>

<div class="fb3"><a href="diseases.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
<form action="dataEntry.php" method="post" name="dataEntry">
<div class="fb3" align="center">
	<table>
		<?php 
			if(isset($db_values)){?>
				<tr><th colspan=2><font color="#ff0000"><p name="input_error" id="input_error" ></p></font></th></tr>
				<tr><th colspan=2><h2>ABOVE 5</h2></th></tr>
				<tr><th colspan=2>CASES</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="a_malecase" class="formbox" value="<?php echo $db_values[0]; ?>"/></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="a_femalecase" class="formbox" value="<?php echo $db_values[1]; ?>"/></td></tr>
				<tr><th colspan="2">DEATHS</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="a_maledeath" class="formbox" value="<?php echo $db_values[2]; ?>"/></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="a_femaledeath" class="formbox" value="<?php echo $db_values[3]; ?>"/></td></tr>
				<tr><td colspan="2"><font color="#ff0000"><p name="input_error" id="input_error" ></p></font></td></tr>
				<tr><th colspan=2><h2>BELOW 5</h2></th></tr>
				<tr><th colspan="2">CASES</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="b_malecase" class="formbox" value="<?php echo $db_values[4]; ?>"/></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="b_femalecase" class="formbox" value="<?php echo $db_values[5]; ?>"/></td></tr>
				<tr><th colspan="2">DEATHS</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="b_maledeath" class="formbox" value="<?php echo $db_values[6]; ?>"/></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="b_femaledeath" class="formbox" value="<?php echo $db_values[7]; ?>"/></td></tr>			
		<tr></tr>
		<?php }
			else{
		?>
				<tr><th colspan=2><font color="#ff0000"><p name="input_error" id="input_error" ></p></font></th></tr>
				<tr><th colspan=2>above 5</th></tr>
				<tr><th colspan=2><h2>ABOVE 5</h2></th></tr>
				<tr><th colspan=2>CASES fgh</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="a_malecase" class="formbox" /></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="a_femalecase" class="formbox" /></td></tr>
				<tr><th colspan="2">DEATHS</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="a_maledeath" class="formbox" /></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="a_femaledeath" class="formbox" /></td></tr>
				<tr><td colspan="2"><font color="#ff0000"><p name="input_error" id="input_error" ></p></font></td></tr>
				<tr><th colspan=2><h2>BELOW 5</h2></th></tr>
				<tr><th colspan="2">CASES</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="b_malecase" class="formbox" /></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="b_femalecase" class="formbox" /></td></tr>
				<tr><th colspan="2">DEATHS</th></tr>	
				<tr><td>Males</td> <td><input type="text" name="data[]" id="b_maledeath" class="formbox" /></td></tr>
				<tr><td>Females</td> <td><input type="text" name="data[]" id="b_femaledeath" class="formbox" /></td></tr>
				
			<?php }
				if($diseaseid==1){?>
					<tr><th colspan=2><h2>LAB MALARIA WEEKLY INFO</h2></th></tr>
				<tr><th colspan=2>Total No. Tested</th></tr>
				  <tr><td>Male</td> <td><input type="text" name="data[]" id="testAbove" class="formbox" value="<?php echo $db_values[8]; ?>"></td></tr>
				  <tr><td>Female</td> <td><input type="text" name="data[]" id="testBelow" class="formbox" value="<?php echo $db_values[9]; ?>"/></td></tr>
				  <tr><th colspan=2>Total No. Positive</th></tr>
				  <tr><td>Male</td> <td><input type="text" name="data[]" id="postAbove" class="formbox" value="<?php echo $db_values[10]; ?>"/></td></tr>
				  <tr><td>Female</td> <td><input type="text" name="data[]" id="postBelow" class="formbox" value="<?php echo $db_values[11]; ?>"/></td></tr>
				<?php }
				?>
		<tr><th colspan="2"><input type="checkbox" name="zeroreport" id="zeroreport" class="formbox" />zero reporting</th></tr>
	 	<tr><th colspan="2"><input type="submit" name="submit" value="Save"  class="formbutton" onclick="return validate()"/></th></tr>
 	</table>
 </div>
<input type="hidden" name = "age" value = "<?php echo $_GET['age']?>"/>
<input type="hidden" name = "diseaseid" value = "<?php echo $_GET['diseaseid']?>"/>
</form>
</body>
</html>
<?php 
}
	else{
		header('location:index.php');
	} 
?>
