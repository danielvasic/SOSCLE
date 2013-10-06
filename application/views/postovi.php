<?php 
$podaci = array ('naslov' => "Postovi ", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'forumi' => TRUE);
$this->load->view('static/header', $podaci);

?>
<div id="loader_provjera_postova">
<p><img src="<?php echo base_url('/stil/moj_stil/loader.gif'); ?>" />&nbsp;Tražim nove objave...</p>
</div>
<div class="container-fluid" >
<ul class="nav nav-tabs">
    	<li class="active"><a href="<?php echo site_url ('upravljanjeForumima/index') ?>"><i class="icon icon-list"></i>&nbsp;<?php echo $tema['ime']; ?></a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    <ul class="breadcrumb">
        <li><a href="<?php echo site_url('forumi/index'); ?>">Forumi</a><span class="divider">/</span></li>
        <li><a href="<?php echo(site_url('teme/index/'.$tema['id_foruma'])); ?>"><?php echo daj_ime_foruma($tema['id_foruma']);?></a><span class="divider">/</span></li>
        <li><span id="ime_teme"><?php echo $tema['ime']; ?></span></li>
    </ul>
    
    <div id="fade"></div>
    <div class="row-fluid">
        <div class="span12 pull-left">
            <div class="btn-group">
<?php if ($tema['status'] == 'otkljucan' && $forum['status'] == 'otkljucan') { ?>
            	<a class="btn btn-primary" id="komentiraj" rel="tooltip" title="Novi post"><span class="icon-white icon-plus-sign"></span>&nbsp;</a>
<?php } else { 
	if ($forum['status'] == 'zakljucan') {
?>
		<div id="upozorenje_zakljucana" class="alert alert-warning">
                <h4 class="alert-header">Upozorenje!</h4>
                <p>Forum je zakljucan, nemožete dodavati postove.</p></div>
            	<a class="btn btn-primary disabled" href="javascript:void(0)" id="komentiraj" rel="tooltip" title="Novi post"><span class="icon-white icon-plus-sign"></span>&nbsp;</a>
<?php } else {?>
	
				<div id="upozorenje_zakljucana" class="alert alert-warning">
                <h4 class="alert-header">Upozorenje!</h4>
                <p>Tema je zatvorena, nemožete dodavati postove.</p></div>
            	<a class="btn btn-primary disabled" href="javascript:void(0)" id="komentiraj" rel="tooltip" title="Novi post"><span class="icon-white icon-plus-sign"></span>&nbsp;</a>
<?php }
}  ?>
<?php if ($tema['id_korisnika'] == $logiraniKorisnik['id']) { ?>
<?php if ($tema['status'] == 'otkljucan' && $forum['status'] == 'otkljucan') { ?>
                <a class="btn" href="javascript:void(0)" rel="tooltip" id="zatvori" title="Zatvori temu"><span class="icon icon-lock"></span>&nbsp;</a>
<?php } else { ?>
				<a class="btn active" href="javascript:void(0)" rel="tooltip" id="zatvori" title="Otvori temu"><span class="icon icon-lock"></span>&nbsp;</a>
<?php } ?>
                <a class="btn" rel="tooltip" href="javascript:void(0)" id="uredi" name="<?php echo $tema['id_foruma']; ?>" title="Uredi temu"><span class="icon icon-edit"></span>&nbsp;</a>
                <a class="btn" rel="tooltip" href="<?php echo site_url('teme/brisi/'.$tema['id']); ?>" title="Briši temu"><span class="icon icon-trash"></span>&nbsp;</a>
<?php } else {?>
				<a class="btn disabled" href="javascript:void(0)" rel="tooltip" title="Zatvori temu"><span class="icon icon-lock"></span>&nbsp;</a>
                <a class="btn disabled" href="javascript:void(0)" id="uredi" rel="tooltip" title="Uredi temu"><span class="icon icon-edit"></span>&nbsp;</a>
                <a class="btn disabled" href="javascript:void(0)" id="brisi" rel="tooltip" title="Briši temu"><span class="icon icon-trash"></span>&nbsp;</a>
<?php }?>
				<a class="btn" rel="tooltip" id="prikazi" data-toggle="button" title="Prikaži/skrij odgovore"><span class="icon icon-indent-left"></span>&nbsp;</a>    
            </div> 
        </div>
    </div>
    <br clear="all" />
	<div id="postovi">
 	
    </div>
    <div id="btn-center">
		<a href="javascript:void(0)" class="btn btn-large ucitaj_jos" name="1" id="jos_1"><span class="icon icon-plus-sign"></span>&nbsp;Još rezultata</a>
    </div>
