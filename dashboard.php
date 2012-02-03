<?php
session_start();
include 'connection/config.php'; 
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>IDSR</title>
  <link href="css/idsr.css" type="text/css" rel="stylesheet" />
  <script  type="text/javascript" src="scripts/jquery-1.6.1.min.js"></script>
  <script>
  	function show(){  
  		var epiweek=document.getElementById("selectEpiweek").value;
        var year=document.getElementById("selectYear").value;
        var province=document.getElementById("selectProvince").value;
        if(epiweek==0 || year==0 || province==null){
            //alert("kindly select valid data");
        	document.getElementById("input_error").innerHTML="Kindly select valid data";
        	document.getElementById("reportedDistrict").innerHTML = "";
			document.getElementById("districtNo").innerHTML = "";
			document.getElementById("percentage").innerHTML = "";
			document.getElementById("stats").innerHTML = "";
			document.getElementById("disease_stats").innerHTML="";
        }
        else{
        	document.getElementById("input_error").innerHTML="";	  	
  			var xmlhttp;
        	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        		xmlhttp = new XMLHttpRequest();
        	}
        	else {// code for IE6, IE5
            	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        	}	                        
        	xmlhttp.onreadystatechange = function(){
        		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	                                    
        			var variable=xmlhttp.responseText;
        			var value=variable.split("_");
        			document.getElementById("reportedDistrict").innerHTML = "<font color=\"#008000\">"+value[0]+"</font>";
        			document.getElementById("districtNo").innerHTML = "<font color=\"#008000\">"+value[1]+"</font>";
        			document.getElementById("percentage").innerHTML = "<font color=\"#008000\">"+value[2]+"</font>";
        			var notNum=isNaN(value[3]);
        			if(notNum)
        				document.getElementById("stats").innerHTML = "Statistics for epiweek <font color=\"#008000\">"+epiweek+"</font> and year <font color=\"#008000\">"+year+"</font> for <font color=\"#008000\">"+value[3]+"</font> province ";
        			else
        				document.getElementById("stats").innerHTML = "Statistics for epiweek <font color=\"#008000\">"+epiweek+"</font> and year <font color=\"#008000\">"+year+"</font> for all provinces ";
        			var table="<table ><tr><th><font color=\"#008000\">Disease</font></th><th><font color=\"#008000\">Cases</font></th><th><font color=\"#008000\">Deaths</font></th></tr>";	
        			var perDisease=value[4].split("#");
        			var count=0;
        			for (a = 0; a < perDisease.length-1;a++) {
        				var perColumn= perDisease[a].split("*");
        				var row="";
        				if(count == 0){
        					row="<tr bgcolor=\"#F0F0FF\"><td align=\"left\">"+perColumn[2]+"</td><td align=\"center\">"+perColumn[0]+"</td><td align=\"center\">"+perColumn[1]+"</td><tr>";
        					count++;
        				}
        				else{
        					row="<tr><td align=\"left\">"+perColumn[2]+"</td><td align=\"center\">"+perColumn[0]+"</td><td align=\"center\">"+perColumn[1]+"</td><tr>";
        					count--;
        				}
        				table+=row;
        			}
        			table+="</table>";	
        			document.getElementById("disease_stats").innerHTML = table;	       			                        	                                
        		}
        	}       		                            
        	xmlhttp.open("POST", "http://localhost/IDSRmobile/dashboardQuery.php", true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("year="+year+"&epiweek="+epiweek+"&province="+province);
        }                             	
  	}
  </script>	
  </head>
	<body>
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br>MINISTRY OF PUBLIC HEALTH AND SANITATION<br></>Intergratead Disease Surveillance and Response</div>
		<div class="fb3"><a href="index.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
		<form action="dashboard.php" method="post" id="LoginForm">
			<div class="fb1" align="center">
			<table>
				<tr><th colspan=2>MINI-DASHBOARD</th></tr>
				<tr><th colspan=2><font color="#ff0000"><p name="input_error" id="input_error" ></p></font></th></tr>
				<tr>
					<td>Year</td>
					<td>  <select name="selectYear" id="selectYear"><option value="0">--Select--</option>
						<?php 
							$sql="SELECT DISTINCT date FROM surveillance";
							$rs=mysql_query($sql);
							while ($row = mysql_fetch_array($rs)) {
								$date=$row['date'];
  								echo "<option value=\"$date\">$date</option>";
							}
 						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Epiweek</td>
					<td>  <select name="selectEpiweek" id="selectEpiweek"><option value="0">--Select--</option>
						<?php 
							$sql="SELECT DISTINCT epiweek FROM surveillance";
							$rs=mysql_query($sql);
							while ($row = mysql_fetch_array($rs)) {
								$epiweek=$row['epiweek'];
  								echo "<option value=\"$epiweek\">$epiweek</option>";
							}
 						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Province</td>
					<td> 
						<?php
							$sql="SELECT id,name FROM provinces";
							$rs=mysql_query($sql); 
							echo "<select name=\"selectProvince\" id=\"selectProvince\">";
							echo "<option value=\"0\">All</option>";
							while ($row=mysql_fetch_assoc($rs)) {	
								$id=$row['id'];
								$name=$row['name'];	
	  							echo "<option value=\"$id\">$name</option>";
							}
							echo "</select>";
	 					?>
	 				</td>
 		 	   </tr>
 			   <tr><td colspan=2><input type="button" name="filter" value="filter"  class="formbutton" onclick="show()"/></td></tr>  			   
 			   <tr>
 			   	<th colspan=2 name="stats" id="stats">
 			   		Statistics for 
 			   		<?php 
 			   			$sql="SELECT MAX( DISTINCT epiweek ) AS epiweek, MAX( DISTINCT DATE ) AS year FROM  `surveillance` ";
 			   			$rs=mysql_query($sql); 							
						if ($row=mysql_fetch_assoc($rs)) {	
							$epiweek=$row['epiweek'];
							$year=$row['year'];						
	  						echo "epiweek $epiweek and year $year";
						}
 			   		?>
 			   	</th> 
 			   </tr>
 			   <tr>
 			   	<td>Districts Reported</td>
 			   	<td name="reportedDistrict" id="reportedDistrict">
 			   		<font color="#008000">
 			   		<?php 
 			   			$sql="SELECT MAX( DATE ) as year FROM  surveillance";
 			   			$rs=mysql_query($sql);
 			   			if($row=mysql_fetch_array($rs)){
 			   				$currentYear=$row['year'];
 			   				$sql="SELECT COUNT( DISTINCT (district) ) AS disticts FROM  surveillance WHERE epiweek =$epiweek AND DATE =$currentYear";
 			   				$rs=mysql_query($sql);
 			   				if($row=mysql_fetch_array($rs)){
 			   					$reportedDistricts=$row['disticts'];
 			   					echo $reportedDistricts;
 			   				}
 			   			}
 			   		?>
 			   		</font>
 			   	</td>
 			   </tr>
 			   <tr>
 			   	<td>No. of Districts</td>
 			   	<td name="districtNo" id="districtNo">
 			   		<font color="#008000">
 			   		<?php
							$sql="SELECT COUNT( id ) as number FROM  districts WHERE flag =1";
							$rs=mysql_query($sql); 							
							while ($row=mysql_fetch_assoc($rs)) {	
								$number=$row['number'];						
	  							echo $number;
							}							
	 				?>
	 				</font>
 			   	</td>
 			   </tr>
 			   <tr>
 			   	<td>Reported (%)</td>
 			   	<td name="percentage" id="percentage"><font color="#008000"><?php echo round(($reportedDistricts/$number)*100,2) ;?></font></td>
 			   </tr>
		</table>
		<br>
		<div>
		</div>
		<br>
		<div name="disease_stats" id="disease_stats" align="center"></div>
		</div>	
	</form>    
</body>
<!-- InstanceEnd --></html>