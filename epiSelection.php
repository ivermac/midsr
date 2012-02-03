<?php
session_start();
include 'connection/config.php';

if(isset($_SESSION['session'])){

	if ($_REQUEST['submit']) {
		
		$epiweek = $_POST['epiweek'];
		$facilitycode = $_POST['facilitycode'];
		$today = split("-", date("Y-n-j"));
		$thisyear = $today[0];
		
		$sql = "SELECT * FROM `lab_weekly` WHERE year(datecreated)=$thisyear and epiweek=$epiweek and facility=$facilitycode";
		$results = mysql_query($sql);
		$count = mysql_num_rows($results);
		if ($count>=1) {
			header('location:epiSelection.php');
		}
		else{
			$_SESSION['epiweek'] = $_POST['epiweek'];
			$_SESSION['weekending'] = $_POST['weekending'];
			$_SESSION['facilitycode'] = $_POST['facilitycode'];
			
			header('location:home.php');
			
			$epiweek = $_POST['epiweek'];
			$weekending = $_POST['weekending'];
			$today = date("Y-n-j");
			$facilitycode = $_POST['facilitycode'];
			$sql = "INSERT INTO lab_weekly (epiweek,weekending,datecreated,facility) VALUES ('$epiweek','$weekending','$today','$facilitycode')";
			$result = mysql_query($sql) or die(mysql_error());
		}	
	}
	else if($_REQUEST['edit']){
		
		$epiweek = $_POST['epiweek'];
		$facilitycode = $_POST['facilitycode'];
		$selectedYear = split("-",$_POST['weekending']);
		$thatYear=$selectedYear[0];
		$_SESSION['thatYear']=$thatYear;
		$today = split("-", date("Y-n-j"));
		$thisyear = $today[0];
		
		$sql = "SELECT * FROM `lab_weekly` WHERE year(datecreated)=$thatYear and epiweek=$epiweek and facility=$facilitycode";
		$results = mysql_query($sql);
		$count = mysql_num_rows($results);
		if ($count==1) {
			$_SESSION['epiweek'] = $_POST['epiweek'];
			$_SESSION['weekending'] = $_POST['weekending'];
			$_SESSION['facilitycode'] = $_POST['facilitycode'];
			
			header('location:summary.php');
		}
		else{
			header('location:epiSelection.php');
		}
		
	}
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
		<script>
			var xmlhttp;
			var variable;
			function exist (epiweek,facilitycode) {
						  							
		        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp = new XMLHttpRequest();
		        }
		        else {// code for IE6, IE5
		            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		        }	                        
		        xmlhttp.onreadystatechange = function(){
		            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	                                    
		                variable=xmlhttp.responseText;
		                return variable;		              		                    	                			                	       		                        	                                
		           	}            	                                                 		            
		        }
		        xmlhttp.open("GET", "http://localhost/idsrmobile/validator.php?epiweek="+epiweek+"&facilitycode="+facilitycode, true);
		        xmlhttp.send();
		        return variable;	       
			}
			function validate() {
				var epiweek = document.getElementById("epiweek").value;
				var weekending = document.getElementById("weekending").value;
				var facilitycode = document.getElementById("facilitycode").value;
								            
				if(epiweek == "" || weekending == "") {
					document.getElementById("input_error").innerHTML = "please fill all text fields";
					return false;
				}
				if(epiweek < 1 || epiweek > 53) {
					document.getElementById("input_error").innerHTML = "epiweek ranges between week 1 and 52/53.";
					return false;
				}
			}
		</script>
		
		<script type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.8.14.custom.min.js"></script>
		<link rel="stylesheet" href="css/jquery.ui.all.css">
		<link rel="stylesheet" href="css/jquery-ui-1.8.14.custom.css">
		<link rel="stylesheet" href="css/demos.css">
		<link href="css/idsr.css" type="text/css" rel="stylesheet" />
		<script>
			$(function() {
				$("#weekending").datepicker({
					showOn : "button",
					dateFormat:$.datepicker.ATOM,
					buttonImage : "images/calendar.gif",
					buttonImageOnly : true,
    				beforeShowDay: function(date) {
				        var day = date.getDay();
				        //var epiweek= $.datepicker.iso8601Week(new Date );
				        //document.getElementById("epiweek").value=epiweek; 
				        //return [(day != 1 && day != 2&& day != 3 && day != 4 && day != 5 && day != 6), ''];
				        //return [(date.getDay()> 1), ''];
				        return [(day ==0 ), ''];
				    },
				    onSelect:function(){
				    	var parsedDate=new Date(document.getElementById("weekending").value);		    	
				    	var epiweek= $.datepicker.iso8601Week( parsedDate);
				        document.getElementById("epiweek").value=epiweek;				    }				
				});
			});

		</script>
	</head>
	<body>
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" />
			<br>
			Integrated Disease Surveillance and Response
		</div>		
		<form action="epiSelection.php" method="post" >
			<div class="fb3" ><a href="post_index.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a></div>
			<div class="fb3" align="center">
				<table>
					<tr><th><font color="#ff0000"><p name="input_error" id="input_error" ></p></font></th></tr>
					<tr><td align="center">Facility</td></tr><tr><td><select name="facilitycode" id="facilitycode">
					<?php
					$district_name = $_SESSION['district_name'];
					$sql = "SELECT facilitycode,name FROM  `facilitys` WHERE district IN (SELECT id FROM districts WHERE name =  '$district_name')";
					$results = mysql_query($sql);
					while ($rows = mysql_fetch_array($results)) {
						$facilitycode = $rows["facilitycode"];
						$facilityname = $rows["name"];
						echo "<option value=$facilitycode>$facilityname</option>";
					}
					?>
				</select></td></tr>
					<tr><td align="center">Week Ending</td></tr><tr><td><input type="text" id="weekending" name="weekending" class="formbox" readonly="true" size="30"/></td></tr>
					<tr><td align="center">Epiweek</td></tr><tr><td><input type="text" id="epiweek" name="epiweek" class="formbox" size="30" readonly="true"/></td></tr>
					
				</table>
			</div>
			<div class="fb3" align="center">
				<input type="submit" name="submit" value="New Data"  class="formbutton" onclick="return validate();"/>
				<input type="submit" name="edit" value="Edit Data"  class="formbutton" />
			</div>
		</form>
	</body>
	<!-- InstanceEnd -->
</html>