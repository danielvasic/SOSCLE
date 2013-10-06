<?php 
$podaci = array ('naslov' => "Upravljanje korisnicima", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'korisnici');
$this->load->view('static/header', $podaci);
?>

<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="<?php echo site_url('upravljanjeKorisnicima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista korisnika</a></li>
    	<li><a href="<?php echo site_url('upravljanjeKorisnicima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Novi korisnik</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    	<?php if (isset($error)) { ?>
        <div class="alert alert-error">
        	<button type="button" class="close" data-dismiss="alert">×</button>
           <p><b>Dogodila greška:</b></p>
		   <p><?php echo $tekst; ?></p>
        </div>        	
        <?php } ?>
        <?php if (count($korisnici) > 0) { ?>
		<table class="table table-bordered table-striped">
        <thead>
        	<tr>
            	<th>#</th>
                <th>Profilna slika</th>
                <th>Ime i prezime</th>
                <th>Email</th>
                <th>Grad</th>
                <th>Kontrole</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($korisnici as $korisnik) : ?>
        	<tr id="korisnik<?php echo $korisnik['id'] ?>">
            	<td><?php echo $korisnik['id'] ?></td>
                <td><img src="<?php echo base_url('/avatari/48x48/'.$korisnik['avatar']); ?>"  rel="tooltip" title="<?php echo $korisnik['uloga'] ?>" /></td>
                <td><a href="<?php echo site_url('profil/pogledaj/'.$korisnik['id']); ?>"><?php echo $korisnik['ime'] ?> <?php echo $korisnik['prezime'] ?></a></td>
                <td><a href="mailto:<?php echo $korisnik['email'] ?>"><?php echo $korisnik['email'] ?></a></td>
                <td><?php echo $korisnik['grad'] ?></td>
                <td>
                    <div class="btn-group-wrap">
                        <div class="btn-group">
                            <a rel="tooltip" title="Uredi" class="btn" href="<?php echo site_url('upravljanjeKorisnicima/uredi/'.$korisnik['id']); ?>"><i class="icon-edit"></i>&nbsp;</a>
                            <a class="btn brisi" rel="tooltip" title="Briši" href="<?php echo site_url('upravljanjeKorisnicima/brisi/'.$korisnik['id']); ?>"><i class="icon-trash"></i>&nbsp;</a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>

        <?php echo generiraj_paginaciju($path, $brojac); ?>
        <?php } else { ?>
        <div class="alert alert-info">
        	<h4 class="alert-header">Info</h4>
            <p>Nema korisnika, možete dodati novi na linku iznad.</p>
        </div>
        <?php }?>
    </div>
    
</div>
<?php
$this->load->view('static/footer');
?>
