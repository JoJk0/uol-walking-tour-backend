<?php

  require_once '/home/ud8462avu8pk/public_html/app/uol-walking-tour/modules/config.php';

?>
<div class="content">
  <div class="top">
    <div class="menu">
      <div class="entry active"><?php echo $_LANG['ARTWORK']; ?></div>
      <a href="?p=tours" class="entry"><?php echo $_LANG['TOURS']; ?></a>
      <?php
        If($_SESSION['su']) echo "<a href='?p=manageAdmin' class='entry'>Manage Admins</a>";
      ?>
    </div>
    <div class="actions">
      <a class="standard button" href="?p=settings">Settings</a>
      <a class="standard button" href="?logout"><?php echo $_LANG['SIGNOUT']; ?></a>
      <div class="button main with-icon" id="new-artwork"><i class="material-icons">add</i><?php echo $_LANG['NEW_ARTWORK']; ?></div>
    </div>
  </div>
  <div class="artwork-edit-bg">
    <div class="artwork-edit"></div>
  </div>
  <div class="artwork-list">
<?php
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
                        <div class="author">'.$_LANG['BY'].' '.$row['artist'].'</div>
                     </div>';
		      }else{
            echo 'ERROR: no image found for ID number: '.$row['id'];
          }

        }
  ?>
  </div>
</div>
<!-- JS FOR POPUP WINDOW -->
 <script type="text/javascript" src="/app/uol-walking-tour/js/jquery-3.3.1.min.js"></script>
 <script>
     // Listener if clicked
     $(".artwork").click(function(){

       // ID of the artwork
       var artworkID = $(this).attr("data-id");

       // Load page into container
       $(".artwork-edit").load("pages/viewArtwork.php?id="+artworkID);

       // Show the editor
       $(".artwork-edit-bg").css("display","flex");

     });

     // New artwork
     $("#new-artwork").click(function(){

       // Load page into container
       $(".artwork-edit").load("pages/viewArtwork.php?new");

       // Show the editor
       $(".artwork-edit-bg").css("display","flex");

     });

 </script>
