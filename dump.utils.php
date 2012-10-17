<?php
function queryMiner($cmd) {
	//Get socket
	$handle = stream_socket_client("tcp://10.0.0.30:4028", $errno, $errorMessage);
	if ($handle === false)
		throw new UnexpectedValueException("Failed to connect: $errorMessage");

	//Send our command
	if(fwrite($handle, json_encode(array("command" => $cmd), true)) === FALSE)
		throw new Exception("Cannot write to stream");

	//Get the response
	$contents = trim(stream_get_contents($handle));
	fclose($handle);

	//JSON parsing and error handling
	$response = json_decode($contents, true);
	switch (json_last_error()) {
		case JSON_ERROR_DEPTH:
			throw new Exception(' - Maximum stack depth exceeded');
		break;
		case JSON_ERROR_STATE_MISMATCH:
			throw new Exception(' - Underflow or the modes mismatch');
		break;
		case JSON_ERROR_CTRL_CHAR:
			throw new Exception(' - Unexpected control character found');
		break;
		case JSON_ERROR_SYNTAX:
			throw new Exception(' - Syntax error, malformed JSON');
		break;
		case JSON_ERROR_UTF8:
			throw new Exception(' - Malformed UTF-8 characters, possibly incorrectly encoded');
		break;
	}
	
	//No exception yet, return
	return $response;
}

function output($outer) {
	foreach($outer as $inner) {
		foreach($inner as $key => $value)
			echo $key . " " . $value . PHP_EOL;
		echo PHP_EOL;
	}
}

function exception_handler($exception) {
	echo "Uncaught exception: " , $exception->getMessage(), "\n";
	exit -1;
}
set_exception_handler('exception_handler');