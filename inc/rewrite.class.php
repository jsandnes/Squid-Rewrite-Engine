<?PHP
require_once("config.php");

class rewriter {

	public function __construct($args) {

		//Split the data from squid into an array.

		$this->proxyData = preg_split('/ /', unserialize($args));

		//Run the rewriter to see if the url should be treated.

		if(LOG_URLS)
			$this->log_url($this->proxyData);
	
		//Check if we want to redirect, or block this URL.

		if(!$this->rewrite($this->proxyData)) {
			print $this->proxyData[0]."\n";

			//Since its not redirected, check if we want to log the search query if this is a search engine.

			$this->log_search_engine($this->proxyData);

		}
		
		if(DEBUG) {
			$this->log(unserialize($args));
		}

	}

	private function rewrite($data) {
				
		//Get the domain name from the URL
		@preg_match('@^(?:http://)?([^/]+)@i', $data[0], $domain);
			
		$q = mysql_query("SELECT id,redirecturl,statuscode FROM rewriterules WHERE '$domain[1]' REGEXP urlexpr"); //or die (mysql_error());
		$r = mysql_fetch_object($q);
		
		if (isset($r->id)) {
			
			if(DEBUG)
				@$this->log("Tried: ".$domain[1]);
			
			echo $r->statuscode.":".$r->redirecturl."\n";
			
			return TRUE;
		}
	
	}
	private function log_url($args) {

		// Get the IP address of the person to log.

		@preg_match('/((\d+).(\d+).(\d+).(\d+))/', $args[1], $ip);
		@$ip = $ip[0];
		mysql_query("INSERT INTO log SET source='$ip', destination='$args[0]'");

	}
	private function log_search_engine($data) {	

		//Get the domain name from the URL
		@preg_match('@^(?:http://)?([^/]+)@i', $data[0], $domain);
			
		$q = mysql_query("SELECT id,qexpr FROM robots WHERE '$domain[1]' REGEXP urlexpr"); //or die (mysql_error());
		$r = mysql_fetch_object($q);
		
		if (isset($r->id)) {
			@preg_match('/'.$r->qexpr.'/', $data[0], $query);
			@preg_match('/((\d+).(\d+).(\d+).(\d+))/', $data[1], $ip);
			@mysql_query("INSERT INTO robot_queries SET search = '".str_replace("+", " ", $query[1])."', source = '$ip[0]'") or $this->log(mysql_error);
			if(DEBUG)
				@$this->log("Search query: ".$query[1]);
		}
	
	}	
	
	private function log($data) {
		openlog("squidre", 0, LOG_LOCAL0);
		syslog(LOG_DEBUG, $data);
		closelog();
	}	
	
	
	
}


?>
