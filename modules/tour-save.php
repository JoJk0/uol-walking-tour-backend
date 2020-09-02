<?php

include '../modules/config.php';

 $new = $_GET['new'];
 $id = $_GET['id'];

if(!isset($_POST['save']) || !$_SESSION['is_logged']){
  echo 'ERROR: UNAUTHORIZED USE OF SCRIPT';
} else{
  //print_r($_POST);

  $post_name = htmlspecialchars($_POST['title']);
  $post_photo = $_FILES['photoFile']['name'];


if(!$new){ //~~~~~~~~~~~~~~~~~~~~~~~~EDIT MODE~~~~~~~~~~~~~
  if(empty($post_photo)){
    //no new photo
    $sql = "UPDATE Tour SET `name` = '$post_name' WHERE id = '$id'";

  }else{
    //update new photo
   $sql = "UPDATE Tour SET `name` = '$post_name', `fileName` = '$post_photo' WHERE id = '$id'";
    saveToDir();
  }

}else{ //~~~~~~~~~~~~~~~NEW ARTWORK MODE~~~~~~~~~~~

  if(empty($post_photo)){
    //no new photo uploaded
   $sql = "INSERT INTO Tour (`name`, `fileName`)
  VALUES ('$post_name', '1.png')";

  }else{
    //new photo
 $sql = "INSERT INTO Tour (`name`, `fileName`)
  VALUES ('$post_name', '$post_photo')";

  saveToDir();
  }
}

  if(checkType()){
    $insert_user = mysqli_query($con, $sql);
    $insert_user || die("USER Database access failed: ".mysqli_error($con)); //if access fails -> error message
    updateArtworks($con, $new, $id);
    echo '1';//CORRECT TYPE, DO QUERY
  }else{
    echo '3';//INCORRECT TYPE
  }
}


//~~~~~~~~~~~~~~FUNCTION TO CHECK IMAGE TYPE~~~~~~~~~~~~~~~~~~~~~~~~~~~
function checkType(){
  $file_type = $_FILES['photoFile']['type']; //returns the mimetype

  $allowed = array("image/jpeg", "image/gif", "image/png");
  if(in_array($file_type, $allowed) || empty($post_photo)) {
    return true;
  }else{
    return false;
  }
}

//~~~~~~~~~~~~~~FUNCTION TO SAVE FILE TO DIRECTORY~~~~~~~~~~~~~~~~~~~~~~~~~~~
function saveToDir(){
   $target = '/home/ud8462avu8pk/public_html/app/uol-walking-tour/images/tour/'.$_FILES['photoFile']['name'];  
    //Save image to directory (if it isn't there already)
    if(!file_exists($target)) move_uploaded_file( $_FILES['photoFile']['tmp_name'], $target);
}


 //~~~~~~~~~~~~~~~SAVING CHOOSEN ARTWORKS~~~~~~~~~~~~~~~~~~~~~~~~~~~
function updateArtworks($con, $new, $id){
  //~~~~~~~~~~~~~~~~~~~~~~~~NEW MODE~~~~~~~~~~~~~
    if($new){
      $tempTourID = "(SELECT MAX(id) FROM Tour)";

    }else{//~~~~~~~~~~~~~~~~~~~~~~~~EDIT MODE~~~~~~~~~~~~~
      $tempTourID = $id;
       $sql = "DELETE FROM Artwork_Tour WHERE TourID = '$tempTourID'";//deleting old artworks
       $insert_user = mysqli_query($con, $sql);
    }

    if(isset($_POST['checkbox']) && !empty($_POST['checkbox'])){//~~~~~~~~~~~Saving checked artworks~~~~~~~~~~~~~
     $chosenArt = $_POST['checkbox'];
      $N = count($chosenArt);

       for($i=0; $i < $N; $i++){
         $tempArtID = $chosenArt[$i];

          $sql = "INSERT INTO Artwork_Tour (ArtworkID, TourID) VALUES ($tempArtID, ($tempTourID))";
          $insert_user = mysqli_query($con, $sql);
          $insert_user || die("USER Database access failed: ".mysqli_error($con)); //if access fails -> error message
       }
    }
}

?>
