<?php 
$podaci = array ('naslov' => "Upozorenje!");
$this->load->view('static/header', $podaci);
?>

<div class="container">
    <div class="alert alert-info">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Upozorenje!</h4>
      <p><?php echo $greska; ?></p>
    </div>
</div>
<?php
$this->load->view('static/footer');
?>