</div>

	<div id="komentiraj_ui" class="modal hide fade">
       <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Dodaj post</h3>
       </div>
       <form class="form-horizontal" id="forma_za_dodavanje" method="post" action="<?php echo site_url('postovi/dodaj_post/'.$tema['id']) ?>">
           	<div class="modal-body">
            	<div class="alert alert-error" id="dodaj_post_greske"></div>
              	<div class="control-group">
                   	<label class="control-label">Ime teme </label>
                    <div class="controls">
                      <input name="imePosta" type="text" class="input input-xlarge" value="<?php echo $tema['ime']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                   	<label class="control-label">Vaš tekst </label>
                    <div class="controls">
                      	<textarea name="sadrzajPosta" id="sadrzajPosta" rows="5" class="input input-xlarge" placeholder="Ovdje upišite vaš tekst"></textarea>
                    </div>
                </div>
            </div>
          	<div class="modal-footer">
                <div class="btn-group">
                        <button type="submit" id="spasi_post" class="btn btn-primary"><span class="icon-white icon-plus"></span> Spasi</button>
                        <button type="reset" class="btn">Poništi</button>
                </div>
            </div>
        </form>
    </div>   
    
    <div id="dodaj_odgovor" class="modal hide fade">
       <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Dodaj odgovor</h3>
       </div>
       <form class="form-horizontal" id="forma_za_odgovaranje" method="post" action="<?php echo site_url('postovi/dodaj_odgovor/'.$tema['id']) ?>">
           	<div class="modal-body">
            	<div class="alert alert-error" id="odgovoriGreske"></div>
              	<div class="control-group">
                   	<label class="control-label">Ime posta: </label>
                    <div class="controls">
                      <input name="imeOdgovora" type="text" class="input input-xlarge" value="" />
                    </div>
                </div>
                <div class="control-group">
                   	<label class="control-label">Vaš tekst: </label>
                    <div class="controls">
                      	<textarea name="sadrzajOdgovora" id="sadrzajOdgovora" rows="5" class="input input-xlarge" placeholder="Ovdje upišite vaš tekst"></textarea>
                    </div>
                </div>
            </div>
          	<div class="modal-footer">
                <div class="btn-group">
                        <button type="submit" id="spasi_odgovor" class="btn btn-primary"><span class="icon-white icon-plus"></span> Spasi</button>
                        <button type="reset" class="btn">Poništi</button>
                </div>
            </div>
        </form>
    </div> 
    
    <div id="uredi_ui" class="modal hide fade">
       <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Uredi temu</h3>
       </div>
       <form class="form-horizontal" id="forma_za_dodavanje" method="post" action="<?php echo site_url('postovi/dodaj_post/'.$tema['id']) ?>">
           	<div class="modal-body">
            	<div class="alert alert-error" id="uredi_temu_greske"></div>
              	<div class="control-group">
                   	<label class="control-label">Ime teme: </label>
                    <div class="controls">
                      <input name="ime" type="text" class="input input-xlarge" value="<?php echo $tema['ime']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                   	<label class="control-label">Opis teme: </label>
                    <div class="controls">
                      	<textarea name="opis" rows="5" id="tema_uredi_opis" class="input input-xlarge" placeholder="Ovdje upišite vaš tekst"></textarea>
                    </div>
                </div>
            </div>
          	<div class="modal-footer">
                <div class="btn-group">
                        <button type="submit" id="spasi_temu" class="btn btn-primary"><span class="icon-white icon-hdd"></span>&nbsp;Spasi</button>
                        <button type="reset" class="btn">Poništi</button>
                </div>
            </div>
        </form>
    </div>   


	<div id="uredi_post_ui" class="modal hide fade">
       <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Uredi temu</h3>
       </div>
       <form class="form-horizontal" id="forma_za_dodavanje">
           	<div class="modal-body">
            	<div class="alert alert-error" id="uredi_post_greske"></div>
              	<div class="control-group">
                   	<label class="control-label">Ime teme: </label>
                    <div class="controls">
                      <input name="imeUredjenogPosta" type="text" class="input input-xlarge" />
                    </div>
                </div>
                <div class="control-group">
                   	<label class="control-label">Sadrzaj: </label>
                    <div class="controls">
                      	<textarea name="sadrzajUredjenogPosta" id="sadrzajUredjenogPosta" rows="5" class="input input-xlarge" placeholder="Ovdje upišite vaš tekst"></textarea>
                    </div>
                </div>
            </div>
          	<div class="modal-footer">
                <div class="btn-group">
                        <button type="submit" id="spasi_uredeni_post" class="btn btn-primary"><span class="icon-white icon-hdd"></span>&nbsp;Spasi</button>
                        <button type="reset" class="btn">Poništi</button>
                </div>
            </div>
        </form>
    </div>   
</div>

<script type="text/javascript" src="<?php echo base_url('js/jquery.cookie.js') ?>" ></script>
<script type="text/javascript" src="<?php echo base_url('js/tema.js') ?>" ></script>
<script type="text/javascript" src="<?php echo base_url('js/post.js') ?>" ></script>
<script type="text/javascript">
var TEMA = new tema('<?php echo $tema['id'] ?>');
var POST = new post('<?php echo $forum['status'] == 'otkljucan' && $tema['status'] == 'otkljucan' ? "otkljucan" : "zakljucan"; ?>', $.cookie('prikazi_komentare'));
</script>
<script type="text/javascript" src="<?php echo base_url('js/postovi-events.js') ?>" ></script>

</script>

<?php
$this->load->view ('static/footer');
?>
