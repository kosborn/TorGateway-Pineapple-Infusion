<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>
<?php

global $directory, $rel_dir;

require_once('/pineapple/components/infusions/torgateway/functions.php');


if(!is_tor_installed()){
	echo "<h3>Before continuing, you need to install Tor.</h3>";
	echo "<button onclick='torgateway_take_action(\"install_tor\",\"tor_result\")'>Install Tor</button>";
} else {
	if(tor_proc_status() == 3){
		echo "<h3 style=color:red;>Tor is not currently running.</h3>";
		echo "<button onclick='torgateway_take_action(\"toggle_tor\",\"tor_result\")'>Start Tor</button>";
	} else if(tor_proc_status() == 2){
		echo "<h3 style=color:blue;>Tor is coming online. Watch the logs below for bootstrap status</h3>";
	} else {
		echo "<h3 style=color:green;>Tor is running.</h3>";
		echo "<button onclick='torgateway_take_action(\"toggle_tor\",\"tor_result\")'>Stop Tor</button>";
	}
}
