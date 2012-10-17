#!/usr/bin/php
<?php
include 'dump.utils.php';

//First, figure out what config mode were in
if(isset($argv[1]) && strtolower($argv[1]) == "config") {
	/*
	multigraph if_bytes
	graph_title $host interface traffic
	graph_order recv send
	graph_args --base 1000
	graph_vlabel bits in (-) / out (+) per \${graph_period}
	graph_category network
	graph_info This graph shows the total traffic for $host
	*/
	$response = queryMiner("gpucount");
	$numCards = $response["GPUS"][0]["Count"];
	
	$output = array();
	for($i = 0; $i <= $numCards; $i++) {
		$output[$i] = array(
			"multigraph" => "miner_gpu" . $i,
			"graph_title" => "GPU $i Mining Stats",
			"graph_order" => "recv send",
			//graph_args --base 1000
			"graph_vlabel" => "MegaHashes per \${graph_period}",
			"graph_category" => "sensors",
			"graph_info" => "Statistics of GPU $i"
		);
	}
	output($output);
	exit(0);
}

//No config line, get miner data
$response = queryMiner("devs");

/*
[GPU] => 0
[Name] => OCL
[ID] => 0
[Enabled] => Y
[Status] => Alive
[Temperature] => 69
[MHS av] => 157.24
[MHS 5s] => 156.22
[Accepted] => 184
[Rejected] => 0
[Hardware Errors] => 0
[Utility] => 2.08
[Last Share Pool] => 0
[Last Share Time] => 1350445775
[Total MH] => 834096.0707
[Diff1 Work] => 184
[Difficulty Accepted] => 184
[Difficulty Rejected] => 0
[Last Share Difficulty] => 1
[Fan Speed] => -1
[Fan Percent] => 78
[GPU Clock] => 600
[Memory Clock] => 1200
[GPU Voltage] => 1
[GPU Activity] => 0
[Powertune] => 0
[Intensity] => 9
*/
$output = array();
foreach($response["DEVS"] as $value) {
	$id = $value["ID"];
	$output[$id] = array(
		"multigraph" => "miner_gpu" . $i,
		"hash5s.value" => $value["MHS 5s"],
		"hashavg.value" => $value["MHS av"],
		"intensity.value" => $value["Intensity"],
		"fan.value" => $value["Fan Percent"],
		"temp.value" => $value["Temperature"],
		"utility" => $value["Utility"],
	);
}
output($output);
