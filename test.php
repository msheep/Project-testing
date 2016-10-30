<?php
	include 'common.php';
	$result = mysql_query("SELECT * FROM request order by update_time desc limit 1");
	while($row = mysql_fetch_array($result)){
	  	print_r(json_encode($row));
  	}
?>