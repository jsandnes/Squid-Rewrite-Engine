#!/usr/bin/php 
<?PHP
	/* Squid Rewrite Enigne
	* Jan Eirik Sandnes, 2010
	*/

	require_once('inc/rewrite.class.php');

	$i = 0;

	while ( $data = fgets(STDIN) ) {
		$i++;
		$rewriter[$i] = new rewriter(serialize($data));
	}
?>
