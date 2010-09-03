#!/usr/bin/php 
<?PHP
	/* Squid Rewrite Enigne
	 * Jan Eirik Sandnes, 2010
	 */

	require_once('inc/rewrite.class.php');

	$i = 0;
	mysql_connect(SQL_HOST, SQL_USER, SQL_PASS);
	mysql_select_db(SQL_DB);
	while ( $data = fgets(STDIN) ) {
		$i++;
		$rewriter[$i] = new rewriter(serialize($data));
	}
	mysql_close();
?>
