<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>

<script src=/components/infusions/torgateway/includes/js/infusion.js></script>
<script>
// To fix setting up multiple intervals
if (typeof LFintervalID == 'undefined'){
	LFintervalID = setInterval(getTorLargeFrame,3000);
}
</script>


<span class="largeFrameUpdate"><?php include_once('/pineapple/components/infusions/torgateway/includes/large_frame.php');?></span>

<fieldset>
<legend>TorGateway Output</legend>
<pre class='tor_result'>
</pre></fieldset>


