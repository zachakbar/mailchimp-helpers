<?php

/*
	This tool is intended for dev/testing purposes.
	
	Pretty simple, just upload and add in a query var to the URL.
	example: http://yoursite.com/mc-multi-tool.php?email=your@email.com&method=GET
	
	Reference link for the API calls
	http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
	
	**NOTE**
	Make sure you add your merge fields in.
	
*/
	
mc_checklist($_GET['email'], 'API_KEY', 'LIST_ID', 'SERVER', $_GET['method']);

function mc_checklist($email, $apikey, $listid, $server, $method) {
	$userid = md5($email);
	$auth = base64_encode( 'user:'. $apikey );
	$data = array(
		'apikey'						=> $apikey,
		'email_address'			=> $email,
		'status'						=> 'subscribed',
		'merge_fields'				=> array(
			'FNAME'					=> '',
			'LNAME'					=> '',
		),
	);
	$json_data = json_encode($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$listid.'/members/' . $userid);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '. $auth));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	$result = curl_exec($ch);
	
	$json = json_decode($result);
	echo 'STATUS:<br>';
	echo $json->{'status'};
	echo '<br>';
	echo 'RESULT:<br>';
	var_dump($result);
}