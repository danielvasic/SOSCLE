<?php 
$podaci = array ('naslov' => "Profil korisnika ". $korisnik['ime']. " " .$korisnik['prezime'], 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'pocetna' => TRUE);
$this->load->view('static/header', $podaci);
if (isset ($uspjeh)) { 
?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('profil/index') ?>">
<?php } ?>
<div class="chat">
	<div class="chat-header">
      	<div class="row-fluid">
        	<div class="span1" style="position:relative;">
            	<button class="dropdown-toggle btn btn-mini" data-toggle="dropdown" style="width:15px; height:15px; padding:0;"><span class="caret"></span></button>
            	<ul class="dropdown-menu">
                	<li><a href="#" class="postavi_status" id="online"><span class="icon-online"></span>&nbsp;Online</a></li>
                    <li><a href="#" class="postavi_status" id="zauzet"><span class="icon-zauzet"></span>&nbsp;Zauzet</a></li>
                    <li class="divider"></li>
                    <li><a href="#" class="postavi_status" id="offline"><span class="icon-offline"></span>&nbsp;Odjavi se sa chata</a></li>
            	</ul>
			</div>
            
            <div class="span9" style="margin-left:10px;">
            	<a href="#" class=""><?php echo $logiraniKorisnik['ime'] . " " . $logiraniKorisnik['prezime'] ?></a>
            </div>

            <div class="span1" style="margin-top:2px;">
        	    <span id="status_korisnika" class="icon-<?php echo $logiraniKorisnik['status']; ?>"></span>
            </div>

		</div>
	</div>
        <div class="chat-body">
        	<div class="chat-disabled" <?php if ($logiraniKorisnik['status'] == 'offline') { ?>style="display:block;"<?php } ?>>
            	<div class="chat-loader" <?php if ($logiraniKorisnik['status'] == 'offline') { ?>style="display:none;"<?php } ?>></div>
            </div>
			<div id="chat-body"></div> 
        </div>
</div>

<div class="container-fluid">
	<div class="row-fluid">
        <div class="span10">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo site_url('profil/pogledaj/'.$korisnik['id']); ?>"><i class="icon icon-user"></i>&nbsp;<?php echo $korisnik['ime']. " " .$korisnik['prezime']; ?></a></li>
                <li><a href="<?php echo site_url('profil/statistike/'.$korisnik['id']); ?>"><i class="icon icon-tasks"></i>&nbsp;Statistike</a></li>
                <li class="active"><a href="#"><i class="icon icon-edit"></i>&nbsp;Uredi profil</a></li>
            </ul>
        </div>
    </div>
    <div class="row-fluid">
    <div class="tab-content span10" id="sadrzaj">
		<div class="well">
        	<h3>Forma za uređivanje profila</h3>
        </div>
	<?php if (isset($greska)) { ?>
        <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <p><b>Dogodile su se sljedeće greške:</b></p>
		   <?php echo validation_errors(); ?>
        </div>
	<?php } ?>
    <?php if (!isset($greska) && isset($uspjeh)) { ?>
         <div class="alert alert-success">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <b>Bravo!</b> uspješno ste ažurirali Vaše podatke, uskoro ćete biti preusmjereni.
        </div>
    <?php } ?>
        <form class="form-horizontal" action="<?php echo site_url('upravljanjeProfilom/index/'); ?>" method="post" enctype="multipart/form-data" >
        <fieldset>
            <div class="control-group">
                <label  class="control-label">Ime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" class="input-xlarge" name="ime" value="<?php echo $korisnik['ime']; ?>" placeholder="Unesite Vaše ime" />
                    <input type="hidden" name="id" value="<?php echo $korisnik['id']; ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Prezime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" name="prezime" class="input-xlarge"  value="<?php echo $korisnik['prezime']; ?>" placeholder="Unesite Vaše prezime" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Email <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="text" name="email" class="input-xlarge"  value="<?php echo $korisnik['email']; ?>" placeholder="Unesite Vašu email adresu" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Stara lozinka <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="password" name="stara_lozinka" class="input-xlarge" placeholder="Unesite Vašu staru lozinku" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Nova lozinka <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="password" name="nova_lozinka" class="input-xlarge" placeholder="Unesite Vašu novu lozinku" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Grad <span class="red">*</span></label>
                <div  class="control offset2">
                    <input type="text" name="grad" class="input-xlarge"  value="<?php echo $korisnik['grad']; ?>" placeholder="Unesite ime Vašeg grada" />
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
                  <textarea name="opis" class="input-xlarge" rows="3" placeholder="Ovdje unesite šta hoćete o Vama"><?php echo $korisnik['opis']; ?></textarea>
                  <p class="help-block">Polja označena sa <span class="red">*</span> su obavezna.</p>
                </div>
            </div>
            
            <div class="form-actions">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Spasi</button>
                    <button type="reset" class="btn">Poništi</button>
                </div>
            </div>
        </fieldset>
        </form>
    </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('stil/chat.js'); ?>"></script>
<?php
$this->load->view('static/footer');
?>