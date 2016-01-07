<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_images";

	$json=json_decode($_POST['jsonImage']);

	$idUtente=$json->idUtente;
	$idNotifica=$json->idNotifica;
	$nome=$json->name;
	$lat=$json->latitude;
	$lon=$json->longitude;
	$indirizzo=$json->address;
	$dirUtente=$json->direzioneUtente;
	$angUtente=$json->gradiUtente;
	$dirSogg=$json->direzioneSoggetto;
	$angSogg=$json->gradiSoggetto;
	$distanza=$json->distanza;

	if($idNotifica!=0){
		$conn=dbConnect();
		$sql="SELECT * FROM tab_notification WHERE id=".$idNotifica;
		$risposta=mysql_query($sql) or die("Errore ".mysql_error());
		$riga=mysql_fetch_array($risposta);
		$idFoto=$riga['id_foto'];
		$sql="SELECT * FROM ".$tabella." WHERE id=".$idFoto;
		$risposta=mysql_query($sql) or die("Errore ".mysql_error());
		$riga=mysql_fetch_array($risposta);
		$idFotoPrincipale=$riga['id_foto'];
		if($idFotoPrincipale==0){
			$idFotoPrincipale=$idFoto;
		}
	}
	else{
		$idFotoPrincipale=0;
	}

	$array1=['data', 'id_foto', 'id_utente', 'nome', 'lat', 'lon', 'indirizzo', 'dirUtente', 'angUtente', 'dirSogg', 'angSogg', 'distanza'];
	$array2=[date("Y-m-d H:i:s"), $idFotoPrincipale, $idUtente, addslashes($nome), addslashes($lat), addslashes($lon), addslashes($indirizzo), addslashes($dirUtente), addslashes($angUtente), addslashes($dirSogg), addslashes($angSogg), addslashes($distanza)];

	$where="";

	$controllo=queryGo($tabella, $array1, $array2, $where);

	// calcolo raggio

	$raggio=2.5;

	$lat_oggetto = $lat + cos(deg2rad($angUtente)) * ($distanza/1000/110.54);
	$lon_oggetto = $lon + sin(deg2rad($angUtente)) * ($distanza/1000/111.320 * cos(deg2rad($lat_oggetto)));

	$lat_cerchio = $lat_oggetto + cos(deg2rad($angSogg)) * ($raggio/110.54);
	$lon_cerchio = $lon_oggetto + sin(deg2rad($angSogg)) * ($raggio/111.320 * cos(deg2rad($lat_cerchio)));

	$conn=dbConnect();

	$sql="SELECT MAX(id) AS maxid FROM ".$tabella;
	$risposta=mysql_query($sql) or die("Errore 1 ".mysql_error());
	$riga=mysql_fetch_array($risposta);
	$idF=$riga['maxid'];

	//$sql="SELECT * FROM tab_users WHERE id!=".$idUtente;
	$sql="SELECT * FROM tab_users";
	$risposta=mysql_query($sql) or die("Errore ".mysql_error());
	while($riga=mysql_fetch_array($risposta)){
		$idUtenteSel=$riga['id'];

		$sql2="SELECT * FROM tab_users_position WHERE id_utente=".$idUtenteSel." AND accuracy<60 LIMIT 1";
		$risposta2=mysql_query($sql2) or die("Errore ".mysql_error());
		$riga2=mysql_fetch_array($risposta2);
		$idPosizione=$riga2['id'];

		$androidid=$riga2['androidid'];

		$longitude1=$lon_cerchio;
		$longitude2=$riga2['longitudine'];
		$latitude1=$lat_cerchio;
		$latitude2=$riga2['latitudine'];

		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515 * 1.609344;
		$distance=round($distance,2);

		if($distance<=$raggio){

			$array1=['data', 'id_utente_invio', 'id_utente_ricezione', 'id_foto', 'letta'];
			$array2=[date("Y-m-d H:i:s"), $idUtente, $idUtenteSel, $idF ,0];

			$where="";

			$controllo=queryGo("tab_notification", $array1, $array2, $where);

			$conn=dbConnect();

			$sql3="SELECT MAX(id) AS maxidN FROM tab_notification";
			$risposta3=mysql_query($sql3) or die("Errore ".mysql_error());
			$riga3=mysql_fetch_array($risposta3);
			$idN=$riga3['maxidN'];

			$tabella="tab_regid";

			$sql3="SELECT * FROM ".$tabella." WHERE androidid LIKE '".$androidid."'";
			$risposta3=mysql_query($sql3) or die("Errore 2 ".mysql_error());
			$riga3=mysql_fetch_array($risposta3);
			$regid=$riga3['regid'];

			$url='https://android.googleapis.com/gcm/send';
			define("GOOGLE_API_KEY","AIzaSyDEKQuGK7n4EHSKX74dZQm7kuk468bV2GA");
			$registration_ids=array($regid);
			$message=array("message" => array("titolo" => "Nuovo oggetto da identificare!", "testo" => "Si sta dirigendo verso di te questo oggetto", "idFoto" => "".$idF."", "idNotifica" => "".$idN.""));
			$fields=array('registration_ids' => $registration_ids,'data' =>$message);
			$headers=array('Authorization: key='.GOOGLE_API_KEY,'Content-Type: application/json');

			$ccurl=curl_init();
			curl_setopt($ccurl, CURLOPT_URL, $url);
			curl_setopt($ccurl, CURLOPT_POST, true);
			curl_setopt($ccurl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ccurl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ccurl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ccurl, CURLOPT_POSTFIELDS, json_encode($fields));

			$result=curl_exec($ccurl);
			curl_close($ccurl);
		}
	}

	mysql_close($conn);

	echo "1";
?>