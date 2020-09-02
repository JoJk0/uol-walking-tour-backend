<?php

  require_once 'config.php';
if(!$_SESSION['is_logged']){
  echo 'ERROR: UNAUTHORIZED USE OF SCRIPT';	//ERROR check
} else{

    //~~~~~~~~~~~~Display artworks dynamicaly~~~~~~~~~~~~~~~~~~~~

        $queryAdmin = "SELECT id, title, artist, fileName FROM artworks2";
        $resultAdmin = mysqli_query($con,$queryAdmin); 
  
        while ($row = mysqli_fetch_array($resultAdmin)){
            $image = $row['fileName'];    
          
          if (!empty($image)){        
		           echo '<div class="artwork" data-id="'.$row['id'].'">
                        <img src="images/'.$image.'" alt="" />
                        <div class="edit-button"><i class="material-icons">edit_outline</i></div>
                        <div class="title">'.$row['title'].'</div>
                        <duv class="author">by '.$row['artist'].'</duv>
                     </div>';
		      }else{
            echo 'ERROR: no image found for ID number: '.$row['id'];  
          }                  

        }
}
?>