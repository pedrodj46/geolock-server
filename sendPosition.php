<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_users_position";

	$json=json_decode($_POST['jsonPosition']);

	$lat=$json->lat;
	$lon=$json->lon;
	$acc=$json->acc;
	$speed=$json->speed;
	$id_utente=$json->id_utente;
	$androidid=$json->androidid;

	$array1=['data', 'androidid', 'id_utente', 'latitudine', 'longitudine', 'accuracy', 'speed'];
	$array2=[date("Y-m-d H:i:s"), $androidid, $id_utente, $lat, $lon, $acc, $speed];
	$controllo=queryGo($tabella, $array1, $array2, "");

	mysql_close($conn);
?>