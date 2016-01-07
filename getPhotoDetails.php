<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_images";

	$json=json_decode($_POST['jsonIdPhoto']);

	$idF=$json->idF;

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." WHERE id=".$idF;
	$risposta=mysql_query($sql) or die("Erorre ".mysql_error());
	$riga=mysql_fetch_array($risposta);

	//$json=array("data" => $riga['data'], "nome" => $riga['nome'], "lat" => $riga['lat'], "lon" => $riga['lon'], "indirizzo" => $riga['indirizzo'], "dirUtente" => $riga['dirUtente'], "angUtente" => $riga['angUtente'], "dirSogg" => $riga['dirSogg'], "angSogg" => $riga['angSogg'], "distanza" => $riga['distanza']);

	$json='{"data":"'.$riga['data'].'","nome":"'.$riga['nome'].'", "lat":"'.$riga['lat'].'", "lon":"'.$riga['lon'].'", "indirizzo":"'.$riga['indirizzo'].'", "dirUtente":"'.$riga['dirUtente'].'", "angUtente":"'.$riga['angUtente'].'", "dirSogg":"'.$riga['dirSogg'].'", "angSogg":"'.$riga['angSogg'].'", "distanza":"'.$riga['distanza'].'", "aperta":"'.$riga['aperta'].'", "messaggio":"'.$riga['messaggio'].'"}';

	mysql_close($conn);

	echo $json;
?>