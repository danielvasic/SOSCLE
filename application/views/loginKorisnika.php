<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dobrodošli na SOSCLE</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/bootstrap/css/bootstrap.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/mojStil.css"); ?>" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"></script>
<script type="text/javascript">
	var CI_ROOT = "<?php echo base_url(); ?>";
</script>
<?php if (isset($success)) { ?>
<meta http-equiv="refresh" content="2;url=<?php echo site_url("profil/index") ?>">
<?php } ?>
</head>

<body>
<div class="beta"></div>
<center>
  <div id="logoNaslov"></div>
</center>
<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Vrati lozinku</h3>
  </div>
  <form action="<?php echo site_url('loginKorisnika/vratiLozinku'); ?>" id="vratiLozinkuForma" class="no-margin" method="post" >
    <div class="modal-body">
      <div class="alert alert-error" id="greskaPovratak">
        <p><b>Dogodila se graška</b></p>
        <p id="greskaTekst">Nista unjeli vašu mail adresu.</p>
      </div>
      <div class="alert alert-success" id="uspjehPovratak">
        <p><b>Bravo</b></p>
        <p>Vaša nova lozinka je poslana na vašu mail adresu.</p>
      </div>
      <p>Email:</p>
      <input type="text" name="email" id ="emailModal" placeholder="Unesite vašu email adresu ovdje" class="input-xlarge" />
      <div class="loader" id="loaderVratiLozinku"></div>
      <p class="help-block">Email sa uputama će biti poslan na emial adresu koju upišete.</p>
    </div>
    <div class="modal-footer">
      <button type="reset" class="btn" data-dismiss="modal">Zatvori</button>
      <button type="submit" class="btn btn-primary" id="vrati">Vrati lozinku</button>
    </div>
  </form>
</div>
<div class="section">
  <div class="section-head">
    <h4>Prijavite se na SOSCLE</h4>
  </div>
  <div class="section-body">
    <?php if (isset($error) && $error == TRUE) { ?>
    <div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <p><b>Dogodile su se sljedeće greške:</b></p>
      <?php echo validation_errors(); ?> </div>
    <?php } ?>
    <?php if (isset($loginError) && $loginError == TRUE) { ?>
    <div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <p><b>Dogodile su se sljedeće greške:</b></p>
      <p>Podaci koje ste unjeli su ne ispravni molimo provjerite vaše podatke</p>
    </div>
    <?php } ?>
    <?php if (isset($success) && $success == TRUE) { ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <p><b>Bravo:</b></p>
      <p>Uspješno ste se prijavili na sustav, biti ćete preusmjereni uskoro.</p>
    </div>
    <?php } ?>
    <form action="<?php echo site_url("loginKorisnika"); ?>" class="no-margin" style="margin-bottom:0;" method="post">
      <label>E-mail:</label>
      <input type="text" class="input-large input-mysize" name="email" placeholder="Unesite vaš e-mail ovdje" />
      <label>Lozinka:</label>
      <input type="password"  class="input-large input-mysize" name="lozinka"  placeholder="Unesite vašu lozinku ovdje" />
      <p class="help-block"><a href="#myModal" data-toggle="modal" id="restore">Zaboravili ste lozinku?</a></p>
      <div class="form-actions no-margin">
        <input type="submit" class="btn btn-primary pull-right" name="prijava" value="Prijavi se" />
        <label>
          <input type="checkbox" name="zapamti_me" value="TRUE" style="float:left;" />
          &nbsp;Zapamti me na ovom računalu </label>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url("stil/bootstrap/js/bootstrap.min.js"); ?>"></script> 
<script type="text/javascript" src="<?php echo base_url("stil/init.javascript.js"); ?>"></script>
</body>
</html>