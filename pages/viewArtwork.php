<?php

  include '../modules/config.php';
$new = false;
if(isset($_GET['new'])) $new = true;


// If edit mode
if(!$new){

  $id = trim($_GET['id']);

  //showing saved user info
  $queryArt = "select * from artworks2 where id = $id";
  $resultArt = mysqli_query($con,$queryArt);
  $row = mysqli_fetch_array($resultArt);

  $title = $row['title'];
	$artist = $row['artist'];
	$yearOfWork = $row['yearOfWork'];
	$info = $row['Information'];
	$image = $row['fileName'];
  $lat = $row['lat'];
  $long = $row['long'];
  $latlong = $row['lat'].",".$row['long'];
  $locname = $row['location'];
  $locnotes = $row['locationNotes'];

  mysqli_free_result($resultArt); // free memory
} else{ // If new artwork mode

  $id = '';
  $title = '';
	$artist = '';
	$yearOfWork = '';
	$info = '';
	$image = '';
  $lat = '53.405879';
  $long = '-2.965846';
  $latlong = '';
  $locname = '';
  $locnotes = '';

}


echo '
   <form action="modules/artwork-save.php?id='.$id.'&new='.$new.'" method="post" enctype="multipart/form-data" id="view-artwork">
     <div id="artwork-delete-cnt">
        <div id="artwork-delete-confirmer"></div>
     </div>
     <div id="map-picker-cnt">
        <div id="map-picker"></div>
     </div>
     <div class="artwork-view">
       <div class="top-bar">
          <div id="back"><i class="material-icons">close</i></div>
          <input type="text" name="title" class="form-input title" value="'.$title.'" placeholder="'.$_LANG['INPUT_TITLE'].'" required />
          ';
          if(!isset($_GET['new'])){echo '<div class="button red" id="delete"><i class="material-icons">delete_outline</i></div>';}
          echo '<input type="submit" id="save" name="save" class="button main" value="'.$_LANG['SAVE'].'" />
       </div>
       <div class="form">
         <label for="photo-file" class="photo" id="photo-chooser-button" class="photo" style="background-image: url(\'/app/uol-walking-tour/images/'.$image.'\');">';
              if(!isset($_GET['new'])){
                echo '
                <div class="edit">
                  <i class="material-icons">image</i>
                  <div class="txt">'.$_LANG['INPUT_PHOTO_EDIT'].'</div>
                </div>';
              } else{
                echo '
                <div class="edit add">
                  <i class="material-icons">add_photo_alternate</i>
                  <div class="txt">'.$_LANG['INPUT_PHOTO'].'</div>
                </div>';
              }
            echo '
         </label>
         <div class="inputs">
            <div class="form-input"><i class="material-icons">person_outline</i><input type="text" name="author" value="'.$artist.'" placeholder="'.$_LANG['INPUT_AUTHOR'].'" required /></div>
            <div class="form-input"><i class="material-icons">access_time</i><input type="number" name="year" value="'.$yearOfWork.'" min="0" max="'.date("Y").'" placeholder="'.$_LANG['INPUT_YEAR'].'" required /></div>
            <div class="form-input"><i class="material-icons">my_location</i><input type="text" name="coords" value="'.$latlong.'" placeholder="'.$_LANG['INPUT_COORDS'].'" id="coords" autocomplete="off"
required onkeypress="return false;"/></div>
            <div class="form-input"><i class="material-icons">place</i><input type="text" name="loc-name" value="'.$locname.'" placeholder="'.$_LANG['INPUT_LOCNAME'].'" required /></div>
            <div class="form-input"><i class="material-icons">navigation</i><input type="text" name="loc-notes" value="'.$locnotes.'" placeholder="'.$_LANG['INPUT_LOCNOTES'].'" required /></div>
            <!-- HIDDEN FILE INPUTS -->
            <input type="file" name="photoFile" id="photo-file" style="display: none" accept="image/*"/>
            <input type="hidden" name="save" value="" />
            <input type="hidden" name="id" value="'.$id.'" />
         </div>
       </div>

        <textarea name="info" class="form-input text" placeholder="'.$_LANG['INPUT_INFO'].'">'.$info.'</textarea>

      </div>
   </form>';
      ?>
<script type="text/javascript">
        //Closing editor
        $(function() {
          $("#back").click( function()
          {
            $(".artwork-edit-bg").css("display","none");
            $(".artwork-edit").html('');
          });
        });

    // Open map location picker
    $("#coords").click(function(){

      // Load map picker to div
      $("#map-picker").load("<?php echo 'modules/MapEditor.php?id='.$id.'&lat='.$lat.'&long='.$long; ?>");

      // Show the picker
      $("#map-picker-cnt").css("display","flex");

    });


    // Photo changer
    $("#photo-file").change(function(){
      console.log(this.files)
      var reader = new FileReader();

      reader.onload = function(event) {
        the_url = event.target.result;
        $('#photo-chooser-button').css("background-image", "url('"+the_url+"')");
        $(".edit i").text("image");
        $(".edit .txt").text("<?php echo $_LANG['INPUT_PHOTO_EDIT']; ?>");
        $(".edit.add").removeClass("add");
      }
      reader.readAsDataURL(this.files[0]);
    });

    // Form AJAX sender
    var frm = $('#view-artwork');
    frm.submit(function (e) {

        e.preventDefault();
        var formData = new FormData(frm[0]);

        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: formData,
            async: false,
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
              console.log(data);
               if(data == '1'){

                 $('body').append('<?php throw_success($_LANG["ARTWORK_SEND_SUCCESS"]) ?>');

                 // Close the window
                 $(".artwork-edit-bg").css("display","none");
                 $(".artwork-edit").html('');

                 // Refresh the list of artwork
                 $('body').html('');
                 $('body').load("pages/main.php");

                 // Send success notification
                 $('body').append('<?php throw_success($_LANG["ARTWORK_SEND_SUCCESS"]) ?>');
               } else if(data == '3'){
                 $('body').append('<?php throw_error($_LANG["ARTWORK_IMAGE_ERROR"]) ?>');

               } else{
                 $('body').append('<?php throw_error($_LANG["ARTWORK_SEND_ERROR"]) ?>');
               }
            },
            error: function (data) {
                $('body').append('<?php throw_error($_LANG["ARTWORK_SEND_ERROR"]) ?>');
            },
        });

    });

  // Artwork deleter
  $("#delete").click(function(){

      // Load map picker to div
      $("#artwork-delete-confirmer").load("<?php echo 'modules/deleteArtwork.php?delete_id='.$id.'&type=0'; ?>");

      // Show the picker
      $("#artwork-delete-cnt").css("display","flex");

  });

</script>
