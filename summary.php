<?php
	session_start();
	include 'connection/config.php'; 
	if(isset($_SESSION['session'])){
		$epiweek = $_SESSION['epiweek'];
		$facilitycode = $_SESSION['facilitycode'];
		$today = split("-", date("Y-n-j"));
		if(isset($_SESSION['thatYear'])){
			$thisyear=$_SESSION['thatYear'];
		}
		else{
			$thisyear = $today[0];
		}
		
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
				/*
				 * the code below retrieves the facility name that corresponds with the facilitycode the name is printed
				 */
				$sql="SELECT name FROM facilitys WHERE facilitycode=$facilitycode";
				$rs=mysql_query($sql);
				$value=mysql_fetch_array($rs);
				$facility_code=$value['name'];
				echo "Epiweek:<font color=\"#008000\" ><u>".$epiweek."</u></font>&nbsp;&nbsp;&nbsp; Facility:<font color=\"#008000\" ><u>".$facility_code."</u></font>";
			?>
			<br><br>Summary
		</div>
		<div class="fb3"><a href="home.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Home</a> </div>
		<div class="fb1" align="center">
			<h2>DISEASES</h2>
		<?php
		$sql="select disease from surveillance where epiweek=$epiweek and facility=$facilitycode and year(datecreated)=$thisyear";
		$results=mysql_query($sql);
		$index=0;
		$diseases=array();
		while($row=mysql_fetch_array($results)){
			$district=$row["disease"];
			$diseases[$index]=$district;
			$index++;
		}
		
		// the php snippet above retrieves the diseases (disease id's) with data from the surveillance table for the specific epiweek, facility and year
		 
		
		for ($i=0; $i < count($diseases); $i++) {
			$sql="select name from diseases where id=$diseases[$i]"; //get the alphanumeric names of the disease id's
			$result=mysql_query($sql);
			if($row=mysql_fetch_array($result)){
				$diseasename=$row["name"]; 
				$sql="select lmcase,lfcase,lmdeath,lfdeath,gmcase,gfcase,gmdeath,gfdeath from surveillance where epiweek=$epiweek and facility=$facilitycode and year(datecreated)=$thisyear and disease=$diseases[$i]";
				$rs=mysql_query($sql); 
				while($r=mysql_fetch_array($rs)){
					$lmcase=$r["lmcase"];
					$lfcase=$r["lfcase"];
					$lmdeath=$r["lmdeath"];
					$lfdeath=$r["lfdeath"];
					$gmcase=$r["gmcase"];
					$gfcase=$r["gfcase"];
					$gmdeath=$r["gmdeath"];
					$gfdeath=$r["gfdeath"];
					//retrieve the case and death detiails of the specific disease and display them in the table below for every disease 
					?>
					
					<table border="0">
							<tr >
								<td colspan="6" align="center"><?php echo $diseasename;?></td>
							</tr>
							<tr >
								<th>Age</th>
								<th colspan="2" align="center">Cases</th>
								<th colspan="2" align="center">Deaths</th>
							</tr>
							<tr>
								<td></td>
								<td>M</td>
								<td>F</td>
								<td>M</td>
								<td>F</td>
								
							</tr>
							<tr>
								<td><5yrs</td>
								<td><font color="green"><?php echo $lmcase;?></font></td>
								<td><font color="green"><?php echo $lfcase;?></font></td>
								<td><font color="green"><?php echo $lmdeath;?></font></td>
								<td><font color="green"><?php echo $lfdeath;?></font></td>
								
							</tr>
							<tr>
								<td>>5yrs</td>
								<td><font color="green"><?php echo $gmcase;?></font></td>
								<td><font color="green"><?php echo $gfcase;?></font></td>
								<td><font color="green"><?php echo $gmdeath;?></font></td>
								<td><font color="green"><?php echo $gfdeath;?></font></td>
								
							</tr>							
					</table><br><hr />
					<?php
				}
			}
		}
		$sql="select name from diseases where id not in (select disease from surveillance where epiweek=$epiweek and facility=$facilitycode and year(datecreated)=$thisyear)";
		//the sql statement above retrieves diseases that have no data for this epiweek and facility in the surveillance table
		$res=mysql_query($sql);
		$count=mysql_num_rows($res);
		if($count>0){
			$sql="select name from facilitys where facilitycode=$facilitycode"; //retrieve the alphanumeric name of the the facility code
			$rst=mysql_query($sql);
			$value=mysql_fetch_array($rst);
			$facility_name=$value['name'];
			echo "Diseases without data from <font color=\"green\">$facility_name</font> for epiweek <font color=\"green\">$epiweek</font> :"."<br><hr>";
			while($row=mysql_fetch_array($res)){?>
			<font color="#ff0000"><?php echo $row["name"]."|";?></font>
		<?php
			}
		}
		
		echo "<hr> <p><h2>LAB WEEKLY MALARIA INFORMATION</h2></p>";
		$sql="select * from lab_weekly where epiweek=$epiweek and facility=$facilitycode and year(datecreated)=$thisyear";
		$rst=mysql_query($sql);
		$remarks="";
		$reportedby="";
		$designition="";
		$datereported="";
		if($row=mysql_fetch_array($rst)){
			$tstAbv5=$row['malaria_above_5'];
			$tstBlw5=$row['malaria_below_5'];
			$pstAbv5=$row['positive_above_5'];
			$pstBlw5=$row['positive_below_5'];
			$remarks=$row['remarks'];
			echo "<table>
					<tr>
						<td>Total</td>
						<td>Below 5</td>
						<td>Above 5</td>
					</tr>
					<tr>
						<td>Tested</td>
						<td><font color=\"green\">$tstBlw5</font></td>
						<td><font color=\"green\">$tstAbv5</font></td>					
					</tr>
					<tr>
						<td>Positive</td>
						<td><font color=\"green\">$pstBlw5</font></td>
						<td><font color=\"green\">$pstAbv5</font></td>
					</tr>
				</table>";
		}
		
		echo "<hr> <p><h2>MORE INFORMATION</h2></p>";
		$sql="select reportedby,designation,datereportedby from surveillance where epiweek=$epiweek and facility=$facilitycode and year(datecreated)=$thisyear";
		$r=mysql_query($sql);
		if($row=mysql_fetch_array($r)){
			$reportedby=$row['reportedby'];
			$designition=$row['designation'];
			$datereported=$row['datereportedby'];
		}
		echo "<table>
					<tr>
						<td>Remarks</td>
						<td><font color=\"green\">$remarks</></td>
					</tr>
					<tr>
						<td>Reported By</td>
						<td><font color=\"green\">$reportedby</font></td>
					</tr>
					<tr>
						<td>Designition</td>
						<td><font color=\"green\">$designition</font></td>
					</tr>
					<tr>
						<td>Date</td>
						<td><font color=\"green\">$datereported</font></td>
					</tr>
				</table>";
	 }
	 else{
		 header('location:index.php');
	 } 
		?>
		</div>
		<div class="fb3"><a href="epiSelection.php"  accesskey="3">Facility/Epiweek selection<img src="images/larrow.png" width="8" height="13" alt="" align="right" /></a></div>
		<div class="fb3"><a href="index.php"  accesskey="3"><font color="#ff0000">Log Out</font><img src="images/larrow.png" width="8" height="13" alt="" align="right" /></a></div>
  </body>