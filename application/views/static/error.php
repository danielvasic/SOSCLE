
<?php 
$podaci = array ('naslov' => "Nastala je greška!");
$this->load->view('static/header', $podaci);
if (isset ($preusmjeri)) { 
?>
<meta http-equiv="refresh" content="<?php echo $vrijeme; ?>;url=<?php echo site_url($url) ?>">
<?php } ?>
<div class="container">
    <div class="alert alert-error">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Nastala je greška!</h4>
      <p><?php echo $greska; ?></p>
    </div>
</div>
<?php
$this->load->view('static/footer');
?>