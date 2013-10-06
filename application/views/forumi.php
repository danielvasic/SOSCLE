<?php 
$podaci = array ('naslov' => "Forumi", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'forumi' => TRUE);
$this->load->view('static/header', $podaci);
?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="#"><i class="icon icon-list"></i>&nbsp;Lista foruma</a></li>
    </ul>
    <div id="sadrzaj">
    	<ul class="breadcrumb">
        	<li>Forumi</li>
        </ul>
        <?php if (count ($forumi) > 0) { ?>
		<table class="table table-condensed table-striped">
        <thead>
        	<th width="5%"></th>
            <th width="55%">Naziv i opis foruma</th>
            <th width="10%">Broj tema</th>
            <th width="10%">Broj postova</th>
            <th width="10%">Autor</th>
            <th width="10%">Zadnja tema</th>
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
                    	<b><a href="<?php echo site_url("teme/index/".$forum['id_foruma'] );?>"><?php echo $forum['ime_foruma']; ?></a></b>
                        <p><?php echo $forum['opis_foruma'] ?></p>
                </td>
                <td style="text-align:center;"><span class="label label-danger"><?php echo broj_tema($forum['id_foruma']) ?></span></td>
                <td style="text-align:center;"><span class="label label-danger"><?php echo broj_postova_forum($forum['id_foruma']); ?></span></td>
                <td>
                	<a href="<?php echo site_url ('profil/pogledaj/'. $forum['id_korisnika'] ) ?>">
						<?php echo $forum['ime_korisnika'] .  " " . $forum['prezime'] ?>
                    </a>
                </td>
                <td>
                	 <?php echo zadnja_tema($forum['id_foruma']); ?>             
                </td>
            </tr>
        <?php } ?>
        </tbody>
        </table>
        <?php } else { ?>
        <div class="alert alert-info">
        	<h4 class="alert-header">Obavijest</h4>
            <p>Nema foruma u Vašim grupama.</p>
        </div>
        <?php } ?>

		<?php echo generiraj_paginaciju('forumi/index', $broj_foruma); ?>
    </div>
    
</div>
<?php 
$this->load->view ('static/footer');
?>
