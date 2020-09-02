<?php 
//DELETING ARTWORK PHP PAGE 

  require_once 'config.php';

  if(!isset($_POST['delete_id']) || !$_SESSION['is_logged']){
    echo 'ERROR: UNAUTHORIZED USE OF SCRIPT';	//ERROR check
  } else{ 
      $delete_id = $_GET['delete_id'];	//assign get variables
      $type = $_GET['type'];
    
      if($type==0){
        $tableChoice = "artworks2";//decidin whether to delete from tour or artworks2
        $idChoice = "ArtworkID";
      }else{
        $tableChoice = "Tour";
        $idChoice = "TourID";
      }          
					
					$deleteArtwork = "DELETE FROM $tableChoice WHERE id = '$delete_id'"; //deleting from artworks2 or tour table
					$resultArtwork = mysqli_query($con,$deleteArtwork);
                                
          $deleteArtworkTour = "DELETE FROM Artwork_Tour WHERE $idChoice = '$delete_id'"; //deleting from connection table Artwork_Admin
					$resultArtworkTour = mysqli_query($con,$deleteArtworkTour);
					
    echo '1';
  } 

?>