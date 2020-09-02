    <div id="login">
      <img src="images/logo-s.png" alt="UoL Artwork"/>
      <div class="title">UoL Artwork</div>
      
      <?php
      
      if(isset($_POST["submit_reg"])){
        $reg = true;
      } else{
        $reg = false;
      }
      
// Register action
if($reg){
  
  if(@$_POST['email'] != '' && @$_POST['firstname'] != '' && @$_POST['lastname'] != '' && @$_POST['password'] != '' && filter_var(@$_POST['email'], FILTER_VALIDATE_EMAIL)){
    
    // Check if username is taken
    if(!mysqli_fetch_row(mysqli_query($con, "SELECT count(*) FROM `Admin` WHERE `Username` = '".$_POST['email']."'"))[0]){
      
      // Register the user
      $post_name = htmlspecialchars($_POST["firstname"]);
      $post_lastname = htmlspecialchars($_POST["lastname"]);
      $post_email = htmlspecialchars($_POST["email"]);
      $post_password = $_POST["password"];

      // Hash the password
      $hashed = password_hash($post_password, PASSWORD_DEFAULT);
  
      // Create new record
      $sql =  "INSERT INTO `Admin` (`AdminID`, `Username`, `Password`, `Firstname`, `Surname`, `verified`, `deleted`) 
      VALUES (NULL, '$post_email', '$hashed', '$post_name', '$post_lastname', 0, 0)";
      $insert_user = mysqli_query($con, $sql);
      
      // Send the confirmation email
      $subject = 'Welcome to UoL Artwork Tour App!';
      $me_name = 'UoL Artwork Walking Tour App';
      $me_email = 'donotreply@artwork.liv.ac.uk';

      
      // Display success message
      echo '<div class="reg_info">'.$_LANG['REG_INFO'].'</div>';
      
      
    } else{
      
      // Username taken already
      $reg = false;
      throw_error($_LANG['REG_USERNAME_TAKEN']);
      
    }

  } else{
    
    // Not met form requirements
    $reg = false;
    throw_error($_LANG['REG_OOPS']);
    
  }
	
} 

// Register form
if(!$reg){
  
  echo '      
      <form action="?p=register" method="POST">
        <input type="email" placeholder="'.$_LANG['REG_EMAIL'].'" name="email" required class="input"/>
        <input type="firstname" placeholder="'.$_LANG['REG_FIRSTNAME'].'" name="firstname" required class="input"/>
        <input type="lastname" placeholder="'.$_LANG['REG_LASTNAME'].'" name="lastname" required class="input"/>
        <input type="password" placeholder="'.$_LANG['LOGIN_PASSWORD'].'" name="password" id="password" required class="input"/>
        <input type="password" placeholder="'.$_LANG['REG_REPASSWORD'].'" name="repassword" id="confirm_password" required class="input"/>
        <input type="submit" name="submit_reg" value="'.$_LANG['REG_BUTTON'].'" class="button main centered" />
      </form>
      <a href="?">'.$_LANG['REGISTER_LOGIN'].'</a>';
  
}
?>

      <div class="copyright">© 2018 UoL Artwork</div>
    </div>

    <script>

    function validatePassword(){
      var password = document.getElementById("password");
      var confirm_password = document.getElementById("confirm_password");

      if(password.value != confirm_password.value) {
        confirm_password.setCustomValidity('Focus! Passwords are not identical :/');
      }
      else{
        confirm_password.setCustomValidity('');
      }
    }
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
    </script>


