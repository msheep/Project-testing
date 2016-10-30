<?php
	date_default_timezone_set('Australia/Melbourne');
	$con = mysql_connect("localhost","root","2009zhyj");
	if (!$con){
  		die('Could not connect: ' . mysql_error());
  	}
  	mysql_select_db("project", $con);

?>