<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_images";

	$conn=dbConnect();

	$json=json_decode($_POST['jsonImage']);

	$idFoto=$json->idFoto;
	$nome=$json->name;
	$lat=$json->latitude;
	$lon=$json->longitude;
	$indirizzo=$json->address;
	$dirUtente=$json->direzioneUtente;
	$angUtente=$json->gradiUtente;
	$dirSogg=$json->direzioneSoggetto;
	$angSogg=$json->gradiSoggetto;
	$distanza=$json->distanza;

	$array1=['data', 'id_foto', 'nome', 'lat', 'lon', 'indirizzo', 'dirUtente', 'angUtente', 'dirSogg', 'angSogg', 'distanza'];
	$array2=[date("Y-m-d H:i:s"), $idFoto, addslashes($nome), addslashes($lat), addslashes($lon), addslashes($indirizzo), addslashes($dirUtente), addslashes($angUtente), addslashes($dirSogg), addslashes($angSogg), addslashes($distanza)];

	$where="";

	$controllo=queryGo($tabella, $array1, $array2, $where);

	mysql_close($conn);

	echo "1";
?>