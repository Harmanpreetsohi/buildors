<?php

	$f = fopen('record.txt', 'a+');

	fwrite($f, "\n\n\nHEllo\n\n\n");

	fwrite($f, json_encode($_REQUEST));

	fclose($f);


	echo '<?xml version="1.0" encoding="UTF-8"?>';
	//mail("mirzaahsan42@gmail.com","handle recording",print_r($_REQUEST,true));
?>
	<Response>
		<Say>thanks for your recording, goodbye.</Say>
		<Hangup />
	</Response>