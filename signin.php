<?php

require_once 'app/helpers.php';
sess_start('digg');

if (isset($_SESSION['user_id'])) {
  header('location: /blog.php');
  exit;
}

$page_title = 'Sign in';
$error = '';

if (isset($_POST['submit'])) {

  if (isset($_POST['token']) && isset($_SESSION['csrf_token']) && $_POST['token'] == $_SESSION['csrf_token']) {

    $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    if (!$email) {

      $error = ' * A valid email is required';
    } elseif (!$password) {

      $error = '* Password is reuqired';
    } else {

      $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
      mysqli_query($link, "SET NAMES utf8");
      $email = mysqli_real_escape_string($link, $email);
      $password = mysqli_real_escape_string($link, $password);
      $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
      $result = mysqli_query($link, $sql);

      if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);
        
        if(password_verify($password, $user['password']) ){
          
          $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user_name'] = $user['name'];
          header('location: /blog.php?sm= ' . $user['name'] . ', welcome back!');
          exit;
          
        } else {
          
          $error = '* Wrong email and password';
          
        }

      } else {
        $error = '* Wrong email and password';
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
        <h1 style="color:#E8EDDF" class="display-2 mb-4">Sign in</h1>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header" style="background-color:#E8EDDF">
            Here you can sign in with your account
          </div>
          <div class="card-body"  style="background-color:#E8EDDF">
            <form action="" method="POST" novalidate="novalidate" autocomplete="off">
              <input type="hidden" name="token" value="<?= $token; ?>">
              <div class="form-group">
                <label for="email"><span class="text-danger">*</span> Email:</label>
                <input value="<?= old('email'); ?>" type="email" id="email" name="email" class="form-control">
              </div>
              <div class="form-group">
                <label for="password"><span class="text-danger">*</span> Password:</label>
                <input type="password" id="password" name="password" class="form-control">
              </div>
              <input type="submit" name="submit" value="Signin" class="btn btn-primary btn-block">
<?php if ($error): ?>
                <div class="alert alert-danger mt-3"><?= $error; ?></div>
<?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
              <?php include 'tpl/footer.php'; ?>