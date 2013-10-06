<?php 
$podaci = array ('naslov' => "Dodaj grupu", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'grupe');
$this->load->view('static/header', $podaci);
if (isset ($preusmjeri) ) { 
?>
<meta http-equiv="refresh" content="5;url=<?php echo site_url ('upravljanjeGrupama/index') ?>">
<?php } ?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li><a href="<?php echo site_url('upravljanjeGrupama/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista grupa</a></li>
    	<li><a href="<?php echo site_url('upravljanjeGrupama/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Nova grupa</a></li>
        <li class="active"><a href="#"><i class="icon icon-edit"></i>&nbsp;Uređivanje grupe - <?php echo $imeGrupe; ?></a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    	<div class="well">
        	<h3>Forma za uređivanje grupe</h3>
        </div>
        <?php if (isset($greska)) { ?>
         <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <p><b>Dogodila se greška</b>.</p>
           <?php echo validation_errors(); ?>
        </div>
        <?php } ?>
        <?php if (isset($success)) { ?>
             <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
               <b>Bravo</b> uspješno ste ažurirali grupu uskoro ćete biti preusmjereni.
            </div>
        <?php } ?>
        <form class="form-horizontal" action="<?php echo site_url('upravljanjeGrupama/uredi/'.$id) ?>" method="post">
        	<fieldset>
            	<div class="control-group">
                	<label class="control-label">Ime grupe <span class="red">*</span></label>
                    <div class="controls offset2">
                    	<input type="text" name="imeGrupe" class="input-xxlarge" value="<?php echo $imeGrupe; ?>" placeholder="Unesite ime grupe" />
                    </div>
                </div>
                
                <div class="control-group">
                	<label class="control-label">Opis grupe <span class="red">*</span></label>
                    <div class="controls offset2">
                   	  <textarea name="opisGrupe" class="input-xxlarge" rows="3" placeholder="Unesite opis grupe"><?php echo $opisGrupe; ?></textarea>
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
$this->load->view('static/footer');
?>