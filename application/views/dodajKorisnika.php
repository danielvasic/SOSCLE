<?php 
$podaci = array ('naslov' => "Dodaj korisnika", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'korisnici');
$this->load->view('static/header', $podaci);
if (isset ($preusmjeri)) { 
?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('upravljanjeKorisnicima/index') ?>">
<?php } ?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li><a href="<?php echo site_url('upravljanjeKorisnicima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista korisnika</a></li>
    	<li class="active"><a href="<?php echo site_url('upravljanjeKorisnicima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Novi korisnik</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
		<div class="well">
        	<h3>Forma za dodavanje korisnika</h3>
        </div>
	<?php if ($greska) { ?>
        <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <p><b>Dogodile su se sljedeće greške:</b></p>
		   <?php echo validation_errors(); ?>
        </div>
	<?php } ?>
    <?php if (!$greska && $potvrda) { ?>
         <div class="alert alert-success">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <b>Bravo!</b> uspješno ste dodali korisnika u bazu podataka uskoro ćete biti preusmjereni.
        </div>
    <?php } ?>
        <form class="form-horizontal" action="<?php echo site_url('upravljanjeKorisnicima/dodaj'); ?>" method="post" enctype="multipart/form-data" >
        <fieldset>
            <div class="control-group">
                <label  class="control-label">Ime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" class="input-xlarge" name="ime" value="<?php echo set_value('ime') ?>" placeholder="Unesite ime korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Prezime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" name="prezime" class="input-xlarge"  value="<?php echo set_value('prezime') ?>" placeholder="Unesite prezime korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Email <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="text" name="email" class="input-xlarge"  value="<?php echo set_value('email') ?>" placeholder="Unesite email korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Lozinka <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="password" name="lozinka" class="input-xlarge" placeholder="Unesite lozinku korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Uloga <span class="red">*</span></label>
                <div  class="control offset2">
                    <select name="uloga" class="input-xlarge">
                    	<option selected="selected" value="<?php echo set_value('uloga') ?>"><?php echo set_value('uloga') ?></option>
                    	<option value="Ucenik">Učenik</option>
                    	<option value="Ucitelj">Učitelj</option>
                    	<option value="Administrator">Administrator</option>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Grad <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="text" name="grad" class="input-xlarge"  value="<?php echo set_value('grad') ?>" placeholder="Unesite grad korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Slika korisnika</label>
                <div  class="control offset2">
                    <input type="file" name="avatar" class="input-xlarge" placeholder="Odaberite sliku korisnika" />
                    <p class="help-block">Dopušteni formati slika su .JPG .PNG i .GIF maximalne veličine 5MB.</p>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Opis</label>
                <div  class="control offset2">
                  <textarea name="opis" class="input-xlarge" rows="3" placeholder="Ovdje unesite opis"><?php echo set_value('opis') ?></textarea>
                  <p class="help-block">Polja označena sa <span class="red">*</span> su obavezna.</p>
                </div>
            </div>
            
            <div class="form-actions">
            	<button type="submit" class="btn btn-primary">Spasi</button>
            	<button type="reset" class="btn">Poništi</button>
            </div>
        </fieldset>
        </form>
    </div>
    
</div>
<?php
$this->load->view('static/footer');
?>