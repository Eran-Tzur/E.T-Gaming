<?php

require_once 'app/helpers.php';
sess_start('digg');
$page_title = 'Home Page';

?>

<?php include 'tpl/header.php'; ?>
<main style="min-height: 800px;">
  <div class="container">
    <div class="row mt-5">
      <div class="col-sm-7 m-auto text-center" style="color:#E8EDDF">
        <h1 class="display-2 mb-4">E.T Gaming blog</h1>
        <p class="col-sm-10 m-auto text-center">E.T Gaming offers a lot of posts a day, getting news stories up at the drop of a hat, and chasing down exclusive info whenever possible. An essential source for anyone interested in gaming.</p>
        <p><a href="/blog.php" class="btn btn-outline-warning btn-lg mt-5">START NOW!</a></p>
      </div>
     <div class="m-auto img-sm"></div>
    </div>
  </div>
</main>
<?php include 'tpl/footer.php'; ?>