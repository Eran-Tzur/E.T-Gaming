<?php
require_once 'app/helpers.php';
sess_start('digg');

if (isset($_SESSION['user_id'])) {
  header('location: blog.php');
  exit;
}

$page_title = 'Sign up';
$error['name'] = $error['email'] = $error['password'] = '';

if (isset($_POST['submit'])) {

  if (isset($_POST['token']) && isset($_SESSION['csrf_token']) && $_POST['token'] == $_SESSION['csrf_token']) {
    
    $name = trim( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) );
    $email = trim( filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) );
    $password = trim( filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) );
    $cpassword = trim( filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING) );
    $form_valid = true;
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
    mysqli_query($link, "SET NAMES utf8");
    $name = mysqli_real_escape_string($link, $name);
    $email = mysqli_real_escape_string($link, $email);
    $password = mysqli_real_escape_string($link, $password);
    
    if( ! $name || mb_strlen($name) < 2 ){
      
      $error['name'] = '* Name is reuqired (min 2 chars)';
      $form_valid = false;
      
    }
    
    if( ! $email ){
      $error['email'] = '* A valid email is required';
      $form_valid = false;
    } elseif( email_exist($link, $email) ){
      $error['email'] = '* Email is taken';
      $form_valid = false; 
    }
    
    if( ! $password ){
      $error['password'] = '* Password is required';
      $form_valid = false; 
    } elseif($password != $cpassword){
      $error['password'] = '* Password Confirmation mismatch';
      $form_valid = false; 
    }
    
    if( $form_valid ){
      
      $file_name = 'default_avatar.png';
      
      if( isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0){
        
        $ex = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
        define('MAX_FILE_SIZE', 1024 * 1024 * 5);
        
        if( is_uploaded_file($_FILES['image']['tmp_name']) ){
          
          if( $_FILES['image']['size'] <= MAX_FILE_SIZE ){
            
              $fileinfo = pathinfo($_FILES['image']['name']);
              
              if( in_array( strtolower($fileinfo['extension']), $ex) ){
                
                $file_name = date('Y.m.d.H.i.s') . '-' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $file_name);
                
              }
            
          }
          
        }
        
      }
      
      $password = password_hash($password, PASSWORD_BCRYPT);
      $sql = "INSERT INTO users VALUES('','$name','$email','$password')";
      $result = mysqli_query($link, $sql);
      
      if( $result && mysqli_affected_rows($link) > 0 ){
        
        $uid = mysqli_insert_id($link);
        $sql = "INSERT INTO profile_images VALUES('',$uid,'$file_name')";
        $result = mysqli_query($link, $sql);
        
        if( $result && mysqli_affected_rows($link) > 0 ){
          
          header('location: signin.php?sm=You sign up, now you can sign in with your account');
          exit;
          
        }
        
      }
      
    }
    
  }

  $token = csrf_token();
} else {

  $token = csrf_token();
}
?>

<?php include 'tpl/header.php'; ?>
<main style="min-height: 800px;">
  <div class="container">
    <div class="row mt-5">
      <div class="col-sm-12">
        <h1 style="color:#E8EDDF" class="display-2 mb-4">Sign up</h1>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header" style="background-color:#E8EDDF">
            Here you can sign up for new account
          </div>
          <div class="card-body" style="background-color:#E8EDDF">
            <form action="" method="POST" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data">
              <input type="hidden" name="token" value="<?= $token; ?>">
              <div class="form-group">
                <label for="name"><span class="text-danger">*</span> Name:</label>
                <input value="<?= old('name'); ?>" type="text" id="name" name="name" class="form-control">
                <span class="text-danger"><?= $error['name']; ?></span>
              </div>
              <div class="form-group">
                <label for="email"><span class="text-danger">*</span> Email:</label>
                <input value="<?= old('email'); ?>" type="email" id="email" name="email" class="form-control">
                <span class="text-danger"><?= $error['email']; ?></span>
              </div>
              <div class="form-group">
                <label for="password"><span class="text-danger">*</span> Password:</label>
                <input type="password" id="password" name="password" class="form-control">
                <span class="text-danger"><?= $error['password']; ?></span>
              </div>
              <div class="form-group">
                <label for="confirm-password"><span class="text-danger">*</span> Confirm password:</label>
                <input type="password" id="confirm-password" name="confirm_password" class="form-control">
              </div>
              <div class="form-group">
                <label for="image">Profile image:</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                  </div>
                  <div class="custom-file">
                    <input name="image" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                  </div>
                </div>
              </div>
              <input type="submit" name="submit" value="Signup" class="btn btn-primary btn-block">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include 'tpl/footer.php'; ?>