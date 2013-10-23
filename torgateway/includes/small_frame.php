<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>
<?php

global $directory, $rel_dir;

require_once('/pineapple/components/infusions/torgateway/functions.php');

$status = tor_proc_status();


echo "<div>Tor status: ".($status ? '<span style=color:green;>Running</span>' : '<span style=color:red;>Stopped</span>')."</div>";
if(!$status) echo "<button onclick=torgateway_take_action('toggle_tor','mini_tor_result')>Start Tor</button>";
if($status){
	echo "<div>Circuit status: ".(tor_circuit_status() ? '<span style=color:green;>Connected</span>' : '<span style=color:blue;>Connecting</span>')."</div>";
	echo "<a href=javascript:torgateway_take_action('tor_new_identity','mini_tor_result')>New identity</a>";
}
