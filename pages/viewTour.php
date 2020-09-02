<?php

  include '../modules/config.php';
$new = false;
if(isset($_GET['new'])){
  $new = true;
}

// If edit mode
if(!$new){

  $id = trim($_GET['id']);

  //showing saved user info
  $queryArt = "select * from Tour where id = $id";
  $resultArt = mysqli_query($con,$queryArt);
  $row = mysqli_fetch_array($resultArt);

  $name = $row['name'];
  $image = $row['fileName'];

  mysqli_free_result($resultArt); // free memory

} else{ // If new artwork mode

  $id = '';
  $name = '';

}


echo '
   <form action="modules/tour-save.php?id='.$id.'&new='.$new.'" method="post" enctype="multipart/form-data" id="view-tour">
	 	 <input type="file" accept="image/png" style="display: none" name="photoFile" id="photo-file" />
     <div id="artwork-delete-cnt">
        <div id="artwork-delete-confirmer"></div>
     </div>
     <div class="tour-view">
       <div class="top-bar">
          <div id="back"><i class="material-icons">close</i></div>
					<label for="photo-file" class="photo-select-tour-cnt">
						<div class="photo-select-tour">';
                if(!isset($_GET['new'])){ echo '
								  <div id="photo-file-button" style="background-image: url(\'/app/uol-walking-tour/images/tour/'.$image.'\')" title="Upload PNG icon"></div>';
                }else{  echo '
                  <div id="photo-file-button" style="background-image: url(\'/app/uol-walking-tour/images/tour/1.png\')" title="Upload PNG icon"></div>';
               }

								 echo ' <div class="title">'.$_LANG['TOUR_SELECT_ICON'].'</div>

						</div>
					</label>
          <input type="text" name="title" class="form-input title" value="'.$name.'" placeholder="'.$_LANG['INPUT_TITLE'].'" required />
          ';
          if(!isset($_GET['new'])) echo '<div class="button red" id="delete"><i class="material-icons">delete_outline</i></div>';
          echo '<input type="submit" id="save" name="save" class="button main" value="'.$_LANG['SAVE'].'" />
          <input type="hidden" name="save" value="" />
       </div>
       <div class="form tours">';

					// Get all of the artwork
					$q = "SELECT * FROM `artworks2` ORDER BY `title`";
					$iq = mysqli_query($con, $q);


					while($r = mysqli_fetch_array($iq)){

						// Get connection for selected tour
						$q2 = "SELECT * FROM `Artwork_Tour` WHERE `TourID` = '$id' AND `ArtworkID` = '".$r['id']."'";
						$iq2 = mysqli_query($con, $q2);

						if(mysqli_num_rows($iq2) != 0) {
							$checked = true;
						} else{
							$checked = false;
						}

					echo '<div class="artwork-in-tour" data-id="'.$r['id'].'">
										<div class="name">
												<span class="title">'.$r['title'].' </span>
												<span class="author">'.$_LANG['BY'].' '.$r['artist'].'</span>
										</div>
										<label class="container">
											<input type="checkbox" '; if($checked){ echo 'checked'; } echo ' name="checkbox[]" value="'.$r['id'].'">
											<span class="checkmark"></span>
										</label>
								</div>';

					}


            echo '

       </div>

      </div>
   </form>';
      ?>
<script type="text/javascript">
        //Closing editor
        $(function() {
          $("#back").click( function()
          {
            $(".tour-edit-bg").css("display","none");
            $(".tour-edit").html('');
          });
        });

    // Photo changer
    $("#photo-file").change(function(){
      console.log(this.files)
      var reader = new FileReader();

      reader.onload = function(event) {
        the_url = event.target.result;
        $('#photo-file-button').css("background-image", "url('"+the_url+"')");
        $(".edit i").text("image");
        $(".edit .txt").text("<?php echo $_LANG['INPUT_PHOTO_EDIT']; ?>");
        $(".edit.add").removeClass("add");
      }
      reader.readAsDataURL(this.files[0]);
    });

    // Form AJAX sender
    var frm = $('#view-tour');
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

                 $('body').append('<?php throw_success($_LANG["TOUR_SEND_SUCCESS"]) ?>');

                 // Close the window
                 $(".tour-edit-bg").css("display","none");
                 $(".tour-edit").html('');

                 // Refresh the list of artwork
                 $('body').html('');
                 $('body').load("pages/tours.php");

                 // Send success notification
                 $('body').append('<?php throw_success($_LANG["TOUR_SEND_SUCCESS"]) ?>');


               } else{
                 $('body').append('<?php throw_error($_LANG["TOUR_SEND_ERROR"]) ?>');
               }
            },
            error: function (data) {
                $('body').append('<?php throw_error($_LANG["TOUR_SEND_ERROR"]) ?>');
            },
        });

    });

  // Tour deleter
  $("#delete").click(function(){

      // Load map picker to div
      $("#artwork-delete-confirmer").load("<?php echo 'modules/deleteArtwork.php?delete_id='.$id.'&type=1'; ?>");

      // Show the picker
      $("#artwork-delete-cnt").css("display","flex");

  });

</script>
