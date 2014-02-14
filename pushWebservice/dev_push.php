<?php

// Put your device token here (without spaces):
//$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
//ddfdd50a2721f5bd7e348e3eaed158ea5b8950e8eed6c00d3a10402d7017949d
//70cb878280cb34c88229bb5a044d824bf349c1166bb4fbbe99038a81f5afe183
//$deviceToken = 'b1c2ca08c1ea436621f622cf221b79376d93d92643230f2a155d4c8e86b88ba6';



// Put your alert message here:
//$message = 'My first push notification!';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$message = $_POST['msg'];
	} else {
		$message = $_GET['msg'];
	}
////////////////////////////////////////////////////////////////////////////////



$file_name = "client_tokenid.txt";
$file_handle = fopen($file_name, "r");
if ($file_handle) {
    while (($line = fgets($file_handle)) !== false) {

        $deviceToken = str_replace("\n", "", $line);
        $deviceToken = str_replace("\r", "", $deviceToken);

		if(strlen($deviceToken) < 100)
		{	
			applePush($deviceToken,$message);
		}
		else{
			googlePush($deviceToken, $message);
		}
    }

} else {

}
fclose($file_handle);



function googlePush($DeviceID, $Message)
{
	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	
	$DeviceID = array($DeviceID);
	$Message = array("MSG" => $Message, "MSGCNT" => $Message, "message" => $Message);
	
	$fields = array(
		'registration_ids' => $DeviceID,
		'data' => $Message,
	);

	$headers = array(
		'Authorization: key=' . 'AIzaSyB5IVRxVn6XbiPV6GgRXxST60Gxbj2GvCY',
		'Content-Type: application/json'
	);
	// Open connection
	$ch = curl_init();
	
	// Set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	
	// Execute post
	$result = curl_exec($ch);
	if ($result === FALSE) {
		echo 'Curl failed: '.curl_error($ch);
	}else{echo $result;}
	// Close connection
	curl_close($ch);
	//echo $result;	

echo "<BR><BR>";

	return;
}

function applePush($deviceToken,$msg)
{
	// Put your private key's passphrase here:
	$passphrase = '12345678';
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

	// Open a connection to the APNS server
	$fp = stream_socket_client(
	//    'ssl://gateway.push.apple.com:2195', $err,
		'ssl://gateway.sandbox.push.apple.com:2195', $err,
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

	if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);

	echo 'Connected to APNS' . PHP_EOL;
	// Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default',
            'badge' => 1
        );
 
	// Encode the payload as JSON
        $payload = json_encode($body);

	// Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

	// Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
            echo "<BR><BR>$message - Message not delivered" . PHP_EOL;
        else
            echo "<BR><BR>$message - Message not delivered"  . PHP_EOL;

		// Close the connection to the server
	fclose($fp);
	return;
}
?>