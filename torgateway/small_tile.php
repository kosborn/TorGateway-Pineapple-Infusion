<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>
<?php
include_once("{$directory}/functions.php");
if(!is_tor_installed()){
	echo "<h3>Tor is not installed!<br/>Use this tile to install it first.</h3>";
	die();
}
?>
<script src=/components/infusions/torgateway/includes/js/infusion.js></script>
<script>
// To fix setting multiple intervals
if (typeof SFintervalID == 'undefined'){
	SFintervalID = setInterval(getTorSmallFrame,3000);
}
</script>


<span class="smallFrameUpdate"><?php include_once('/pineapple/components/infusions/torgateway/includes/small_frame.php');?></span>

<fieldset>
<pre class="mini_tor_result"></pre></fieldset>
