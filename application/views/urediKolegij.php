<?php 
$podaci = array ('naslov' => "Uredi kolegij", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'kolegiji');
$this->load->view('static/header', $podaci);
?>

<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li><a href="<?php echo site_url ('upravljanjeKolegijima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista kolegija</a></li>
    	<li><a href="<?php echo site_url ('upravljanjeKolegijima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Dodaj kolegij</a></li>
        <li class="active"><a href="#"><i class="icon icon-edit"></i>&nbsp;Uredi kolegij - <?php echo $kolegij['ime']; ?></a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
		<div class="well">
        	<h3>Forma za dodavanje kolegija</h3>
        </div>
	    <?php if (isset ($greska)) { ?>
        <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <b>Dogodila se greška</b> <?php echo $greska; ?>.
        </div>
        <?php } ?>
       
        <form class="form-horizontal" action="<?php echo site_url ('upravljanjeKolegijima/uredi/'.$kolegij['id']); ?>" method="post">
        <fieldset>
            <div class="control-group">
                <label  class="control-label">Ime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" class="input-xlarge" name="ime" value="<?php echo $kolegij['ime']; ?>" placeholder="Unesite ime kolegija ovdje" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Opis <span class="red">*</span></label>
                <div  class="control offset2">
                  <textarea name="opis" class="input-xlarge" rows="3" placeholder="Unesite opis kolegija ovdje"><?php echo $kolegij['opis']; ?></textarea>
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
<?php
$this->load->view('static/footer');
?>