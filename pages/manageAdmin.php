<?php
  require_once '/home/ud8462avu8pk/public_html/app/uol-walking-tour/modules/config.php';


  if(!$_SESSION['su']){
    include('modules/login.php');
  } else{ 
    if(isset($_POST['accept'])){  //ACCEPT
      $id = $_POST['accept'];

       $sql = "UPDATE Admin SET `deleted` = 0, `verified` = 1 WHERE AdminID = '$id'";
      $insert_user = mysqli_query($con, $sql);

      emailUser($id, $con);
    	throw_success($_LANG['SU_ADMIN_APPR_SUCCESS']);

    } else if(isset($_POST['decline'])){  //DECLINE
      $id = $_POST['decline'];

      $sql = "UPDATE Admin SET `deleted` = 1, `verified` = 1 WHERE AdminID = '$id'";
      $insert_user = mysqli_query($con, $sql);
    	throw_success($_LANG['SU_ADMIN_REJECT_SUCCESS']);

    } else if(isset($_POST['deact'])){  //DEACTIVATE
      $id = $_POST['deact'];

      $sql = "UPDATE Admin SET `deleted` = 1 WHERE AdminID = '$id'";
      $insert_user = mysqli_query($con, $sql);
    	throw_success($_LANG['SU_ADMIN_DEACT_SUCCESS']);

    } else if(isset($_POST['restore'])){ //ACTIVATE
      $id = $_POST['restore'];

       $sql = "UPDATE Admin SET `deleted` = 0, `verified` = 1 WHERE AdminID = '$id'";
      $insert_user = mysqli_query($con, $sql);
    	throw_success($_LANG['SU_ADMIN_ACT_SUCCESS']);

    } else if(isset($_POST['delete'])){ //DELETE FROM DATABASE
      $id = $_POST['delete'];

      $delete = "DELETE FROM Admin WHERE AdminID = '$id'"; //deleting from artworks2 or tour table
    	$result = mysqli_query($con,$delete);

    	throw_success($_LANG['SU_ADMIN_DEL_SUCCESS']);

    }


    function emailUser($id, $con){

       $queryAdmin = "SELECT Username, Firstname, Surname FROM Admin WHERE AdminID = '$id'";
       $resultAdmin = mysqli_query($con,$queryAdmin);

       while ($row = mysqli_fetch_array($resultAdmin)){
         $msg = "Dear ".$row['Firstname'].",<br>Your registration has been successfully approved.<br>
         You may login into the Database Manager using your email and password<br>Link: <a href='http://dacarprestige.co.uk/app/uol-walking-tour'>http://dacarprestige.co.uk/app/uol-walking-tour</a><br>Thank you.";
         $msg = wordwrap($msg, 70);
         $to = $row['Username'];
          phpMailer($msg, $to, $row['Firstname'], $row['Surname']);

       }
    }

    function phpMailer($msg, $to, $firstname, $surname){
      $subject = "Your access request has been approved";
      // To send HTML mail, the Content-type header must be set
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=utf-8';

      // Additional headers
      $headers[] = 'To: '.$firstname.' '.$surname.' <'.$to.'>';
      $headers[] = 'From: UoL Walking Tour App <donotreply@uol-walking-tour.co.uk>';

      mail($to,$subject,$msg,implode("\r\n", $headers));
    }


    echo '<form action="" method="POST" enctype="multipart/form-data" id="adminForm">
       <link rel="stylesheet" href="css/main.css" />
    <div class="content">
      <div class="top">
        <div class="menu">
          <a href="?" class="entry">'.$_LANG['ARTWORK'].'</a>
          <a href="?p=tours" class="entry">'.$_LANG['TOURS'].'</a>';

            If($_SESSION['su']) echo "<div class='entry active'>Manage Admins</div>";

        echo '</div>
        <div class="actions">
           <a class="standard button" href="?p=settings">Settings</a>
          <a class="standard button" href="?logout">'.$_LANG['SIGNOUT'].'</a>
        </div>
      </div>
    	<div class="page">

      <div class="tour-edit-bg">
        <div class="tour-edit"></div>
      </div>';


        //~~~~~~~~~~~~Display UNVERIFIED Admins~~~~~~~~~~~~~~~~~~~~
            $queryAdmin = "SELECT * FROM Admin WHERE verified=0";
            $resultAdmin = mysqli_query($con,$queryAdmin);

    				if(mysqli_num_rows($resultAdmin) > 0){
    					echo '<div class="pending-title">'.$_LANG['SU_PENDING_ADMINS'].'</div>';
    				}

    	  echo '<div class="pending-admins-list">';

            while ($row = mysqli_fetch_array($resultAdmin)){


              echo '<div class="admin" data-id="'.$row['AdminID'].'">
    					  				<span class="username">'.$row['Username'].' </span>


                        <button type="submit" name="accept" class="admin-button accept" value="'.$row['AdminID'].'" title="Accept"><i class="material-icons">check_circle_outline</i></button>

                        <button type="submit" name="decline" class="admin-button decline" value="'.$row['AdminID'].'" title="Decline"><i class="material-icons">highlight_off</i></button>
    								</div>';
            }

    		echo '</div>
    		<div class="admins-title">'.$_LANG['SU_ACTIVE_ADMINS'].'</div>
    		<div class="admins-list">';


      //~~~~~~~~~~~~Display VERIFIED Admins (Except Super Admin)~~~~~~~~~~~~~~~~~~~~
            $queryAdmin = "SELECT * FROM Admin WHERE verified=1 AND deleted=0 AND AdminID <> 1";
            $resultAdmin = mysqli_query($con,$queryAdmin);

            while ($row = mysqli_fetch_array($resultAdmin)){
    		           echo '<div class="admin" data-id="'.$row['AdminID'].'">
    												<span class="username">'.$row['Username'].' </span>
                            <button type="submit" name="deact" class="admin-button decline" value="'.$row['AdminID'].'" title="Deactivate admin"><i class="material-icons">power_settings_new</i></button>
    								</div>';
            }


    		echo '</div>';

    		    $queryAdmin = "SELECT * FROM Admin WHERE deleted=1";
            $resultAdmin = mysqli_query($con,$queryAdmin);

    				if(mysqli_num_rows($resultAdmin) > 0){
    					echo '<div class="pending-title">Inactive Admins</div>';
    				}

    		echo '<div class="admins-list">';


      //~~~~~~~~~~~~Display DELETED Admins (Except Super Admin)~~~~~~~~~~~~~~~~~~~~
            $queryAdmin = "SELECT * FROM Admin WHERE deleted=1 AND AdminID <> 1";
            $resultAdmin = mysqli_query($con,$queryAdmin);

            while ($row = mysqli_fetch_array($resultAdmin)){
    		           echo '<div class="admin" data-id="'.$row['AdminID'].'">
    												<span class="username">'.$row['Username'].' </span>
                            <button type="submit" name="restore" class="admin-button accept" value="'.$row['AdminID'].'" title="Activate admin"><i class="material-icons">play_circle_outline</i></button>
                            <button type="submit" name="delete" class="admin-button decline" value="'.$row['AdminID'].'" title="Delete admin" onclick="return confirm(\''.$_LANG['SU_ADMIN_DELETE_CONFIRM'].'\');"><i class="material-icons">highlight_off</i></button>
    								</div>';
            }


    echo '
      	</div>
    		</div>


    </form>';

  }
?>
