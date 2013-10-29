<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>
<?php

global $directory, $rel_dir;

require_once('/pineapple/components/infusions/torgateway/functions.php');

$status = tor_proc_status();
if($status == 1){
	$msg = '<span style=color:green;>Running</span>';
	$cStatus = tor_circuit_status();
} else if($status == 3){ 
	$msg = '<span style=color:red;>Stopped</span>';
} else if ($status == 2){
	$msg = '<span style=color:blue;>Tor Coming Up</span>';
	$cStatus = tor_circuit_status();
}




echo "<div>Tor status: ".$msg."</div>";

if($status == 3){
	echo "<button onclick=torgateway_take_action('toggle_tor','mini_tor_result')>Start Tor</button>";
} else{ 
	echo "<div>Circuit status: ".(($cStatus) ? '<span style=color:green;>Connected</span>' : '<span style=color:blue;>Connecting</span>')."</div>";
	echo "<a href=javascript:torgateway_take_action('tor_new_identity','mini_tor_result')>New identity</a>";
}
