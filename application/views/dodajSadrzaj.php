<?php 
$podaci = array ('naslov' => "Dodaj sadrzaj", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'sadrzaj');
$this->load->view('static/header', $podaci);
if (isset ($preusmjeri) ) { 
?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('upravljanjeSadrzajem/index') ?>">
<?php } ?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li><a href="<?php echo site_url ('upravljanjeSadrzajem/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista paketa</a></li>
    	<li class="active"><a href="<?php echo site_url ('upravljanjeSadrzajem/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Dodaj paket</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
		<div class="well">
        	<h3>Forma za dodavanje nastavnog sadržaja</h3>
        </div>
		<?php 
			if (isset ($greska)) {
		?>
        <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <b>Dogodila se greška:</b> <?php echo $greska; ?>
        </div>
        
        <?php
		}
		?>
        
        <?php if (isset ($uspjeh)) { ?>
        <div class="alert alert-success">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <b>Bravo!</b> uspješno ste dodali nastavni sadržaj u bazu podataka.
        </div>
        <?php } ?>
        <form class="form-horizontal" action="<?php echo site_url ('upravljanjeSadrzajem/dodaj'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="control-group">
                <label  class="control-label">Ime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" class="input-xlarge" name="ime" placeholder="Unesite ime lekcije ovdje" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">SCORM paket <span class="red">*</span></label>
                <div  class="control offset2">
                  <input type="file" name="scormFile" class="input-xlarge" />
                   <p class="help-block">Maximalna veličina paketa je 10MB</p>
                </div>
            </div>
            
           <div class="control-group">
                <label class="control-label">Odaberite kolegij kojem ovaj paket pripada <span class="red">*</span></label>
                <div  class="control offset2">
                	<select class="input-xlarge" name="kolegij">
                    	<?php foreach ($kolegiji as $kolegij) { ?>
                        <option value="<?php echo $kolegij['id']; ?>"><?php echo $kolegij['ime']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Vrsta nevigacije <span class="red">*</span></label>
                <div  class="control offset2">
                	<select class="input-xlarge" name="navigacija">
                    	<option value="nextprev">Sljedeća prethodna</option>
                        <option value="tree">Stablo aktivnosti</option>
                        <option value="both">Oboje</option>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Opis <span class="red">*</span></label>
                <div  class="control offset2">
                  <textarea name="opis" class="input-xlarge" rows="3" placeholder="Unesite opis kolegija ovdje"></textarea>
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
$this->load->view("static/footer");
?>
