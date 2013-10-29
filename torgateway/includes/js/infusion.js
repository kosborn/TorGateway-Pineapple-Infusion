function torgateway_take_action(action_value,appendTo) {
	appendTo = appendTo || 'tor_result';
	$.get("/components/infusions/torgateway/includes/actions.php", 'action='+action_value, 
		function(data){
			$( "."+appendTo ).prepend( data+"\n" );
			$( ".mini_tor_result" ).prepend( data+"\n" );}
		)
}

function getTorLargeFrame(){
	$.ajax({
		url:'/components/infusions/torgateway/includes/large_frame.php'}).done(
		function(data){
			$( ".largeFrameUpdate" ).html(data);
		}
	)
}

function getTorSmallFrame(){
	$.ajax({
		url:'/components/infusions/torgateway/includes/small_frame.php'}).done(
		function(data){
			$( ".smallFrameUpdate" ).html(data)
		}
	)
}
