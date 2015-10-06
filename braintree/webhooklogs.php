 <?php
 	header('Content-Type: text/plain');
 	/*
	$file = fopen("./webhook.log","r");
	
	while(! feof($file))
	  {
	  echo fgets($file). "<br />";
	  }
	
	fclose($file);
	*/
	$fh = fopen("./webhook.log", 'r');
	
	$pageText = fread($fh, 25000);
	echo "<pre>";
	echo nl2br($pageText);
	echo "</pre>";
?>     