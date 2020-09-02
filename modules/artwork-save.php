<?php
//SAVING ARTWORK TO DATABASE PHP FILE
include '../modules/config.php';

if(!isset($_POST['save']) || !$_SESSION['is_logged']){
  echo 'ERROR: UNAUTHORIZED USE OF SCRIPT'; //ERROR check
} else{
  $new = $_GET['new']; //assign get variables
  $id = $_GET['id'];
	$filename = strtolower(str_replace(" ", "", str_replace("-", "", $_FILES['photoFile']['name'])));
  $post_title = htmlspecialchars($_POST['title']);		//get post variables
	$post_artist = htmlspecialchars($_POST['author']);
	$post_yearOfWork = htmlspecialchars($_POST['year']);
	$post_info = htmlspecialchars($_POST['info']);
  $post_photo = $filename;
  $post_latlong = htmlspecialchars($_POST['coords']);
  $divideLoc = explode(",", $post_latlong);
  $post_lat = htmlspecialchars($divideLoc[0]);
  $post_long = htmlspecialchars($divideLoc[1]);
  $post_locname = htmlspecialchars($_POST['loc-name']);
  $post_locnotes = htmlspecialchars($_POST['loc-notes']);

if(!$new){ //~~~~~~~~~~~~~~~~~~~~~~~~EDIT MODE~~~~~~~~~~~~~
  if(empty($post_photo)){
    //no new photo
    $sql = "UPDATE artworks2 SET `title` = '$post_title', `artist` = '$post_artist', `yearOfWork` ='$post_yearOfWork',
    `Information` ='$post_info', `lat` = '$post_lat', `long` = '$post_long' , `location` = '$post_locname', `locationNotes` = '$post_locnotes' WHERE id = '$id'";

  }else{//if new photo
    //update new photo
   $sql = "UPDATE artworks2 SET `title` = '$post_title', `artist` = '$post_artist', `yearOfWork` ='$post_yearOfWork',
   `Information` ='$post_info', `lat` = '$post_lat', `long` = '$post_long', `location` = '$post_locname', `locationNotes` = '$post_locnotes', `fileName` = '$post_photo' WHERE id = '$id'";
    saveToDir();//saving new photo 
  }

}else{ //~~~~~~~~~~~~~~~NEW ARTWORK MODE~~~~~~~~~~~
  if(empty($post_photo)){
    //no new photo uploaded
   $sql = "INSERT INTO artworks2 (`title`, `artist`, `yearOfWork`, `Information`, `lat`, `long`, `location`, `locationNotes`, `fileName`)
  VALUES ('$post_title','$post_artist','$post_yearOfWork', '$post_info', '$post_lat', '$post_long', '$post_locname', '$post_locnotes', 'noImageUploaded.png')";

  }else{//if new photo
    //insert new photo
  $sql = "INSERT INTO artworks2 (`title`, `artist`, `yearOfWork`, `Information`, `lat`, `long`, `location`, `locationNotes`, `fileName`)
  VALUES ('$post_title','$post_artist','$post_yearOfWork', '$post_info', '$post_lat', '$post_long', '$post_locname', '$post_locnotes', '$post_photo')";

  saveToDir();//saving new photo 
  }
}

  if(checkType($post_photo)){
    $insert_user = mysqli_query($con, $sql);
    $insert_user || die("USER Database access failed: ".mysqli_error($con)); //if access fails -> error message
    echo '1';//CORRECT TYPE, DO QUERY
  }else{
    echo '3';//INCORRECT TYPE
  }
}


//~~~~~~~~~~~~~~FUNCTION TO CHECK IMAGE TYPE~~~~~~~~~~~~~~~~~~~~~~~~~~~
function checkType($post_photo){
  $file_type = $_FILES['photoFile']['type']; //returns the mimetype

  $allowed = array("image/jpeg", "image/gif", "image/png");
  if(in_array($file_type, $allowed)){
    return true;
    echo "one";
  }else if(empty($post_photo)){
    return true;
    echo "one2";
  }else{
    return false;
    echo "one3";
  }
}

//~~~~~~~~~~~~~~FUNCTION TO SAVE FILE TO DIRECTORY~~~~~~~~~~~~~~~~~~~~~~~~~~~
function saveToDir(){
	$filename = $_FILES['photoFile']['name'];
	$filename = strtolower(str_replace(" ", "", str_replace("-", "", $filename)));
   $target = '/home/ud8462avu8pk/public_html/app/uol-walking-tour/images/'.$filename;  
    //Save image to directory (if it isn't there already)
    if(!file_exists($target)) move_uploaded_file( $_FILES['photoFile']['tmp_name'], $target);
}

?>
