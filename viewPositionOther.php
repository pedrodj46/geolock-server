<?php
	include("lib/config.php");
	include("lib/funzioni.php");

	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');

	$tabella="tab_users";

	$conn=dbConnect();
	$sql="SELECT * FROM ".$tabella." LIMIT 1";
	$risposta1=mysql_query($sql);
	while($riga1=mysql_fetch_array($risposta1)){
		$sql="SELECT * FROM ".$tabella."_position WHERE id_utente=".$riga1['id']." ORDER BY data DESC LIMIT 1";
		$risposta=mysql_query($sql);
		while($riga=mysql_fetch_array($risposta)){
      $latitude=$riga['latitudine'];
			$longitude=$riga['longitudine'];
		}
	}

  $radius = 6371;
  $distance = 5;
  echo $bearing = 270;

  //  New latitude in degrees.
  $new_latitude_sud = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));
      
  //  New longitude in degrees.
  $new_longitude_sud = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($new_latitude))));
  
  echo $bearing += 90;
  if($bearing>360){
    $bearing=abs($bearing-360);
  }

  //  New latitude in degrees.
  $new_latitude_est = rad2deg(asin(sin(deg2rad($new_latitude_sud)) * cos($distance / $radius) + cos(deg2rad($new_latitude_sud)) * sin($distance / $radius) * cos(deg2rad($bearing))));
      
  //  New longitude in degrees.
  $new_longitude_est = rad2deg(deg2rad($new_longitude_sud) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($new_latitude_sud)), cos($distance / $radius) - sin(deg2rad($new_latitude_sud)) * sin(deg2rad($new_latitude_est))));

  $str="{lat: ".$latitude.", lng: ".$longitude."},{lat: ".$new_latitude_sud.", lng: ".$new_longitude_sud."},{lat: ".$new_latitude_est.", lng: ".$new_longitude_est."}";
  
  echo $bearing += 90;
  if($bearing>360){
    $bearing=abs($bearing-360);
  }

  //  New latitude in degrees.
  $new_latitude_nord = rad2deg(asin(sin(deg2rad($new_latitude_est)) * cos($distance / $radius) + cos(deg2rad($new_latitude_est)) * sin($distance / $radius) * cos(deg2rad($bearing))));
      
  //  New longitude in degrees.
  $new_longitude_nord = rad2deg(deg2rad($new_longitude_est) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($new_latitude_est)), cos($distance / $radius) - sin(deg2rad($new_latitude_est)) * sin(deg2rad($new_latitude_nord))));

  $str="{lat: ".$latitude.", lng: ".$longitude."},{lat: ".$new_latitude_sud.", lng: ".$new_longitude_sud."},{lat: ".$new_latitude_est.", lng: ".$new_longitude_est."},{lat: ".$new_latitude_nord.", lng: ".$new_longitude_nord."}";

  $mediaLat=($latitude+$new_latitude_sud+$new_latitude_est+$new_latitude_nord)/4;
  $mediaLon=($longitude+$new_longitude_sud+$new_longitude_est+$new_longitude_nord)/4;

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
/*
      var arr= ["<?php echo $latitude; ?>","<?php echo $longitude; ?>"];
      prova=getBoundingBox(arr, 5);
      alert(prova);

     function getBoundingBox(centerPoint, distance) {
        var MIN_LAT, MAX_LAT, MIN_LON, MAX_LON, R, radDist, degLat, degLon, radLat, radLon, minLat, maxLat, minLon, maxLon, deltaLon;
        if (distance < 0) {
          return 'Illegal arguments';
        }
        // helper functions (degrees<–>radians)
        Number.prototype.degToRad = function () {
          return this * (Math.PI / 180);
        };
        Number.prototype.radToDeg = function () {
          return (180 * this) / Math.PI;
        };
        // coordinate limits
        MIN_LAT = (-90).degToRad();
        MAX_LAT = (90).degToRad();
        MIN_LON = (-180).degToRad();
        MAX_LON = (180).degToRad();
        // Earth's radius (km)
        R = 6378.1;
        // angular distance in radians on a great circle
        radDist = distance / R;
        // center point coordinates (deg)
        degLat = centerPoint[0];
        degLon = centerPoint[1];
        // center point coordinates (rad)
        radLat = degLat.degToRad();
        radLon = degLon.degToRad();
        // minimum and maximum latitudes for given distance
        minLat = radLat - radDist;
        maxLat = radLat + radDist;
        // minimum and maximum longitudes for given distance
        minLon = void 0;
        maxLon = void 0;
        // define deltaLon to help determine min and max longitudes
        deltaLon = Math.asin(Math.sin(radDist) / Math.cos(radLat));
        if (minLat > MIN_LAT && maxLat < MAX_LAT) {
          minLon = radLon - deltaLon;
          maxLon = radLon + deltaLon;
          if (minLon < MIN_LON) {
            minLon = minLon + 2 * Math.PI;
          }
          if (maxLon > MAX_LON) {
            maxLon = maxLon - 2 * Math.PI;
          }
        }
        // a pole is within the given distance
        else {
          minLat = Math.max(minLat, MIN_LAT);
          maxLat = Math.min(maxLat, MAX_LAT);
          minLon = MIN_LON;
          maxLon = MAX_LON;
        }
        return [
          minLon.radToDeg(),
          minLat.radToDeg(),
          maxLon.radToDeg(),
          maxLat.radToDeg()
        ];
      };*/

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>
  </body>
</html>