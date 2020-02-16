<?php
require_once 'app/helpers.php';
sess_start('digg');
$page_title = 'About us';
?>

<?php include 'tpl/header.php'; ?>
<main style="min-height: 800px;">
  <div class="container">
    <div class="row mt-5">
      <div class="col-sm-12">
        <h1 style="color:#E8EDDF" class="display-2 mb-4 text-center">E.T Gaming blog</h1>
        <p class="mt-3 col-sm-8 m-auto text-center" style="color:#E8EDDF">a news and opinion site about games and things serious gamers care about. We’re here to inform you and, sometimes, entertain you.

</p><p class="mt-3 col-sm-8 m-auto text-center" style="color:#E8EDDF">We aim to be an inclusive site for gamers of any ethnicity, gender or sexual orientation. We expect our writers and commenters to treat those they write about as they would if they met them in person. </p><p class="mt-3 col-sm-8 m-auto text-center" style="color:#E8EDDF"> For more on what that means, on the values we embrace and on what lines we expect writers and commenters not to cross, please read this.</p>
      </div>
      <div class="m-auto img-a"></div>
    </div>
  </div>
</main>
<?php include 'tpl/footer.php'; ?>