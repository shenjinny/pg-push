<?php

// Put your device token here (without spaces):
//$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
//ddfdd50a2721f5bd7e348e3eaed158ea5b8950e8eed6c00d3a10402d7017949d
//70cb878280cb34c88229bb5a044d824bf349c1166bb4fbbe99038a81f5afe183
//$deviceToken = 'b1c2ca08c1ea436621f622cf221b79376d93d92643230f2a155d4c8e86b88ba6';

// Put your private key's passphrase here:
$passphrase = '12345678';

// Put your alert message here:
//$message = 'My first push notification!';
$message = $_GET['msg'];

////////////////////////////////////////////////////////////////////////////////

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


$file_name = "client_tokenid.txt";
$file_handle = fopen($file_name, "r");
if ($file_handle) {
    while (($line = fgets($file_handle)) !== false) {

        $deviceToken = str_replace("\n", "", $line);
        $deviceToken = str_replace("\r", "", $deviceToken);
        //echo ($deviceToken.":".strlen($deviceToken)."<br>");


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
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;

    }

} else {

}
fclose($file_handle);

// Close the connection to the server
fclose($fp);


?>