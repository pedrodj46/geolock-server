<?php
	header('Content-Type: application/json');

	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_images";

	$idU=(int)$_GET['idU'];
	$json="";
	$i=0;

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." WHERE id_utente=".$idU;
	$risposta=mysql_query($sql) or die("Errore ".mysql_error());
	while($riga=mysql_fetch_array($risposta)){
		if($i!=0){
			$json.=",";
		}
		$json.='{"nome":"'.$riga['nome'].'", "lat":"'.$riga['lat'].'", "lon":"'.$riga['lon'].'", "ang":"'.$riga['angUtente'].'", "dis":"'.$riga['distanza'].'", "idF":"'.$riga['id'].'"}';
		$i++;
	}

	$json='['.$json.']';

	mysql_close($conn);

	echo $json;

?>