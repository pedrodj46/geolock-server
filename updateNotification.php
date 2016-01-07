<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_notification";

	$json=json_decode($_POST['jsonNotification']);

	$idN=$json->idN;
	$letta=$json->letta;

	$array1=['letta', 'data_letta'];
	$array2=[$letta, date("Y-m-d H:i:s")];

	$where=" WHERE id=".$idN;

	$controllo=queryGo($tabella, $array1, $array2, $where);

	echo $controllo;
 
?>