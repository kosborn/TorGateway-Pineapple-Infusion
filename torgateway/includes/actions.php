<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>
<?php


global $directory, $rel_dir;

require_once('/pineapple/components/infusions/torgateway/functions.php');


if($_GET['action']){
	switch($_GET['action']) {
		case 'install_tor':
			install_tor();
			break;
		case 'remove_tor':
			remove_tor();
			break;
		case 'toggle_tor':
			toggle_tor();
			break;
		case 'tor_new_identity':
			tor_new_identity();
			break;
		case 'test':
			echo "TEST";
			break;
	}
}


