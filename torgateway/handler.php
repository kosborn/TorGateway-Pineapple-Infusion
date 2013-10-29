<?php
#########################
#    User Variables     #
#   Please edit these   #
#########################
$name = 'Tor Gateway';  #Name of tile
$updatable = 'false';  #Should this tile auto-refresh
$version = '1.1';

#########################
#     Handler Code      #
# No need to edit below #
#########################

#Set up handler functions and data
include('/pineapple/includes/api/tile_functions.php');
$directory = realpath(dirname(__FILE__)).'/';
$rel_dir = str_replace('/pineapple', '', $directory);
include('/pineapple/includes/api/handler_helper.php');

?>