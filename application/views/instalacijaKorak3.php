<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instalacija sustava SOSCLE</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/bootstrap/css/bootstrap.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/mojStil.css"); ?>" />
<?php if (isset($uspjeh)) { ?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('loginKorisnika/index') ?>">
<?php } ?>
</head>

<body>
<div class="beta"></div>
<div id="logo"></div>
<div class="section">
	<div class="section-head">
    	<h3>Instalacija sustava - Postavljanje mail servera</h3>
    </div>
    <?php if(isset($uspjeh)) { ?>
    <div class="alert alert-success" style="margin:10px;">
    	<h4>Uspjeh!</h4>
        <p><?php echo $uspjeh; ?></p>
    </div>
    <?php } ?>
    
    <?php if(isset($greska)) { ?>
    <div class="alert alert-error" style="margin:10px;">
    	<h4>Nastala je greska</h4>
        <p><?php echo $greska; ?></p>
    </div>
    <?php } ?>
	<div class="section-body">
            <form action="<?php echo site_url('instalacija/postaviSmtp'); ?>" method="post">
            	<label>SMTP host:</label>
                <input type="text" class="input-large input-mysize" name="host" placeholder="Unesite ime Smtp hosta" value="<?php echo set_value('ime'); ?>"/>
                
                <label>Korisničko ime:</label>
                <input type="text" class="input-large input-mysize" name="ime" placeholder="Unesite Vašu mail adresu" value="<?php echo set_value('prezime'); ?>"/>
                
                <label>Lozinka:</label>
                <input type="password" class="input-large input-mysize" name="lozinka" placeholder="Unesite Vašu lozinku" />
                
                <label>Port:</label>
                <select name="port">
					<option value="587">TLS/STARTTLS: 587</option> 
                    <option value="465">SSL: 465</option>              
                </select>
                <p class="help-block">Prijedlog napravite racun na <a href="http://www.gmail.com/">gmail</a> i postavite te podatke sa hostom smtp.googlemail.com</p>
    
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
