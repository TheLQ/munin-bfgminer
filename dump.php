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
	exit 0;
}