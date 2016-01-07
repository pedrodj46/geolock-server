<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_regid";

	$conn=dbConnect();

	$sql="SELECT * FROM ".$tabella." WHERE ID=3";
	$risposta=mysql_query($sql) or die("Errore ".mysql_error());
	while($riga=mysql_fetch_array($risposta)){
		$regid=$riga['regid'];

		$url='https://android.googleapis.com/gcm/send';
		define("GOOGLE_API_KEY","AIzaSyDEKQuGK7n4EHSKX74dZQm7kuk468bV2GA");
		$registration_ids=array($regid);
		$message=array("message" => array("titolo" => "Nuovo", "testo" => "testo_notifica", "idFoto" => "46"));
		$fields=array('registration_ids' => $registration_ids,'data' =>$message);
		$headers=array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

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

	mysql_close($conn);

?>