<?php
	header('Content-Type: application/json');

	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_notification";

	if(isset($_GET['idU']) && (int)$_GET['idU']!=0){

		$idU=(int)$_GET['idU'];

		$i=0;
		$json="";

		$conn=dbConnect();
		$sql="SELECT t2.*, t1.letta AS letta, t1.id AS idN, t1.id_utente_invio AS idU, t1.data AS dataInvio FROM ".$tabella." AS t1 LEFT JOIN tab_images AS t2 ON t1.id_foto=t2.id WHERE t1.id_utente_ricezione=".$idU." ORDER BY t1.data DESC";
		$risposta=mysql_query($sql) or die("Erorre ".mysql_error());
		while($riga=mysql_fetch_array($risposta)){

			$sql1="SELECT * FROM tab_users WHERE id=".$riga['idU'];
			$risposta1=mysql_query($sql1) or die("Errore ".mysql_error());
			$riga1=mysql_fetch_array($risposta1);

			if($i!=0){
				$json.=',';
			}

			$dataInvio="il ".date("d/m/Y", strtotime($riga['dataInvio']))." alle ".date("H:m", strtotime($riga['dataInvio']));

			$json.='{"nome":"'.$riga['nome'].'", "nomeUtente":"'.$riga1['nome'].'", "dataInvio":"'.$dataInvio.'", "letta":"'.$riga['letta'].'", "idN":"'.$riga['idN'].'", "aperta":"'.$riga['aperta'].'"}';
			$i++;
		}
		$json='['.$json.']';

		mysql_close($conn);

		echo $json;

	}
	else if(isset($_GET['idN']) && (int)$_GET['idN']!=0){
		$idN=(int)$_GET['idN'];

		$json="";

		$conn=dbConnect();
		$sql="SELECT * FROM ".$tabella." WHERE id=".$idN;
		$risposta=mysql_query($sql) or die("Errore ".mysql_error());
		$riga=mysql_fetch_array($risposta);
		if($riga['letta']==0){
			$sql="UPDATE ".$tabella." SET letta=1, data_letta='".date("Y-m-d H:i:s")."' WHERE id=".$idN;
			$risposta=mysql_query($sql) or die("Errore ".mysql_error());
		}

		$sql="SELECT t2.* FROM ".$tabella." AS t1 LEFT JOIN tab_images AS t2 ON t1.id_foto=t2.id WHERE t1.id=".$idN;
		$risposta=mysql_query($sql) or die("Errore ".mysql_error());
		$riga=mysql_fetch_array($risposta);

		$json.='[{"nome":"'.$riga['nome'].'", "lat":"'.$riga['lat'].'", "lon":"'.$riga['lon'].'", "indirizzo":"'.$riga['indirizzo'].'", "dirUtente":"'.$riga['dirUtente'].'", "angUtente":"'.$riga['angUtente'].'", "dirSogg":"'.$riga['dirSogg'].'", "angSogg":"'.$riga['angSogg'].'", "distanza":"'.$riga['distanza'].'", "aperta":"'.$riga['aperta'].'", "messaggio":"'.$riga['messaggio'].'"}]';

		mysql_close($conn);

		echo $json;

	}
?>