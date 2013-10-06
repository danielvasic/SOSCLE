<?php 
$podaci = array ('naslov' => "Uredi korisnika", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'korisnici');
$this->load->view('static/header', $podaci);
if (isset ($preusmjeri)) { 
?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('upravljanjeKorisnicima/index') ?>">
<?php } ?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li><a href="<?php echo site_url('upravljanjeKorisnicima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista korisnika</a></li>
    	<li><a href="<?php echo site_url('upravljanjeKorisnicima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Novi korisnik</a></li>
        <li class="active"><a href="#"><i class="icon icon-edit"></i>&nbsp;Uredi korisnika</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
		<div class="well">
        	<h3>Forma za uređivanje korisnika</h3>
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
           <b>Bravo!</b> uspješno ste uredili korisničke podatke, uskoro ćete biti preusmjereni.
        </div>
    <?php } ?>
        <form class="form-horizontal" action="<?php echo site_url('upravljanjeKorisnicima/uredi/'.$korisnik['id']); ?>" method="post" enctype="multipart/form-data" >
        <fieldset>
            <div class="control-group">
                <label  class="control-label">Ime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" class="input-xlarge" name="ime" value="<?php echo $korisnik['ime']; ?>" placeholder="Unesite ime korisnika" />
                    <input type="hidden" name="id" value="<?php echo $korisnik['id']; ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Prezime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" name="prezime" class="input-xlarge"  value="<?php echo $korisnik['prezime']; ?>" placeholder="Unesite prezime korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Email <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="text" name="email" class="input-xlarge"  value="<?php echo $korisnik['email']; ?>" placeholder="Unesite email korisnika" />
                </div>
            </div>
            
            
            <div class="control-group">
                <label class="control-label">Nova lozinka <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="password" name="lozinka" class="input-xlarge" placeholder="Unesite novu lozinku korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Uloga <span class="red">*</span></label>
                <div  class="control offset2">
                    <select name="uloga" class="input-xlarge">
                    	<option selected="selected" value="<?php echo $korisnik['uloga']; ?>"><?php echo $korisnik['uloga']; ?></option>
                    	<option value="Ucenik">Učenik</option>
                    	<option value="Ucitelj">Učitelj</option>
                    	<option value="Administrator">Administrator</option>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Grad <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="text" name="grad" class="input-xlarge"  value="<?php echo $korisnik['grad']; ?>" placeholder="Unesite grad korisnika" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Slika korisnika</label>
                <div  class="control offset2">
                    <input type="file" name="avatar" class="input-xlarge" placeholder="Odaberite sliku korisnika" />
                    <p class="help-block">Dopušteni formati slika su .JPG .PNG i .GIF maximalne veličine 5MB.</p>
                    <p class="help-block"><img src="<?php echo base_url('/avatari/48x48/'.$korisnik['avatar']); ?>" /></p>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Opis</label>
                <div  class="control offset2">
                  <textarea name="opis" class="input-xlarge" rows="3" placeholder="Ovdje unesite opis"><?php echo $korisnik['opis']; ?></textarea>
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