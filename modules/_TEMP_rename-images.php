<?php 

include '../modules/config.php';

$q = "SELECT * FROM artworks2";
$iq = mysqli_query($con, $q);

while($r = mysqli_fetch_array($iq)){
	$filename = $r['fileName'];
	$new_filename = str_replace(" ", "", $filename);
	$new_filename = strtolower($new_filename);
	
	// Update db
	$q2 = "UPDATE artworks2 SET fileName = '$new_filename' WHERE id = '".$r['id']."'";
	$iq2 = mysqli_query($con, $q2);
	
	// Change filename of file
	//rename("../images/".$filename, "../images/".$new_filename);
}
 ?>