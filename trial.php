<?php 
include 'connection/config.php'; 
function getTotals(){
	$output="";
	$sql="SELECT SUM( lmcase + lfcase + gmcase + gfcase ) AS cases, SUM( lmdeath + lfdeath + gmdeath + gfdeath ) AS deaths, disease FROM  `surveillance` 
			  WHERE epiweek =18 AND DATE =2011 AND district IN (SELECT ID FROM districts WHERE province =1) GROUP BY disease";
	$rs=mysql_query($sql);	
	while($value=mysql_fetch_array($rs)){
		$output.=$value['cases']."*".$value['deaths']."*".$value['disease'];
		$output.="#";	
	}
	return $output;	
}
echo getTotals();
?>