 <div class="content">
  <div class="top">
    <div class="menu">
      <div class="entry active">Artwork</div>
      <div class="entry">Tours</div>
    </div>
    <div class="actions">
      <a class="standard button" href="?logout">Sign Out</a>
      <div class="button main with-icon"><i class="material-icons">add</i>New artwork</div>
    </div>
  </div>
  <div class="artwork-list">
    <?php

    //~~~~~~~~~~~~Display artworks dynamicaly~~~~~~~~~~~~~~~~~~~~

        $queryAdmin = "SELECT id, title, artist, fileName FROM artworks2";
        $resultAdmin = mysqli_query($con,$queryAdmin); 
  
        while ($row = mysqli_fetch_array($resultAdmin)){
            $image = $row['fileName'];    
          
          if (!empty($image)){        
		           echo '<div class="artwork">
                        <img src="images/'.$image.'" alt="" />
                        <div class="edit-button"><i class="material-icons">edit_outline</i></div>
                        <div class="title">'.$row['title'].'</div>
                        <duv class="author">by '.$row['artist'].'</duv>
                     </div>';
		      }else{
            echo 'ERROR: no image found for ID number: '.$row['id'];  
          }                  

        }

?>
  
  </div>
</div>