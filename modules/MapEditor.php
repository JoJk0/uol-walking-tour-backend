<?php 
//ALLOWING USER TO DRAG THEIR LOCATION USING GOOGLE MAPS API
  include '../modules/config.php';

echo '
       <div class="top-bar">
          <div id="back-picker"><i class="material-icons">close</i></div>
          <div class="form-input title centered">'.$_LANG['MAPPICKER_TITLE'].'</div>
          <div class="button main" id="picker-done">'.$_LANG['MAPPICKER_DONE'].'</div>
       </div>

    <!--The div element for the map -->
    <div id="map"></div>';

      ?>
    <script>
<?php
  $id = trim($_GET['id']);
  $lat = trim($_GET['lat']);
  $long = trim($_GET['long']);
?>
  
	var laturl, lngurl, moved = false;
	
// Initialize and add the map
function initMap() {

	var myLatLng = {lat: <?php echo $lat;?>, lng: <?php echo $long;?>};

  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), 
	  {	  
		zoom: 18, 
		center: myLatLng
	  });
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker(
  {
	position: myLatLng, 
	map: map,
	title: "Location of Artwork",
	animation: google.maps.Animation.BOUNCE,
	draggable: true
	

  });
  
	google.maps.event.addListener(marker, "drag", function(){
			laturl=marker.getPosition().lat().toFixed(6);
			lngurl=marker.getPosition().lng().toFixed(6);
			moved = true;			
	});
			
}

     // Close the window
     $("#back-picker").click(function(){
        $("#map-picker-cnt").css("display","none");
        $("#map-picker").html('');
     });
     
     // Save the location
     $("#picker-done").click(function(){
       if(!moved){
         var pickerDone = document.getElementById("picker-done");
         alert("You need to move the pin on the map first!");
       } else{
           $("#coords").val(laturl+","+lngurl);
           $("#map-picker-cnt").css("display","none");
           $("#map-picker").html('');
       }

     });
      
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD72Pm8WBcUrUHHomaKTjATvcPut1cWsGs&callback=initMap">
     // key=AIzaSyD72Pm8WBcUrUHHomaKTjATvcPut1cWsGs
    </script>
