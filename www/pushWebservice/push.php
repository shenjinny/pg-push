<?php

// Put your device token here (without spaces):
//$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
$deviceToken = '6e5dade66592d5530796083e0109ff00465b984206d968cb95759174ce522f2d';

// Put your private key's passphrase here:
$passphrase = '12345678';

// Put your alert message here:
$message = "Dashizle Push test!";
//$message = $_GET['msg'];

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
    'ssl://gateway.sandbox.push.apple.com:2195', $err,
    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
    exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;


//$file_name = "client_tokenid.txt";
//$file_handle = fopen($file_name, "w");
//if ($file_handle) {
//    while (($line = fgets($file_handle)) !== false) {
//
//        $deviceToken = str_replace("\n", "", $line);
//        $deviceToken = str_replace("\r", "", $deviceToken);
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

//    }
//
//} else {
//
//}
//fclose($file_handle);

// Close the connection to the server
fclose($fp);





?>