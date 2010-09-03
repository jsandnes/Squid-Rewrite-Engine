<?PHP
require_once("config.php");

class rewriter {

	public function __construct($args) {

		//Split the data from squid into an array.
		$this->proxyData = preg_split('/ /', unserialize($args));

		//Run the rewriter to see if the url should be treated.
		if(!$this->rewrite($this->proxyData[0]))
			print $this->proxyData[0]."\n";

		/*
		if ($this->proxyData[0] == 'http://www.vg.no/')
		echo "404:http://www.db.no/\n"; 
		*/

		if(DEBUG) {
			$this->log(unserialize($args));
		}

	}

	private function rewrite($url) {
		
	}

	private function log_search_engine($data) {
		// TODO

	}	
	
	private function log($data) {
		openlog("squidre", 0, LOG_LOCAL0);
		syslog(LOG_DEBUG, $data);
		closelog();
	}	
	
	
	
}


?>
