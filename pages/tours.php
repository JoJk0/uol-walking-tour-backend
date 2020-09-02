<?php

  require_once '/home/ud8462avu8pk/public_html/app/uol-walking-tour/modules/config.php';

?>
<div class="content">
  <div class="top">
    <div class="menu">
      <a href="?" class="entry"><?php echo $_LANG['ARTWORK']; ?></a>
      <div class="entry active"><?php echo $_LANG['TOURS']; ?></div>
       <?php
        If($_SESSION['su']) echo "<a href='?p=manageAdmin' class='entry'>Manage Admins</a>";
      ?>
    </div>
    <div class="actions">
      <a class="standard button" href="?p=settings">Settings</a>
      <a class="standard button" href="?logout"><?php echo $_LANG['SIGNOUT']; ?></a>
      <div class="button main with-icon" id="new-tour"><i class="material-icons">add</i><?php echo $_LANG['NEW_TOUR']; ?></div>
    </div>
  </div>
  <div class="tour-edit-bg">
    <div class="tour-edit"></div>
  </div>
  <div class="artwork-list">
<?php
    //~~~~~~~~~~~~Display tours dynamicaly~~~~~~~~~~~~~~~~~~~~

        $queryAdmin = "SELECT * FROM Tour";
        $resultAdmin = mysqli_query($con,$queryAdmin);

        while ($row = mysqli_fetch_array($resultAdmin)){

		           echo '<div class="tour" data-id="'.$row['id'].'">
                        <div class="top-img">
                          <img src="images/tour/'.$row['fileName'].'" alt="" />
                        </div>
                        <div class="edit-button"><i class="material-icons">edit_outline</i></div>
                        <div class="title">'.$row['name'].'</div>
                     </div>';

        }
  ?>
  </div>
</div>
<!-- JS FOR POPUP WINDOW -->
 <script type="text/javascript" src="/app/uol-walking-tour/js/jquery-3.3.1.min.js"></script>
 <script>
     // Listener if clicked
     $(".tour").click(function(){

       // ID of the tour
       var tourID = $(this).attr("data-id");

       // Load page into container
       $(".tour-edit").load("pages/viewTour.php?id="+tourID);

       // Show the editor
       $(".tour-edit-bg").css("display","flex");

     });

     // New tour
     $("#new-tour").click(function(){

       // Load page into container
       $(".tour-edit").load("pages/viewTour.php?new");

       // Show the editor
       $(".tour-edit-bg").css("display","flex");

     });

 </script>
