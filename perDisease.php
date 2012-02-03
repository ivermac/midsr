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
  			var year=document.getElementById("selectYear").value;
        	var disease=document.getElementById("selectDisease").value;  	  	
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
        			document.getElementById("disease_stats").innerHTML = variable;
        		}
        	}       		                            
        	xmlhttp.open("POST", "http://localhost/IDSRmobile/perDiseaseQuery.php", true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("year="+year+"&disease="+disease);
                                     	
  	}
  </script>	
  </head>
	<body>
		<div class="fb1" align="center"><img name="accounts" src="images/naslogo.jpg" alt="" align="center" /><br>MINISTRY OF PUBLIC HEALTH AND SANITATION<br></>Intergratead Disease Surveillance and Response</div>
		<div class="fb3"><a href="dashboard.php"><img src="images/larrow.png" width="10" height="15" alt="" /> Back</a> </div>
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
					<td>Disease</td>
					<td>  <select name="selectDisease" id="selectDisease"><option value="0">--Select--</option>
						<?php 
							$sql="SELECT name FROM diseases";
							$rs=mysql_query($sql);
							while ($row = mysql_fetch_array($rs)) {
								$epiweek=$row['name'];
  								echo "<option value=\"$epiweek\">$epiweek</option>";
							}
 						?>
						</select>
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
	  						echo "Year: $year";
						}
 			   		?>
 			   	</th> 
 			   </tr>			   
		</table>
		<?php
			$months=array("January","February","March","April","May","June","July","August","September","October","November","December");
			echo "<table>";
			for ($i=0; $i < count($months); $i++) { 
				echo "<tr>
						<td>$months[$i]</td>
						<td></td>
					  </tr>";
			}
			
			echo "</table>";
		?>
		<br>
		<div>
			<?php 
				/*$rs=mysql_query("SELECT id,name FROM diseases GROUP BY id ASC ");
				$diseasDetails="";
				while($rows=mysql_fetch_array($rs)){
					$id=$rows['id'];
					$name=$rows['name'];
					$concat=$id."-".$name.",";
					$diseasDetails.=$concat;
				}
				echo $diseasDetails;*/
			?>
		</div>
		<br>
		<div name="disease_stats" id="disease_stats" align="center">hello display</div>
		</div>	
	</form>    
</body>
<!-- InstanceEnd --></html>