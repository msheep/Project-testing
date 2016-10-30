<?php
	include 'common.php';
	$result = array();
	$sql = "DELETE from response where id > 0";

	if (mysql_query($sql) === TRUE) {
	    $result['status'] = 'success';
	    $result['msg'] = "Clear successfully";
	    $result['cookies'] = $cookies;
	} else {
		$result['status'] = 'error';
	    $result['msg'] = mysql_error();
	}
	print_r(json_encode($result));
?>