<?php 
//OUTPUTTING ALL THE AVAILABLE ARTWORK INFORMATION TO BE USED IN THE MOBILE APPLICATION 
include 'config.php';
if(isset($_GET['id'])){
  $q = "SELECT * FROM artworks2 WHERE id = '".$_GET['id']."'";
} else{
  $q = "SELECT * FROM artworks2";
}

$iq = mysqli_query($con, $q);

$data = array();

while($r = mysqli_fetch_array($iq)){
  
	$q2 = "SELECT TourID FROM Artwork_Tour WHERE ArtworkID = '".$r['id']."'";
	$iq2 = mysqli_query($con, $q2);
	$tours = '';
	
	while($r2 = mysqli_fetch_array($iq2)){
  
 	 	$tours = $tours.$r2['TourID']." ";

	}
	
  $data[$r['id']] = array(
    
    'id'    =>  $r['id'],
    'title' => $r['title'],
    'artist' => $r['artist'],
    'yearOfWork' => $r['yearOfWork'],
    'Information' => str_replace('&quot', ' ', $r['Information']),
    'lat'         =>  $r['lat'],
    'long'        =>  $r['long'],
    'location'    =>  $r['location'],
    'locationNotes' =>  $r['locationNotes'],
    'fileName'      =>  $r['fileName'],
    'lastModified'  =>  $r['lastModified'],
    'enabled'       =>  $r['enabled'],
		'tours'					=>	$tours
  
  );
}

echo json_encode(utf8ize($data));//JSON encoding data to be fetch by the mobile app

?>