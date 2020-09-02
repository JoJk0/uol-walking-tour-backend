<?php
  require_once '/home/ud8462avu8pk/public_html/app/uol-walking-tour/modules/config.php';

  $id = $_SESSION['username_id'];

        $queryAdmin = "SELECT * FROM Admin WHERE AdminID='$id'";
        $resultAdmin = mysqli_query($con,$queryAdmin);

				while ($row = mysqli_fetch_array($resultAdmin)){
          $fname = $row['Firstname'];
          $lname = $row['Surname'];
          $username = $row['Username'];
          $password = $row['Password'];
        }

 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~UPDATING EMAIL AND NAMES~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       if(isset($_POST["update"])){
              $reg = true;
            } else{
              $reg = false;
            }

      if($reg){

        if($_POST['email'] != '' && $_POST['firstname'] != '' && $_POST['lastname'] != '' && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

            // UPDATE the user
            $post_name = htmlspecialchars($_POST["firstname"]);
            $post_lastname = htmlspecialchars($_POST["lastname"]);
            $post_email = htmlspecialchars($_POST["email"]);

            // Update record
            $sql = "UPDATE Admin SET `Username` = '$post_email', `Firstname` = '$post_name', `Surname` = '$post_lastname' WHERE AdminID = '$id'";
            $insert_user = mysqli_query($con, $sql);

            // Display success message

           header('Location: '.$_SERVER['REQUEST_URI']);
           // throw_success("Changed Successfully!");

        } else{

          // Not met form requirements
          $reg = false;
          throw_error($_LANG['UPDATE_OOPS']);

        }
      }


 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~UPDATING PASSWORD~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if(isset($_POST["updatePass"])){
  $oldPass = htmlspecialchars($_POST['oldPass']);
  $newPass = $_POST['newPass'];
  $confirmPass = $_POST['confirmPass'];

      if($oldPass != '' && $newPass != '' && $confirmPass != ''){
           if (password_verify($oldPass, $password)) {

                   if (strcmp($newPass, $confirmPass) == 0){  //~~~CORRECT PASSWORD

                     // Hash the password
                      $hashed = password_hash($newPass, PASSWORD_DEFAULT);

                     // Update record
                    $sql = "UPDATE Admin SET `Password` = '$hashed' WHERE AdminID = '$id'";
                    $insert_user = mysqli_query($con, $sql);
                    throw_success("Password Changed Successfully!");

                   }else{
                      throw_error('Passwords must be identical');
                   }
              } else {
                  throw_error('Old Password is Incorrect!');
              }

      } else {
             throw_error('Empty Password Fields!');
      }

}


?>
<form action="" method="POST" enctype="multipart/form-data" id="settingsForm">
   <link rel="stylesheet" href="css/main.css" />
<div class="content">
  <div class="top">
    <div class="menu">
      <a href="?" class="entry"><?php echo $_LANG['ARTWORK']; ?></a>
      <a href="?p=tours" class="entry"><?php echo $_LANG['TOURS']; ?></a>
      <?php
        If($_SESSION['su']) echo "<a href='?p=manageAdmin' class='entry'>Manage Admins</a>";
      ?>

    </div>
    <div class="actions">
      <a class="standard button" href="?logout"><?php echo $_LANG['SIGNOUT']; ?></a>
    </div>
  </div>
	<div class="page settings">
		<?php echo '
		<div class="settings-user">
			<div class="pending-title">User settings</div>
			<input type="text" class="input" name="firstname" placeholder="'.$_LANG['REG_FIRSTNAME'].'" value="'.$fname.'">
			<input type="text" class="input" name="lastname" placeholder="'.$_LANG['REG_LASTNAME'].'" value="'.$lname.'">
			<input type="text" class="input" name="email" placeholder="'.$_LANG['REG_EMAIL'].'" value="'.$username.'">
			<input type="submit" name="update" value="Update" class="button main">
		</div>

		<div class="settings-user">
			<div class="pending-title">Change password</div>
			<input type="password" class="input" name="oldPass" id="old_password" placeholder="'.$_LANG['LOGIN_PASSWORD'].'" value="">
			<input type="password" class="input" name="newPass" id="password" placeholder="'.$_LANG['SETTINGS_NEW_PASSWORD'].'" value="">
			<input type="password" class="input" name="confirmPass" id="confirm_password" placeholder="'.$_LANG['SETTINGS_CONFIRM_PASSWORD'].'" value="">
			<input type="submit" name="updatePass" class="button main" value="Update">
		</div>
  </div>
    <div class="msg-admin">
      <div class="pending-title">Contact Us</div>
			<p style="text-align:center">
      If you have any questions, please contact us by sending an email to:
      <a href="mailto:uolwalkingtour@gmail.com?Subject=Help" target="_top">uolwalkingtour@gmail.com</a>
      </p>
		</div>

		';
    ?>


</form>
