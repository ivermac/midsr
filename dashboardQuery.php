<?php 
session_start();
include 'connection/config.php'; 

$year=$_POST['year'];
$epiweek=$_POST['epiweek'];
$province=$_POST['province'];
$_SESSION['province']=$province;

function getDistricts($sqlReportedDistricts,$sqlAllDisticts,$province){
	$output="";
	$rs=mysql_query($sqlReportedDistricts);
	if($value=mysql_fetch_array($rs)){
		$reported=$value['district'];
		$output=$reported;
		$output.="_";
	}
	$rs=mysql_query($sqlAllDisticts);
	if($value=mysql_fetch_array($rs)){
		$all=$value['districts'];
		$output.=$all;
		$output.="_";
	}
	$percentage=round(($reported/$all)*100,2);
	$output.=$percentage;
	$output.="_";
	if($province==0){
		$output.=$province;
	}
	else if($province>0){
		$rs=mysql_query("SELECT name FROM provinces WHERE id=$province");
		if($value=mysql_fetch_array($rs)){
			$province_name=$value['name'];
		}
		$output.=$province_name;
	}
	//$output.=getTotals();
	return $output;
}
function getTotals($epiweek,$year,$province){
	$output="";
	if($province==0){
		$sql="SELECT SUM( lmcase + lfcase + gmcase + gfcase ) AS cases, SUM( lmdeath + lfdeath + gmdeath + gfdeath ) AS deaths, diseases.name AS disease FROM  `surveillance`,diseases 
		  WHERE epiweek =$epiweek AND DATE =$year AND surveillance.disease=diseases.id GROUP BY diseases.id";
	}
	else{
		/*$sql="SELECT SUM( lmcase + lfcase + gmcase + gfcase ) AS cases, SUM( lmdeath + lfdeath + gmdeath + gfdeath ) AS deaths, disease FROM  `surveillance` 
			  WHERE epiweek =$epiweek AND DATE =$year AND district IN (SELECT ID FROM districts WHERE province =$province) GROUP BY disease";*/
		$sql="SELECT SUM( lmcase + lfcase + gmcase + gfcase ) AS cases, SUM( lmdeath + lfdeath + gmdeath + gfdeath ) AS deaths, diseases.name AS disease FROM  `surveillance`,diseases 
				WHERE epiweek =$epiweek AND DATE =$year AND surveillance.disease=diseases.id AND district IN (SELECT ID FROM districts WHERE province =$province) GROUP BY diseases.id";	
	}
	$rs=mysql_query($sql);	
	while($value=mysql_fetch_array($rs)){
		$output.=$value['cases']."*".$value['deaths']."*".$value['disease'];
		$output.="#";	
	}
	return $output;	
}
if($province==0){
	$reportedDistricts="SELECT COUNT(DISTINCT(district)) AS district FROM `surveillance` WHERE epiweek=$epiweek and date=$year ";
	$allDistricts="SELECT COUNT( id ) AS districts  FROM  `districts` WHERE flag=1";
	echo getDistricts($reportedDistricts,$allDistricts,$province)."_".getTotals($epiweek,$year,$province);
}
else{
	$reportedDistricts="SELECT COUNT(DISTINCT(district)) AS district FROM `surveillance` WHERE epiweek=$epiweek and date=$year and district IN (select id from districts where province=$province)";
	$allDistricts="SELECT COUNT( id ) AS districts  FROM  `districts` WHERE province =$province";	
	echo getDistricts($reportedDistricts,$allDistricts,$province)."_".getTotals($epiweek,$year,$province);
}

?>