<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instalacija sustava SOSCLE</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/bootstrap/css/bootstrap.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/mojStil.css"); ?>" />
</head>

<body>
<div class="beta"></div>
<div id="logo"></div>
<div class="section">
	<div class="section-head">
    	<h3>Instalacija sustava - Postavljanje baze podataka</h3>
    </div>
    <?php if(isset($greska)) { ?>
    <div class="alert alert-error" style="margin:10px;">
    	<h4>Nastala je greska</h4>
        <p><?php echo $greska; ?></p>
    </div>
    <?php } ?>
	<div class="section-body">
            <form action="<?php echo site_url('instalacija/postaviBazu'); ?>" method="post">
            
            	 <label>Ime poslužitelja:</label>
                <input type="text" class="input-large input-mysize" name="host" placeholder="Unesite ime poslužitelja" value="<?php echo set_value('host'); ?>"/>
                
                <label>Korisničko ime (email adresa):</label>
                <input type="text" class="input-large input-mysize" name="korime" placeholder="Unesite vaše korisničko ime" value="<?php echo set_value('korime'); ?>"/>
                <label>Lozinka:</label>
                <input type="password"  class="input-large input-mysize" name="lozinka"  placeholder="Unesite vašu lozinku ovdje"  value="<?php echo set_value('lozinka'); ?>" />
                
                <label>Baza podataka:</label>
                <input type="text" class="input-large input-mysize" name="baza" placeholder="Unesite ime baze podataka"  value="<?php echo set_value('baza'); ?>" />
                <p class="help-block">Molimo da prvo zapravite bazu podataka pod nazivom koji ćete upisati u polje <i>Baza podataka</i>.</p>
    
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary btn-large" name="prijava" value="Sljedeci korak" />
                </div>
            </form>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"></script>
<script type="text/javascript">
$(".alert").fadeIn();
</script>
<script type="text/javascript" src="<?php echo base_url("stil/bootstrap/js/bootstrap.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("stil/init.javascript.js"); ?>"></script>
</body>
</html>
