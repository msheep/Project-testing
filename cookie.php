<?php
	include 'common.php';
	$result = array();
	if(isset($_POST['cookie']) && !empty($_POST['cookie'])){
		$cookies = array();
		foreach ($_POST['cookie'] as $key => $value) {
			$cookie = split(';', $value);
			if(count($cookie) > 1){
				foreach($cookie as $k=>$v){
					$cookie[$k] = trim($v);
					if(empty($cookie[1])){
						unset($cookie);
					}else{
						if($k == 2){
							$cookie[$k] = strtotime(trim($v));
						}
					}
				}
			}
			if(count($cookie) > 1){
				array_push($cookies, $cookie);
			}
		}

		if(count($cookies) == 0){
			$result['status'] = 'error';
		    $result['msg'] = "Have no valid cookie";
		}else{
			$sql = "INSERT INTO response (cookie, update_time) VALUES ('".json_encode($cookies)."', '".date("Y-m-d H:i:s", time())."')";

			if (mysql_query($sql) === TRUE) {
			    $result['status'] = 'success';
			    $result['msg'] = "New record created successfully";
			    $result['cookies'] = $cookies;
			} else {
				$result['status'] = 'error';
			    $result['msg'] = mysql_error();
			}
		}
	}else{
		$result['status'] = 'error';
	    $result['msg'] = "Have no valid cookie";
	}
	print_r(json_encode($result));
	// return json_encode($result);
?>