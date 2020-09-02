<!-- LOGIN PAGE -->

    <div id="login">
      <img src="images/logo-s.png" alt="UoL Artwork"/>
      <div class="title">UoL Artwork</div>
      <form action="?" method="POST" enctype="multipart/form-data">
        <input type="email" placeholder="<?php echo $_LANG['LOGIN_EMAIL']; ?>" name="email" required class="input"/>
        <input type="password" placeholder="<?php echo $_LANG['LOGIN_PASSWORD']; ?>" name="password" required class="input"/>
        <input type="submit" name="submit" value="<?php echo $_LANG['LOGIN_BUTTON']; ?>" class="button-main">
      </form>
      <a href="?p=register"><?php echo $_LANG['LOGIN_REGISTER']; ?></a>
      <div class="copyright">© 2018 UoL Artwork</div>
    </div>
	
<?php	

// Logging script
if(isset($_POST["submit"])){
		$post_login_email = $_POST["email"];
		$post_login_password = $_POST["password"];

		$queryAdmin = "SELECT Username FROM Admin"; 
		$resultAdmin = mysqli_query($con,$queryAdmin); 
		while ($row = mysqli_fetch_array($resultAdmin)){

			if ($row['Username'] == $post_login_email){ //IF email accepted

				$queryPassword = "SELECT AdminID, Password, verified FROM Admin WHERE Username = '$post_login_email' ";
				$resultPassword = mysqli_query($con,$queryPassword); 
				
				
				while($row = mysqli_fetch_assoc($resultPassword)){
          
					if (password_verify($post_login_password, $row['Password'])){	//IF password accepted
            
            if($row['verified'] == 1){ // If user accepted by superuser
              
              // Logged in successfully
              $_SESSION['is_logged'] = TRUE;
              $_SESSION['username'] = $post_login_email;
              $_SESSION['username_id'] = $row['AdminID'];
              if($post_login_email == $_CONFIG['su_username']){
                $_SESSION['su'] = TRUE; // Set the Super User flag
              } else{
                $_SESSION['su'] = FALSE;
              }

              header('Location: index.php');
              exit;
              
            } else{
              throw_error($_LANG['LOGIN_USER_NOT_VERIFIED']);
            }
				
					}else{
            throw_error($_LANG['LOGIN_INVALID_CREDENTIALS']);
					}
				}
			} else{
        throw_error($_LANG['LOGIN_INVALID_CREDENTIALS']);
      }
		}
		
}


?>
