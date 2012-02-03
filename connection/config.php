<?php
session_start();
$mysql_link = mysql_connect("localhost", "root", ""); 
mysql_select_db("idsr") or die("Could not select database");
?>
