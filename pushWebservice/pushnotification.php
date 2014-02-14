<?php
function sendpushmsg($userid, $msg, $code){
	$result = mysql_query("select * from device_user_tb where userid=$userid");
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_array($result)){
			if($row["status"] == "1"){
				$DeviceID = $row["deviceid"];
				$Message = $msg;
				$id = $code;
				if($row["type"] == "ios"){					
					applePush($DeviceID,$Message,$id);
				}
				if($row["type"] == "android"){
					googlePush($DeviceID,$Message,$id);
				}
			}
		}
		
	}
}

function googlePush($DeviceID, $Message , $id)
{
	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	
	$DeviceID = array($DeviceID);
	$Message = array("MSG" => $Message, "MSGCNT" => $Message, "message" => $Message, "id" => $id);
	
	$fields = array(
		'registration_ids' => $DeviceID,
		'data' => $Message,
	);
	
	$headers = array(
		'Authorization: key=' . 'AIzaSyBoQ_K2-OWHyTMUNJ8iCWRcGxddCHG4wco',
		'Content-Type: application/json'
	);
	// Open connection
	$ch = curl_init();
	
	// Set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	// Disabling SSL Certificate support temporarly
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	
	// Execute post
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('Curl failed: ' . curl_error($ch));
	}
	// Close connection
	curl_close($ch);
	//echo $result;	
}

function applePush($DeviceID,$Message,$id)
{
	$deviceToken = $DeviceID; // deviceTokenID
	
	// For development
	$apnsHost = 'gateway.sandbox.push.apple.com';
	$apnsCert = './ck.pem';
	
	$apnsPort = 2195;
	
	$payload = array('aps' => array('alert' => $Message, 'badge' => 0, 'sound' => 'default', 'id' => $id));
	$payload = json_encode($payload);
		
	$streamContext = stream_context_create();
	//$streamContext = stream_context_create(array('ssl' => array('local_cert' => $apnsCert)));
	stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
	

	
	$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $streamContext);
	
	if($apns)
	{
	  $apnsMessage = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $deviceToken)).chr(0).chr(strlen($payload)).$payload;
	  fwrite($apns, $apnsMessage);
	  fclose($apns);
	}else{
		//echo 'Could not connect';
	}
}
?>