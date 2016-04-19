<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$tabella="tab_images";

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." WHERE id=96";
	$risposta=mysql_query($sql);
	while($riga=mysql_fetch_array($risposta)){
    $data=$riga['data'];
    $lat=$riga['lat'];
    $lon=$riga['lon'];
    $distanza=$riga['distanza'];
    $angUtente=$riga['angUtente'];
		$angSogg=$riga['angSogg'];
	}

  $raggio=2.5;

  $lat_oggetto = $lat + cos(deg2rad($angUtente)) * ($distanza/1000/110.54);
  $lon_oggetto = $lon + sin(deg2rad($angUtente)) * ($distanza/1000/111.320 * cos(deg2rad($lat_oggetto)));

  $lat_cerchio = $lat_oggetto + cos(deg2rad($angSogg)) * ($raggio/110.54);
  $lon_cerchio = $lon_oggetto + sin(deg2rad($angSogg)) * ($raggio/111.320 * cos(deg2rad($lat_cerchio)));

  $longitude1=$lon_oggetto;
  $longitude2=$lon_cerchio;
  $latitude1=$lat_oggetto;
  $latitude2=$lat_cerchio;

  $theta = $longitude1 - $longitude2;
  $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
  $distance = acos($distance);
  $distance = rad2deg($distance);
  $distance = $distance * 60 * 1.1515 * 1.609344;
  $d=round($distance,2);

  echo "RAGGIO: ".$d."km<br />";

  $i=0;
  $sql="SELECT * FROM tab_users";
  $risposta=mysql_query($sql) or die("Errore ".mysql_error());
  while($riga=mysql_fetch_array($risposta)){
    $sql2="SELECT * FROM tab_users_position WHERE id_utente=".$riga['id']." AND data<'".$data."' AND accuracy<60 LIMIT 1";
    $risposta2=mysql_query($sql2) or die("Errore ".mysql_error());
    $riga2=mysql_fetch_array($risposta2);

    $longitude1=$lon_cerchio;
    $longitude2=$riga2['longitudine'];
    $latitude1=$lat_cerchio;
    $latitude2=$riga2['latitudine'];

    $theta = $longitude1 - $longitude2;
    $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $distance = acos($distance);
    $distance = rad2deg($distance);
    $distance = $distance * 60 * 1.1515 * 1.609344;
    $d=round($distance,2);

    if($riga2['latitudine']>0 && $riga2['longitudine']>0){
      $str[$i]="{lat: ".$riga2['latitudine'].", lng: ".$riga2['longitudine']."},";
      $i++;
    }

    echo "DISTANZA ".$riga['id'].": ".$d." km - ";
  }

  echo "<br />COORDINATE OGGETTO: ".$lat_oggetto.", ".$lon_oggetto." - COORDINATE CERCHIO: ".$lat_cerchio.", ".$lon_cerchio;

	mysql_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polylines</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: <?php echo $lat_cerchio; ?>, lng: <?php echo $lon_cerchio; ?>},
          mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        var circleCenter = new google.maps.Circle({
          strokeColor: '#00FF00',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#00FF00',
          fillOpacity: 0.35,
          map: map,
          center: {lat: <?php echo $lat_cerchio; ?>, lng: <?php echo $lon_cerchio; ?>},
          radius: <?php echo $raggio*1000; ?>
        });

        var marker = new google.maps.Marker({
          map: map,
          position: {lat: <?php echo $lat_oggetto; ?>, lng: <?php echo $lon_oggetto; ?>},
          title: 'Oggetto'
        });

        <?php for($i=0;$i<count($str);$i++){ ?>
        var marker<?php echo $i; ?> = new google.maps.Marker({
          map: map,
          position: <?php echo $str[$i]; ?>
          title: 'User<?php echo $i; ?>'
        });

        <?php } ?>
      }

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>
  </body>
</html>
