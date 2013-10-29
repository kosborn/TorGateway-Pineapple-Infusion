<?php include_once('/pineapple/includes/api/tile_functions.php'); ?>
<?php

// Check to see if the tor package is installed

function is_tor_installed(){
	$isInstalled = shell_exec('opkg list-installed tor');
	if($isInstalled){
		# If installed, returns 'tor - $VERSION'
		return true;
	} else {
		# If not, returns NULL
		return false;
	}
}


// Install tor

function install_tor(){
	# TODO: probably a better way to update, install, and check installed
	# TODO: force installed to external SD card?
	
	# Check to see if tor is installed first (don't waste our time otherwise)
	if(is_tor_installed()) { echo 'Tor is already installed!'; return false; }
	
	# Check to see if we are online to update and download tor
	if(online()){
		exec('opkg update') && exec('opkg install tor');
		exec('cp /etc/tor/torrc /etc/tor/torrc.bak_'.time());
		exec('cp '.$directory.'/torrc.pineapple /etc/tor/torrc');
		exec('/etc/init.d/tor stop');
		echo 'Tor has been installed';
		return true;
	} else {
		echo "You must be online to install tor";
		return false;
	}

}


// Remove tor

function remove_tor(){
	echo shell_exec('opkg remove tor');
	return true;
}


// Check to see if tor process is running
// DOES NOT CHECK TOR NETWORK STATUS

function tor_proc_status(){
	if(exec('pgrep /usr/sbin/tor')){
		$value = tor_control('GETINFO status/circuit-established');
		if($value != ''){
			return 1; // IF UP
		} else {
			return 2; // IF COMING UP
		}
	} else {
		return 3; // IF DOWN
	}
}


// Toggle tor on a supplied interface (default br-lan)

function toggle_tor($interface='br-lan'){
	# Make sure a tor process is not already running
	if(tor_proc_status()!=3){
		$action = 'D';
		echo shell_exec('/etc/init.d/tor stop');
		echo "Tor sopped.";
	} else {
		$action = 'A';
		echo shell_exec('/etc/init.d/tor start');
	}
	tor_apply_iptables($action,$interface);
	return true;
	
}

function tor_apply_iptables($action,$interface){
	# TODO: reivew these rules. They are from https://trac.torproject.org/projects/tor/wiki/doc/OpenWRT
	$torDNS = "iptables -t nat -{$action} PREROUTING -i {$interface} -p udp --dport 53 -j REDIRECT --to-ports 9053";
	$torTCP = "iptables -t nat -{$action} PREROUTING -i {$interface} ! -d Pineapple.lan  -p tcp ! --dport 53 --syn -j REDIRECT --to-ports 9040";

	# TODO: Add a check to verify the commands ran sucesfully
	exec($torDNS);
	exec($torTCP);
	# TODO: Instead of 'deleting' the rules, maybe take a snapshot with iptables-save and then restore it on tor-off?
}


function tor_circuit_status(){
	# Check to see if Tor is even running
	if(tor_proc_status() != 3){
		$value = tor_control('GETINFO status/circuit-established');
		if(substr($value,0,32) == "250-status/circuit-established=1"){
			return true; // CIRCUIT UP
		} else {
			return false; // NOT UP
	
		}
	}
}	


function tor_new_identity(){

	# Check to see if tor is even running
	if(tor_proc_status()==FALSE) die('Tor is not running');
	
	$value = tor_control('SIGNAL NEWNYM');
	if(substr($value,0,6) == "250 OK"){
		echo $value;
	} else {
		echo $value;
	}
	
}


// A very simple interface to control tor
// Seriously, this is probably really bad. Don't use it elsewhere.

function tor_control($command){
	$socket=0;
	
	if($socket==1){
		# Check to see if the socket can connect
		if(!($socket = fsockopen('127.0.0.1',9051,$errno,$errstr,2))) die("Control port connect failure, is tor running? Error: {$errstr}");
		stream_set_timeout($socket,1);
	
		# Authenticate
		fwrite($socket,"AUTHENTICATE \"fruitsalad\"\r\n");
		# ...Or fail	
		if(substr(fread($socket,512),0,6) != "250 OK") die("Couldn't authenticate, something is definitely wrong.");
		
		# Send command
		fwrite($socket,"{$command}\r\n");
		$value = explode("\n",fread($socket,512));
		fclose($socket);
	
		# Dumb way of doing this, disregard 
		array_pop($value);
		return implode("\n",$value);
	} else {
		# Sleep 1 is  is here to prevent sh from overwriting the file immediately when redirecting in /tmp/tor_control
		# Otherwise it causes silly raise conditions in the UI
		$command = "echo \"sleep 2; echo -e 'AUTHENTICATE 667275697473616c6164\\n".$command."'|nc localhost 9051 > /tmp/tor_control\"";
		if(shell_exec('pgrep -lf "nc localhost 9051"|grep -v sh') == NULL && shell_exec('pgrep -lf "sleep 2"|grep -v sh') == NULL ){
			exec($command."|at now");
		}
		if(file_exists('/tmp/tor_control')){
			$file = "/tmp/tor_control";
			$data = file($file);
			$line = $data[count($data)-2];
			return $line;
		}
	}
}
