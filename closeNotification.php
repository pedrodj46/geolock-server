<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_notification";

	$json=json_decode($_POST['jsonClose']);

	$idN=$json->idN;
	$messaggio=$json->messaggio;

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." WHERE id=".$idN;
	$risposta=mysql_query($sql) or die("Errore ".mysql_error());
	$riga=mysql_fetch_array($risposta);
	$idFoto=$riga['id_foto'];
	$idUtente=$riga['id_utente_ricezione'];

	$sql="SELECT * FROM tab_images WHERE id=".$idFoto;
	$risposta=mysql_query($sql) or die("Errore ".mysql_error());
	$riga=mysql_fetch_array($risposta);
	$idFotoPrincipale=$riga['id_foto'];

	if($idFotoPrincipale==0){
		$array1=['aperta', 'data_chiusura', 'utente_chiusura', 'messaggio'];
		$array2=['0', date("Y-m-d H:i:s"), $idUtente, utf8_encode($messaggio)];

		$where=" WHERE id=".$idFoto;

		$controllo=queryGo("tab_images", $array1, $array2, $where);
	}
	else{
		$array1=['aperta', 'data_chiusura', 'utente_chiusura', 'messaggio'];
		$array2=['0', date("Y-m-d H:i:s"), $idUtente, utf8_encode($messaggio)];

		$where=" WHERE id=".$idFotoPrincipale." OR id_foto=".$idFotoPrincipale;

		$controllo=queryGo("tab_images", $array1, $array2, $where);

	}

	echo "1";
 
?>