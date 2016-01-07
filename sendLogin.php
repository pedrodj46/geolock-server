<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_users";

	$json=json_decode($_POST['jsonLogin']);

	$username=$json->username;
	$password=$json->password;
	$device=$json->device;
	$androidid=$json->androidid;

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." WHERE username='".$username."' AND password='".md5($password)."'";
	$risposta=mysql_query($sql) or die("Erorre ".mysql_error());
	$riga=mysql_fetch_array($risposta);
	if($riga['password']==md5($password)){

		$id_utente=$riga['id'];

		$array1=['data', 'id_utente', 'email_utente', 'device', 'androidid'];
		$array2=[date("Y-m-d H:i:s"), $id_utente, $username, $device, $androidid];
		$controllo=queryGo($tabella."_login", $array1, $array2, "");

		echo $riga['id'];
	}
	else{
		echo 0;
	}

	mysql_close($conn);
?>