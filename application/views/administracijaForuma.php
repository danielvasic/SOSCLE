<?php 
$podaci = array ('naslov' => "Upravljanje forumima", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'forumi');
$this->load->view('static/header', $podaci);
?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="<?php echo site_url ('upravljanjeForumima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista foruma</a></li>
        <li><a href="<?php echo site_url ('upravljanjeForumima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Novi forum</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    <?php if (count($forumi) > 0) { ?>
		<table class="table table-condensed table-bordered table-striped">
        <thead>
        	<th width="5%"></th>
            <th width="55%">Naziv i opis foruma</th>
            <th width="10%">Autor</th>
            <th width="5%">Broj tema</th>
            <th width="5%">Broj postova</th>
            <th width="10%">Zadnja tema</th>
            <th width="10%">Kontrole</th>
        </thead>
        <tbody>
         <?php foreach ($forumi as $forum) { ?>
        	<tr id="forum<?php echo $forum['id_foruma'] ?>">
            	<td>
                <?php if ($forum['status'] == 'otkljucan') { ?>
                <img id="status_slika_<?php echo $forum['id_foruma'] ?>" src="<?php echo base_url('stil/moj_stil/Folder_Open.png'); ?>" />
                <?php } else if ($forum['status'] == 'zakljucan') { ?>
                <img id="status_slika_<?php echo $forum['id_foruma'] ?>" src="<?php echo base_url('stil/moj_stil/Lock.png'); ?>" />
                <?php } ?>
                </td>
                <td style="text-align:justify">
                    	<b><a href="<?php echo site_url('teme/index/'.$forum['id_foruma']); ?>"><?php echo $forum['ime_foruma'] ?></a></b>
                        <p><?php echo $forum['opis_foruma'] ?></p>
                </td>
                <td><a href="<?php echo site_url ('profilKorisnika/'. $forum['id_korisnika'] ) ?>"><?php echo $forum['ime_korisnika'] .  " " . $forum['prezime'] ?></a></td>
                <td><span class="label label-danger"><?php echo broj_tema($forum['id_foruma']); ?></span></td>
                <td><span class="label label-danger"><?php echo broj_postova_forum($forum['id_foruma']); ?></span></td>
                <td><?php echo zadnja_tema($forum['id_foruma']) ?></td>
                <td>
                	<div class="btn-group">
                    	<a href="#" class="btn" rel="tooltip" title="Akcije"><span class="icon icon-comment"></span>&nbsp;</a>
                        <a href="#" class="btn dropdown-toggle"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        	<li><a href="<?php echo site_url('upravljanjeForumima/uredi/'. $forum['id_foruma']);?>"><span class="icon icon-edit"></span>&nbsp;Uredi</a></li>
                            <li><a class="brisi" href="<?php echo site_url('upravljanjeForumima/brisi/'. $forum['id_foruma']);?>"><span class="icon icon-trash"></span>&nbsp;Briši</a></li>
                            <li class="divider"></li>
                            <li>
                            	<a href="javascript:void(0)" class="toggle_zakljucaj" name="<?php echo $forum['id_foruma'] ?>">
                                	<span class="icon icon-lock"></span>
									<?php if ($forum['status'] == 'otkljucan') { ?>
                                    <span id="zakljucaj_akcija">Zaključaj</span>
                                    <?php } else if ($forum['status'] == 'zakljucan') { ?>
                                    <span id="zakljucaj_akcija">Otključaj</span>
									<?php } ?>                           
                            </a></li>
                        </ul>
                    </div>
                </td>
            </tr>
          <?php } ?>
        </tbody>
        </table>
            
		<?php echo generiraj_paginaciju('upravljanjeForumima/index', $broj_foruma); ?>
        <?php } else { ?>
        <div class="alert alert-info" style="margin-bottom:0;">
        	<h4 class="alert-header">Upozorenje</h4>
            <p>Još nema nijedan forum, možete dodati jedan, na linku iznad.</p>
        </div> 
        <?php } ?>
    </div>
    
</div>

<script type="text/javascript">
$('body').append ($('<div></div>').attr('id', 'big_loader'));
$('.toggle_zakljucaj').click (function () {
	var id_foruma = $(this).attr('name');
	$("#big_loader").fadeIn();
	$.getJSON('<?php echo site_url ('upravljanjeForumima/toggle_zakljucaj'); ?>', {'id' : id_foruma}, function (data) {
		if (data.greska == 0) {
			$('#status_slika_'+id_foruma).attr ('src', decodeURIComponent(data.url_slike));
			$("#zakljucaj_akcija").html(data.status);
		} else {
			alert (data.tekst);	
		}	
		$("#big_loader").fadeOut();
	});
});
</script>

<?php 
	$this->load->view ('static/footer');
?>
