<?php 
$podaci = array ('naslov' => "Upravljanje nastavnim sadržajem", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'sadrzaj');
$this->load->view('static/header', $podaci);
?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="<?php echo site_url('upravljanjeSadrzajem/index'); ?>"><i class="icon icon-list"></i>&nbsp;Lista paketa</a></li>
        <li><a href="<?php echo site_url('upravljanjeSadrzajem/dodaj'); ?>"><i class="icon icon-plus-sign"></i>&nbsp;Dodaj paket</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    <?php if (count($sadrzaji) > 0 ) { ?>
		<table class="table table-condensed table-striped table-bordered">
        <thead>
        	<th width="5%">#</th>
            <th width="60%" style="text-align:justify">Naziv i opis paketa</th>
            <th width="10%">Autor</th>
            <th width="10%">Kolegij</th>
            <th width="15%">Kontrole</th>
        </thead>
        <tbody>
        <?php foreach ($sadrzaji as $sadrzaj) { ?>
        	<tr>
            	<td><img src="<?php echo base_url('stil/moj_stil/Address_Book.png'); ?>" rel="tooltip" title="Sadržaj broj <?php echo $sadrzaj['id'] ?>" /></td>
                <td style="text-align:justify">
                    	<b><a href="#<?php echo $sadrzaj['id'] ?>"><?php echo $sadrzaj['ime'] ?></a></b>
                        <p><?php echo $sadrzaj['opis'] ?></p>
                </td>
                <td><?php echo daj_korisnika($sadrzaj['id_korisnika']); ?></td>
                <td><a href="<?php echo site_url("kolegiji/index/".$sadrzaj['id_kolegija']); ?>"><?php echo daj_ime_kolegija( $sadrzaj['id_kolegija']) ?></a></td>
                <td>
                    <div class="btn-group">
                        <a rel="tooltip" title="Uredi"  href="<?php echo site_url ('upravljanjeSadrzajem/uredi/')."/".$sadrzaj['id']; ?>" class="btn" ><span class="icon icon-edit"></span>&nbsp;</a>
                        <a rel="tooltip" title="Briši" href="<?php echo site_url ('upravljanjeSadrzajem/brisi/')."/".$sadrzaj['id']; ?>" class="btn brisi"><span class="icon icon-trash"></span>&nbsp;</a>
                    </div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        </table>        
        
        <div class="pagination pull-left">
            <?php  echo generiraj_paginaciju("upravljanjeSadrzajem/index", $broj_sadrzaja); ?>
        </div>
        <?php } else { ?>
        <div class="alert alert-info" style="margin-bottom:0;">
        	<h4 class="alert-header">Upozorenje</h4>
            <p>Nema nastavnih sadrzaja, možete dodati novi na linku iznad.</p>
        </div>
        <?php } ?>


    </div>
    
</div>

<?php
$this->load->view('static/footer');
?>