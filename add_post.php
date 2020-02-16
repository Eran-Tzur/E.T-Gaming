<?php
require_once 'app/helpers.php';
sess_start('digg');

if( ! user_verify() ){
  
  header('location: signin.php');
  exit;
  
}

$error = '';
$page_title = 'Add post page';

if( isset($_POST['submit']) ){
  
  $title = trim( filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) );
  $article = trim( filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) );
  
  if( ! $title || mb_strlen($title) < 2 || mb_strlen($title) > 255){
    
    $error = '* Title field is required (min 2 chars, max 255)';
    
  } elseif( ! $article || mb_strlen($article) < 2 ){
    
    $error = '* Article field is required (min 2 chars)';
    
  } else {
    
    $uid = $_SESSION['user_id'];
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
    mysqli_query($link, "SET NAMES utf8");
    $title = mysqli_real_escape_string($link, $title);
    $article = mysqli_real_escape_string($link, $article);
    $sql = "INSERT INTO posts VALUES('',$uid,'$title','$article',NOW(),NOW())";
    $result = mysqli_query($link, $sql);
    
    if( $result && mysqli_affected_rows($link) > 0 ){
      
      header('location: blog.php?sm=Your post created successfuly');
      exit;
      
    }
    
  }
  
}


?>

<?php include 'tpl/header.php'; ?>
<main style="min-height: 800px;">
  <div class="container">
    <div class="row mt-5">
      <div class="col-sm-12">
        <h1> Add post page </h1>
        <p>
          <a href="/blog.php"><i class="fas fa-chevron-circle-left"></i> Back</a>
        </p>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-sm-6">
        <div class="card" style="background-color:#CFDBD5">
          <div class="card-header">
            Add your new post
          </div>
          <div class="card-body">
            <form action="" method="POST" novalidate="novalidate" autocomplete="off">
              <div class="form-group">
                <label for="title"><span class="text-danger">*</span> Title:</label>
                <input value="<?= old('title'); ?>" type="text" id="title" name="title" class="form-control" style="background-color:#E8EDDF">
              </div>
              <div class="form-group">
                <label for="article"><span class="text-danger">*</span> Article:</label>
                <textarea class="form-control" name="article" id="article" rows="7" style="background-color:#E8EDDF"><?= old('article'); ?></textarea>
              </div>
              <input type="submit" name="submit" value="Post" class="btn btn-primary btn-block">
              <?php if( $error ): ?>
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