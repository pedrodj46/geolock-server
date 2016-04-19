<?php
	header('Content-Type: application/json');

	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_notification";

	$idN=(int)$_GET['idN'];

	$json="";

	$conn=dbConnect();
	$sql="SELECT t2.* FROM ".$tabella." AS t1 LEFT JOIN tab_images AS t2 ON t1.id_foto=t2.id WHERE t1.id=".$idN;
	$risposta=mysql_query($sql) or die("Errore ".mysql_error());
	$riga=mysql_fetch_array($risposta);

	$angUtente=$riga['angUtente'];
	$angSogg=$riga['angSogg'];

	$angSogg+=$angUtente;
	if($angSogg>360){
		$angSogg-=360;
	}

	$json.='[{"lat":"'.$riga['lat'].'", "lon":"'.$riga['lon'].'", "gradi":"'.$angSogg.'"}]';

	mysql_close($conn);

	echo $json;
?>