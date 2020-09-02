<?php 

  require_once 'config.php';

  if(!isset($_GET['delete_id']) || !$_SESSION['is_logged']){
    echo 'ERROR: UNAUTHORIZED USE OF SCRIPT'; //ERROR CHECK
  } else{
    $id = $_GET['delete_id'];	//assign get variables
    $type = $_GET['type'];
    if($type==0) $goTo = "pages/main.php";
    if($type==1) $goTo = "pages/tours.php";
   
    echo '<div class="window-prompt">'.$_LANG['ARTWORK_DELETE_CONFIRM'].'</div>
          <div class="decision">
            <div class="button standard" id="delete_cancel">'.$_LANG['ARTWORK_DELETE_CANCEL'].'</div>
            <div class="button main" id="delete_confirm">'.$_LANG['ARTWORK_DELETE'].'</div>
          </div>';
  }
    

?>

<script type="text/javascript">
  // Cancel deletion
  $("#delete_cancel").click(function(){
    // Close the window
    $("#artwork-delete-cnt").css("display","none");
    $("#artwork-delete-confirmer").html('');
    
  });
  
  // Confirm deletion
  $("#delete_confirm").click(function(){
    
    // Send ID to deleter
    $.ajax({
            type: 'POST',
            url: 'modules/artwork-delete.php?delete_id=<?php echo $id; ?>&type=<?php echo $type; ?>',
            data: "delete_id=<?php echo $_GET['delete_id']; ?>",
            success: function (data) {
              console.log(data);
               if(data == '1'){
                 
                 // Close the deleter
                 $(".artwork-delete-cnt").css("display","none");
                 $(".artwork-delete-confirmer").html('');
                 
                
                 // Close editor
                 $(".artwork-edit-bg").css("display","none");
                 $(".artwork-edit").html('');
                 
                 // Refresh the list of artwork
                 $('body').html('');
                 $('body').load('<?php echo $goTo; ?>');
                 
                 // Send success notification
                 $('body').append('<?php throw_success($_LANG["ARTWORK_DELETE_SUCCESS"]) ?>');
                 
                 
               } else{
                 $('body').append('<?php throw_error($_LANG["ARTWORK_DELETE_ERROR"]) ?>');
               }
            },
            error: function (data) {
                $('body').append('<?php throw_error($_LANG["ARTWORK_DELETE_ERROR"]) ?>');
            },
        });
    
  });
</script>