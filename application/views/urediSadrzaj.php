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
    	<li><a href="<?php echo site_url ('upravljanjeSadrzajem/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Dodaj paket</a></li>
        <li class="active"><a href="#"><i class="icon icon-plus-sign"></i>&nbsp;Uredi sadrzaj - <?php echo $sadrzaj['ime']; ?></a></li>
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
           <b>Bravo!</b> uspješno ste uredili nastavni sadržaj.
        </div>
        <?php } ?>
        <form class="form-horizontal" action="<?php echo site_url ('upravljanjeSadrzajem/uredi/'.$sadrzaj['id']); ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="control-group">
                <label  class="control-label">Ime <span class="red">*</span></label>
                <div class="control offset2">
                    <input type="text" class="input-xlarge" name="ime" value="<?php echo $sadrzaj['ime'] ?>" placeholder="Unesite ime kolegija ovdje" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">SCORM paket <span class="red">*</span></label>
                <div  class="control offset2">
                  <input type="file" name="scormFile" class="input-xlarge" />
                   <a href="<?php echo base_url($sadrzaj['putanja'])?>">SCORM paket</a>
                   <p class="help-block">Maximalna veličina paketa je 10MB, uređivanjem brišete i dodajete novi paket ako ništa ne odaberete ostati će stari SCORM paket.</p>
                </div>
            </div>
            
           <div class="control-group">
                <label class="control-label">Odaberite kolegij kojem ovaj paket pripada <span class="red">*</span></label>
                <div  class="control offset2">
                	<select class="input-xlarge" name="kolegij">
                    	<?php foreach ($kolegiji as $kolegij) { ?>
                        <option value="<?php echo $kolegij['id']; ?>"<?php if ($kolegij['id'] == $sadrzaj['id']) { ?> selected="selected" <?php }?>><?php echo $kolegij['ime']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Vrsta nevigacije <span class="red">*</span></label>
                <div  class="control offset2">
                	<select class="input-xlarge" name="navigacija">
                    	<option <?php if ($sadrzaj['vrsta_navigacije'] == 'nextprev') { ?> selected="selected" <?php }?> value="nextprev">Sljedeća prethodna</option>
                        <option <?php if ($sadrzaj['vrsta_navigacije'] == 'tree') { ?> selected="selected" <?php }?> value="tree">Stablo aktivnosti</option>
                        <option <?php if ($sadrzaj['vrsta_navigacije'] == 'both') { ?> selected="selected" <?php }?> value="both">Oboje</option>
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Opis <span class="red">*</span></label>
                <div  class="control offset2">
                  <textarea name="opis" class="input-xlarge" rows="3" placeholder="Unesite opis kolegija ovdje"><?php echo $sadrzaj['opis'] ?></textarea>
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
