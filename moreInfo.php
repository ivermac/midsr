<?php 
	include 'connection/config.php';
	if(isset($_SESSION['session'])){
		$epiweek=$_SESSION['epiweek'] ;
		$facilitycode=$_SESSION['facilitycode'] ;
		
		$db_values=array();
		if(isset($epiweek)){
			if(isset($facilitycode)){
				$sql="SELECT remarks FROM lab_weekly WHERE epiweek='$epiweek' AND facility='$facilitycode'";
				$rst=mysql_query($sql);			
				if($row=mysql_fetch_array($rst)){
					$db_values[0]=$row["remarks"];
								
					$query="select distinct(reportedby) as reportedby, designation,datereportedby from surveillance where epiweek='$epiweek' AND facility='$facilitycode'";
					$r=mysql_query($query);
					if($rows=mysql_fetch_array($r)){
						$db_values[1]=$rows["reportedby"];
						$db_values[2]=$rows["designation"];
						$db_values[3]=$rows["datereportedby"];
					}				
				}		
			}
		}
		
		
		if ($_REQUEST['submit']) {
			$epiweek=$_SESSION['epiweek'];
			
			$district_name=$_SESSION['district_name'];
			$sql="select id from districts where name='$district_name'";
			$result=mysql_query($sql);
			if($row=mysql_fetch_array($result)){
				$district=$row["id"];
			}
			
			$remarks=$_POST['remarks'];	
			$reportedBy=$_POST['reportedBy'];
			$designation=$_POST['designation'];	
			$date=$_POST['date'];
			$sql="UPDATE surveillance SET district='$district',reportedby='$reportedBy',designation='$designation',datereportedby='$date' WHERE epiweek='$epiweek' and facility='$facilitycode'";
			$result=mysql_query($sql)or die(mysql_error());
			$sql="UPDATE lab_weekly SET district='$district',remarks='$remarks' WHERE epiweek='$epiweek' and facility='$facilitycode'";
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
		<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.8.14.custom.min.js"></script>
		<link rel="stylesheet" href="css/jquery.ui.all.css">
		<link rel="stylesheet" href="css/jquery-ui-1.8.14.custom.css">
		<link rel="stylesheet" href="css/demos.css">
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
		<script>
			$(function() {
				$("#date").datepicker({
					showOn : "button",
					buttonImage : "images/calendar.gif",
					buttonImageOnly : true
				});
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
			<br><br>More information
		</div>
		<div class="fb3"><a href="home.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
		<form action="moreInfo.php" method="post">
			<div class="fb3" align="center">
				<table>
					<?php 
					if(isset($db_values)){?>
					<tr><td align="center">Remarks</td></tr>
					<tr><td align="center"><textarea name="remarks" id="remarks" cols=40 rows=6 class="formbox" ><?php echo $db_values[0]; ?></textarea></td></tr>
					<tr><td align="center">Reported by</td></tr>
					<tr><td align="center"><input type="text" name="reportedBy" id="reportedBy" class="formbox" value="<?php echo $db_values[1]; ?>"/></td></tr>
					<tr><td align="center">Designition</td></tr>
					<tr><td align="center"><input type="text" name="designation" id="designation" class="formbox" value="<?php echo $db_values[2]; ?>"/></td></tr>
					<tr><td align="center">Date</td></tr>
					<tr><td align="center"><input type="text" name="date" id="date" class="formbox" readonly="true" value="<?php echo $db_values[3]; ?>"/></td></tr>
					<tr><td align="center"><input type="submit" name="submit" value="Save"  class="formbutton" /></td></tr>	
					<?php }
					else{?>				
					<tr><td align="center">Remarks</td></tr>
					<tr><td align="center"><textarea name="remarks" id="remarks" cols=40 rows=6 class="formbox"></textarea></td></tr>
					<tr><td align="center">Reported by</td></tr>
					<tr><td align="center"><input type="text" name="reportedBy" id="reportedBy" class="formbox" /></td></tr>
					<tr><td align="center">Designition</td></tr>
					<tr><td align="center"><input type="text" name="designation" id="designation" class="formbox" /></td></tr>
					<tr><td align="center">Date</td></tr>
					<tr><td align="center"><input type="text" name="date" id="date" class="formbox" readonly="true"/></td></tr>
					<tr><td align="center"><input type="submit" name="submit" value="Save"  class="formbutton" /></td></tr>
					<?php }?>
					
				</table>
				 
			</div>	
		</form>
	</body>
</html>
<?php
}
else{
	header('location:index.php');
} 
?>