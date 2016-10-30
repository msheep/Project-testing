<?php
	include 'common.php';
	//delete the original cookie
	if(!empty($_COOKIE)){
		foreach($_COOKIE as $name=>$val){
			setcookie($name, $val, time()-3600);
		}
	}
	$result = mysql_query("SELECT cookie FROM response order by update_time desc limit 1");
	while($row = mysql_fetch_array($result)){
	  	$cookies = json_decode($row['cookie']);
	  	foreach ($cookies as $key => $values) {
	  		switch (count($values)) {
	  			case 2:
	  				setcookie($values[0], $values[1]);
	  				break;
	  			case 3:
	  				setcookie($values[0], $values[1], $values[2]);
	  				break;
	  			case 4:
	  				setcookie($values[0], $values[1], $values[2], $values[3]);
	  				break;
	  			case 5:
	  				setcookie($values[0], $values[1], $values[2], $values[3], $values[4]);
	  				break;
	  			case 6:
	  				setcookie($values[0], $values[1], $values[2], $values[3], $values[4], $values[5]);
	  				break;
	  			default:
	  				break;
	  		}
	  	}
  	}

  	$client = $_SERVER['REMOTE_ADDR'];
  	$header = json_encode(getallheaders());

  	$sql = "INSERT INTO request(server, header, update_time) VALUES ('".$client."', '".$header."', '".date("Y-m-d H:i:s", time())."')";

  	mysql_query($sql);
?>
<html lang="en">
	<head>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
		<!-- Optional theme -->
		<link rel="stylesheet" href="/bootstrap/css/bootstrap-theme.min.css" crossorigin="anonymous">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/bootstrap/js/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
		<script src="/bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>
	</head>

	<body>
		<div class="container">
			<h1 class="page-header"> Project Testing </h1>

			<div class="row">
				<div class="col-md-2"><code>Current Server: </code></div>
			  	<div class="col-md-10">
			  		<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
			  	</div>
			</div>

			<p>&nbsp;</p>
			<div class="row">
				<div class="col-md-2">
					<code for="set-cookie">Current Cookie Settings:</code>
				</div>
			  	<div class="col-md-8" id="cookie-settings">
			  		<?php if(!empty($cookies )){
			  			foreach($cookies as $val){?>
			  			<p><?php echo implode(";", $val);?></p>
			  		<?php }
			  		}?>
			  	</div>
			</div>

			<p>&nbsp;</p>

			<div class="row">
				<div class="col-md-2">
					<code for="set-cookie">Set Cookie:</code>
				</div>
			  	<div class="col-md-8">
			  		<form id="cookies">
						<div class="form-inline">
							<input type="text" class="form-control" name="set-cookie[]" style="width: 80%" placeholder="name; value; [expire; path; domain; secure]">
				  		</div>
			  		</form>
			  	</div>
			</div>

			<div class="row">
				<div class="col-md-2"> </div>
			  	<div class="col-md-8">
			  		<button onclick="addCookie()" type="button" class="btn btn-default">Add</button>&nbsp;
			  		<button onclick="deleteCookie()" type="button" class="btn btn-default">Clear</button>&nbsp;
			  		<button onclick="submitCookie()" type="submit" class="btn btn-default">Submit</button>&nbsp;
			  		<span id="error" class="text-danger"></span>
			  	</div>
			</div>

			<p>&nbsp;</p>

			<div class="row">
				<div class="col-md-2"><code>Requested Header:</code></div>
			  	<div class="col-md-10" id="headers">
			  		Loading...
			  	</div>
			</div>

		</div>

		<script type="text/javascript">
			$(function(){
				$('input').val('');
				setInterval(function(){
					requestHeaders();
				}, 10000);
			});

			function requestHeaders(){
				$.ajax({
		            url: "test.php",
		            type: "POST",
		            dataType: "json",
		            success: function (data) {
		            	var str = '';
		            	headers = eval('(' + data['header'] + ')');
		            	$.each(headers, function(name, value){
		            		str += '<p>'+name+': '+value+'</p>'
		            	})
		            	$("#headers").html(str);
		            },
		        });
			}

			function addCookie(){
				$("#cookies").append('<div class="form-inline" style="margin-top:10px"><input type="text" class="form-control" name="set-cookie[]" style="width: 80%;" placeholder="name, value, [expire, path, domain, secure]"> <button type="button" class="remove btn btn-danger" onclick="remove(this)"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div>');
				refreshForm();
			}

			function submitCookie(){
				var cookies = [];
				$('input[name="set-cookie[]"]').each(function(){
					var cookie = $.trim($(this).val());
					if(cookie.length > 0){
						cookies.push(cookie);
					}
				})

				$.ajax({
		            url: "cookie.php",
		            type: "POST",
		            data: {cookie: cookies},
		            dataType: "json",
		            success: function (data) {
				  		$('#error').html(data['msg']);
				  		if(data['status'] != 'error'){
				  			str = '';
				  			$.each(data['cookies'], function(name, value){
			            		str += '<p>'+value.join(";")+'</p>'
			            	})
				  			$("#cookie-settings").html(str);
				  		}
				  		
		            },
		        });
			}

			function deleteCookie(){
				var cookies = [];
				$.ajax({
		            url: "deletecookie.php",
		            type: "POST",
		            dataType: "json",
		            success: function (data) {
				  		$('#error').html(data['msg']);
		            },
		        });
			}

			function remove(obj){
				$(obj).parent('.form-inline').remove();
				refreshForm();
			}

			function refreshForm(){
				$('input[name="set-cookie[]"]:first').parent().css('margin-top','0px');
				if($('input[name="set-cookie[]"]').length < 2){
					$('input[name="set-cookie[]"]').siblings('button').remove();
				}else{
					if($('input[name="set-cookie[]"]:first').siblings('button').length == 0){
						$('input[name="set-cookie[]"]:first').after(' <button type="button" class="remove btn btn-danger" onclick="remove(this)"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div>');
					}
				}
			}
		</script>

	</body>

</html>