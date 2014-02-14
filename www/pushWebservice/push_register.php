<?php
/**
 * Created by PhpStorm.
 * User: threek
 * Date: 1/19/14
 * Time: 2:23 AM
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$token_id = $_POST['token_id'];
	} else {
		$token_id = $_GET['token_id'];
	}

if (strlen($token_id) == 0) {
	return;
}

$file_name = "client_tokenid.txt";


$file_handle = fopen($file_name, "r");
if ($file_handle) {

        while (($line = fgets($file_handle)) !== false) {
//            echo ("a");
//            echo ($line.":".strlen($line)."<br>");
            $actual_token_id = str_replace("\n", "", $line);
            $actual_token_id = str_replace("\r", "", $actual_token_id);
            if ($actual_token_id == $token_id) {
//                echo ("exist");
                fclose($file_handle);
				//echo '{"ret":' . json_encode("token id is already exist/".$token_id."/".$actual_token_id) . '}';
                return;
            }
        }
//    echo("ok");
    fclose($file_handle);
} else {
	//echo '{"ret":' . json_encode("failed") . '}';
    return;
}

$file_handle = fopen($file_name, "a");
if ($file_handle) {
    $ret = fwrite($file_handle, $token_id."\r\n");
	if($ret == false){
		//echo '{"ret":' . json_encode("file write failed.") . '}';
		return;
	}
}
fclose($file_handle);

?>