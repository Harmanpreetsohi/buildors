<?php 


	$f = fopen('recording.txt', 'a+');

	fwrite($f, json_encode($_REQUEST));


	fwrite($f, '---------------------------------');

	fclose($f);


	$f = fopen('recording.txt', 'a+');

	fwrite($f, json_encode($_FILES));

	fclose($f);

 ?>