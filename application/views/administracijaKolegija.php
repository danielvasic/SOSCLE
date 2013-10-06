<?php 
$podaci = array ('naslov' => "Upravljanje kolegijima", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'kolegiji');
$this->load->view('static/header', $podaci);
?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="<?php echo site_url ('upravljanjeKolegijima/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista kolegija</a></li>
    	<li><a href="<?php echo site_url ('upravljanjeKolegijima/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Dodaj kolegij</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
        <?php if (count ($kolegiji) > 0) { ?> 
		<table class="table table-bordered table-striped">
        <thead>
        	<tr>
            	<th width="5%">#</th>
                <th width="10%">Ime</th>
                <th width="30%">Opis</th>
                <th width="5%">Broj lekcija</th>
                <th width="10%">Datum</th>
                <th width="10%">Autor</th>
                <th width="5%">Broj grupa</th>
                <th width="25%">Kontrole</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($kolegiji as $kolegij) : ?>
        	<tr>
            	<td><?php echo $kolegij['id']; ?></td>
                <td><a href="<?php echo site_url('kolegiji/index/'.$kolegij['id']); ?>" target="_blank"><?php echo $kolegij['ime']; ?></a></td>
                <td width="250px">	
					<?php echo $kolegij['opis']; ?>
                </td>
                <td><?php echo broj_sadrzaja($kolegij['id']); ?></td>
                <td><?php echo $kolegij['datum']; ?></td>
                <td><?php echo daj_korisnika ($kolegij['id_korisnika']); ?></td>
                <td>
                	<?php echo broj_grupa($kolegij['id']); ?>
                </td>
                <td>
                    <div class="btn-group-wrap">
                        <div class="btn-group">
                            <a class="btn" rel="tooltip" title="Uredi" href="<?php echo site_url('upravljanjeKolegijima/uredi/'.$kolegij['id']); ?>"><i class="icon-edit"></i> &nbsp;</a>
                            <a class="btn brisi" rel="tooltip" title="Briši" href="<?php echo site_url('upravljanjeKolegijima/brisi/'.$kolegij['id']); ?>"><i class="icon-trash"></i>&nbsp;</a>
                             <a class="btn dropdown-toggle" data-toggle='dropdown' href="javascript:void(0)"><i class="caret"></i></a>
                            <ul class="dropdown-menu">
                            	<li>
                                	<a href="javascript:void(0)" name="<?php echo $kolegij['id']; ?>" class="dodaj_grupe">
                                    	<i class="icon icon-plus-sign"></i>&nbsp;Dodaj grupe
                                    </a>
                                </li>
                                <li>
                                	<a href="javascript:void(0)" name="<?php echo $kolegij['id']; ?>" class="brisi_grupe">
                                    	<i class="icon icon-trash"></i>&nbsp;Briši grupe
                                    </a>                                
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
 		<div id="dodajModal" class="modal hide fade">
        	<form class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Dodaj kolegij u grupu</h3>
                </div>
                <div class="modal-body" id="dodajBody">                            

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" id="dodaj">Dodaj</button>
                </div>
            </form>
        </div>
        
        <div id="brisiModal" class="modal hide fade">
        	<form class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Briši kolegij iz grupe</h3>
                </div>
                <div class="modal-body" id="brisiBody">                            

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" id="brisi">Briši</button>
                </div>
            </form>
        </div>
        <?php echo generiraj_paginaciju ('upravljanjeKolegijima/index', $broj_stavki); ?>
        <?php } else { ?>
        <div class="alert alert-info" style="margin-bottom:0;">
        	<h4 class="alert-header">Upozorenje</h4>
            <p>Još nema nijedan kolegij, možete dodati jedan, na linku iznad.</p>
        </div> 
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
	$("body").prepend($("<div></div>").attr('id', 'big_loader'));
	
	$(".dodaj_grupe").click(function(e) {
        id_kolegija = $(this).attr('name');
		$("#big_loader").show();
		$.getJSON('<?php echo site_url ('upravljanjeKolegijima/daj_za_dodati/') ?>/'+id_kolegija, {}, function (data) {
			for (i in data) {
				if (i == 0) 
					$('#dodajBody').html(napravi_checkbox(data[i].ime, data[i].id));
				else
					$('#dodajBody').append(napravi_checkbox(data[i].ime, data[i].id));		
			}
			$("#dodaj").attr('name', id_kolegija);
			$("#dodajModal").modal('show');
			$("#big_loader").hide();
		});
    });

	$("#dodaj").click(function(e) {
		$("#big_loader").show();
		var id_kolegija = $(this).attr('name');		
		var polje = [];
			$(".cbox:checked").each(function(index, element) {
			polje[index] = $(this).val();
			$(this).remove();
		});
					
			
		polje = polje.join(",");
		$.ajax({
			url: '<?php echo site_url('upravljanjeKolegijima/dodaj_grupe/')?>/'+id_kolegija, 
			data: {'grupe' : polje},
			type: "POST", 
			async: false,
			dataType:'json',
			success: function (data) {
				$("#kolegij_br_grupa_"+id_kolegija).html(data.broj_grupa);
				$("#dodajModal").modal('hide');
				$("#big_loader").hide();
			}
		});
		return false;
	});			
	
	$(".brisi_grupe").click(function (e) {
		var id_kolegija = $(this).attr('name');
		$("#big_loader").show();
		$.getJSON('<?php echo site_url ('upravljanjeKolegijima/daj_za_brisati/') ?>/'+id_kolegija, {}, function (data) {
			for (i in data) {
				if (i == 0) 
					$('#brisiBody').html(napravi_checkbox(data[i].ime, data[i].id));
				else
					$('#brisiBody').append(napravi_checkbox(data[i].ime, data[i].id));		
			}
			$("#brisi").attr('name', id_kolegija)
			$("#brisiModal").modal('show');
			$("#big_loader").hide();
		});
	});
	
	$("#brisi").click(function(e) {
		$("#big_loader").show();
		var id_kolegija = $(this).attr('name');	
		var polje = [];
		$(".cbox:checked").each(function(index, element) {
			polje[index] = $(this).val();
			$(this).remove();
		});
			
				
		polje = polje.join(",");
		$.ajax({
			url: '<?php echo site_url('upravljanjeKolegijima/brisi_grupe/')?>/'+id_kolegija, 
			data: {'grupe' : polje},
			type: "POST", 
			async: false,
			dataType:'json',
			success: function (data) {
				$("#kolegij_br_grupa_"+id_kolegija).html(data.broj_grupa);
				$("#brisiModal").modal('hide');
				$("#big_loader").hide();
			}
		});
		return false;
	});
	
	function napravi_checkbox (ime, id) {
		var labela = $("<label></label>").addClass('checkbox');
		var input_polje = $("<input />").attr('type', 'checkbox').attr('name', id).val(id).addClass('cbox');
		var ime_polje = $("<p></p>").html(ime);
		labela.html(input_polje);
		labela.append(ime_polje);
		return labela;
	}
</script>
<?php
$this->load->view('static/footer');
?>