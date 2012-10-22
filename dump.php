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
	
	$output = array(
		"hashes" => array(
			"multigraph" => "miner_hashes",
			"graph_title" => "GPU Hash Rate",
			"graph_order" => "recv send",
			//graph_args --base 1000
			"graph_vlabel" => "MegaHashes per \${graph_period}",
			"graph_category" => "sensors",
			"graph_info" => "Statistics of GPU Hash Rate"
		), "cooling" => array (
			"multigraph" => "miner_cooling",
			"graph_title" => "GPU Cooling stats",
			"graph_order" => "recv send",
			//graph_args --base 1000
			"graph_vlabel" => "Temp(C) and Fan(%) per \${graph_period}",
			"graph_category" => "sensors",
			"graph_info" => "Statistics of GPU cooling"
		), "int" => array (
			"multigraph" => "miner_int",
			"graph_title" => "GPU Intensity",
			"graph_order" => "recv send",
			//graph_args --base 1000
			"graph_vlabel" => "Intensity at \${graph_period}",
			"graph_category" => "sensors",
			"graph_info" => "Statistics of GPU intensity"
		)
	);

	//Create fields for each graph
	$response = queryMiner("gpucount");
	$numCards = $response["GPUS"][0]["Count"];
	for($i = 0; $i < $numCards; $i++) {
		$output["hashes"]["gpu{$i}_5s.label"] = "GPU $i 5s average MegaHashes/sec";
		$output["hashes"]["gpu{$i}_avg.label"] = "GPU $i All time average MegaHashes/sec";
		$output["cooling"]["gpu{$i}_temp.label"] = "GPU $i Temp (Celcius)";
		$output["cooling"]["gpu{$i}_fan.label"] = "GPU $i Fan Percent";
		$output["cooling"]["gpu{$i}_fan.min"] = "0";
		$output["cooling"]["gpu{$i}_fan.max"] = "100";
		$output["int"]["gpu{$i}_int.label"] = "GPU $i intensity";
		$output["int"]["gpu{$i}_int.min"] = "0";
		$output["int"]["gpu{$i}_int.max"] = "14";
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
$output["hashes"]["multigraph"] = "miner_hashes";
$output["cooling"]["multigraph"] = "miner_cooling";
$output["int"]["multigraph"] = "miner_int";
foreach($response["DEVS"] as $value) {
	$id = $value["ID"];
	$output["hashes"]["gpu{$id}_5s.value"] = $value["MHS 5s"];
	$output["hashes"]["gpu{$id}_avg.value"] = $value["MHS av"];
	$output["cooling"]["gpu{$id}_temp.value"] = $value["Temperature"];
	$output["cooling"]["gpu{$id}_fan.value"] = $value["Fan Percent"];
	$output["int"]["gpu{$id}_int.value"] = $value["Intensity"];
}
output($output);
