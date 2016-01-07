<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');

	$tabella="tab_images";

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." ORDER BY data DESC LIMIT 1";
	$risposta=mysql_query($sql);
	while($riga=mysql_fetch_array($risposta)){
    $latitude=$riga['lat'];
    $longitude=$riga['lon'];
    $distanza=$riga['distanza'];
    $angolo=$riga['angUtente'];
		$angolo_0=$riga['angSogg'];
	}

  $km=5;
  $diagonale=sqrt(pow($km,2)+pow($km,2));
  $angolo_1=$angolo_0-45;
  if($angolo_1<0){
    $angolo_1=abs($angolo_1+360);
  }
  $angolo_2=$angolo_0+45;
  if($angolo_2>360){
    $angolo_2=abs($angolo_2-360);
  }

  echo "ANGOLO PARTENZA: ".$angolo_0."°, ANGOLO 1: ".$angolo_1."°, ANGOLO 2: ".$angolo_2."°";

  $new_latitude = $latitude + cos(deg2rad($angolo)) * ($distanza/1000/110.54);
  $new_longitude = $longitude + sin(deg2rad($angolo)) * ($distanza/1000/111.320*cos(deg2rad($new_latitudine)));

  $new_latitude_1 = $new_latitude + cos(deg2rad($angolo_1)) * ($km/110.54);
  $new_longitude_1 = $new_longitude + sin(deg2rad($angolo_1)) * ($km/111.320*cos(deg2rad($new_latitudine_1)));

  $new_latitude_2 = $new_latitude + cos(deg2rad($angolo_2)) * ($km/110.54);
  $new_longitude_2 = $new_longitude + sin(deg2rad($angolo_2)) * ($km/111.320*cos(deg2rad($new_latitudine_2)));

  $new_latitude_3 = $new_latitude + cos(deg2rad($angolo_0)) * ($diagonale/110.54);
  $new_longitude_3 = $new_longitude + sin(deg2rad($angolo_0)) * ($diagonale/111.320*cos(deg2rad($new_latitudine_3)));

  $str="{lat: ".$latitude.", lng: ".$longitude."},{lat: ".$new_latitude.", lng: ".$new_longitude."},{lat: ".$new_latitude_1.", lng: ".$new_longitude_1."},{lat: ".$new_latitude_3.", lng: ".$new_longitude_3."},{lat: ".$new_latitude_2.", lng: ".$new_longitude_2."},{lat: ".$new_latitude.", lng: ".$new_longitude."}";
  $str2=",{lat: ".$new_latitude_1.", lng: ".$new_longitude_1."},{lat: ".$new_latitude_3.", lng: ".$new_longitude_3."},{lat: ".$new_latitude_2.", lng: ".$new_longitude_2."}";

  $str3="lat: ".$new_latitude.", lng: ".$new_longitude." - lat: ".$new_latitude_1.", lng: ".$new_longitude_1." - lat: ".$new_latitude_2.", lng: ".$new_longitude_2." - lat: ".$new_latitude_3.", lng: ".$new_longitude_3;

  $mediaLat=($latitude+$new_latitude+$new_latitude_1+$new_latitude_2+$new_latitude_3)/5;
  $mediaLon=($longitude+$new_longitude+$new_longitude_1+$new_longitude_2+$new_longitude_3)/5;

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
          center: {lat: <?php echo $mediaLat; ?>, lng: <?php echo $mediaLon; ?>},
          mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        var flightPlanCoordinates = [
          <?php echo $str; ?>
        ];
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#<?php echo $rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]; ?>',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
      }

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>
  </body>
</html>
