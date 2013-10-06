<?php
$podaci = array ('logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'forumi', 'naslov' => 'Dodaj forum');
$this->load->view ('static/header', $podaci);
if (isset ($preusmjeri) ) { 
?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('upravljanjeForumima/index') ?>">
<?php } ?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li><a href="<?php echo site_url('upravljanjeForumima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista foruma</a></li>
        <li class="active"><a href="<?php echo site_url('upravljanjeForumima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Novi forum</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    	<div class="well">
        	<h3>Forma za dodavanje foruma</h3>
        </div>
        <?php if (isset ($greska)) { ?>
        <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <p><b>Dogodila se greška</b></p>
           <p><?php echo validation_errors(); ?></p>
        </div>
        <?php } ?>
        <?php if (isset ($success)) { ?>
        <div class="alert alert-success">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <b>Bravo</b> uspješno ste napravili forum.
        </div>
        <?php } ?>
        
        <form class="form-horizontal" action="<?php site_url ('upravljanjeForumina/dodaj'); ?>" method="POST">
        	<fieldset>
            	<div class="control-group">
                	<label class="control-label">Ime foruma <span class="red">*</span></label>
                    <div class="controls offset2">
                    	<input type="text" name="imeForuma" class="input-xxlarge" value="<?php echo set_value('imeForuma') ?>" placeholder="Unesite ime foruma" />
                    </div>
                </div>
                
                <div class="control-group">
                	<label class="control-label">Odaberite grupe <span class="red">*</span></label>
                    <div class="controls offset2">
                    	<select multiple="multiple" class="input-xxlarge" name="grupe[]">
                        <?php foreach ($grupe as $grupa): ?>
                        <option value="<?php echo $grupa['id'] ?>">
                        	<?php echo $grupa['ime'] ?>
                        </option>
                        <?php endforeach; ?>
                        </select>
                        <div class="help-block">Držite CTRL (ili CMD na Macintosh sustavima) za dodavanja i brisanje sa liste.</div>
                    </div>
                </div>
                
                <div class="control-group">
                	<label class="control-label">Status foruma <span class="red">*</span></label>
                    <div class="controls offset2">
                    	<select class="input-xxlarge" name="status">
                        	<option value="otkljucan" selected="selected">Otključan</option>
                            <option value="zakljucan">Zaključan</option>
                        </select>
                    </div>
                </div>
                
                <div class="control-group">
                	<label class="control-label">Opis foruma <span class="red">*</span></label>
                    <div class="controls offset2">
                    	<textarea rows="3" name="opisForuma" class="input-xxlarge" placeholder="Unesite opis foruma"><?php echo set_value('opisForuma') ?></textarea>
                        <div class="help-block">Polja označena sa <span class="red">*</span> su potrebna</div>
                    </div>
                </div>
                
                <div class="form-actions">
                	<div class="btn-group">
                    	<button class="btn btn-primary" type="submit">Spasi</button>
                        <button class="btn" type="reset">Poništi</button>
                    </div>
                </div>
			</fieldset>
		</form>
    </div>
    
</div>
<?php
$this->load->view ('static/footer');
?>