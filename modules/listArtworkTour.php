<?php 
//OUTPUTTING ALL THE AVAILABLE TOUR INFORMATION (CONNECTIONG TO AN ARTWORK) TO BE USED IN THE MOBILE APPLICATION 
include 'config.php';

$q = "SELECT * FROM Artwork_Tour WHERE TourID='".$_GET['TourID']."'";
$iq = mysqli_query($con, $q);

$data = array();

$i = 0;
while($r = mysqli_fetch_array($iq)){
  
  $data[$i] = $r['ArtworkID'];
  
	$i++;
}

echo json_encode($data);//JSON encoding data to be fetch by the mobile app

?>