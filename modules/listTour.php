<?php 
//OUTPUTTING ALL THE AVAILABLE TOUR INFORMATION TO BE USED IN THE MOBILE APPLICATION
include 'config.php';

if(isset($_GET['id'])){
  $q = "SELECT * FROM Tour WHERE id = '".$_GET['id']."'";
} else{
  $q = "SELECT * FROM Tour";
}
$iq = mysqli_query($con, $q);

$data = array();

while($r = mysqli_fetch_array($iq)){
  
  $data[$r['id']] = array(
    
    'id'    =>  $r['id'],
    'name' => $r['name'],
  
  );
  


}

 echo json_encode($data);//JSON encoding data to be fetch by the mobile app

?>