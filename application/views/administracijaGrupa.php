<?php 
$podaci = array ('naslov' => "Upravljanje grupama", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'grupe');
$this->load->view('static/header', $podaci);
?>
<div class="container-fluid">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="<?php echo site_url('upravljanjeGrupama/index') ?>"><i class="icon icon-list"></i>&nbsp;Lista grupa</a></li>
    	<li><a href="<?php echo site_url('upravljanjeGrupama/dodaj') ?>"><i class="icon icon-plus-sign"></i>&nbsp;Nova grupa</a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    <?php if(count($grupe) > 0) { ?>
		<table class="table table-bordered table-striped">
        <thead>
        	<tr>
            	<th width="5%">#</th>
                <th width="10%">Ime grupe</th>
                <th width="45%">Opis grupe</th>
                <th width="10%">Broj sudionika</th>
                <th width="10%">Datum stvaranja</th>
                <th width="20%">Kontrole</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($grupe as $grupa) { ?>
        	<tr id="grupa<?php echo $grupa['id']; ?>">
            	<td><?php echo $grupa['id']; ?></td>
                <td><?php echo $grupa['ime']; ?></td>
                <td style="text-align:justify;"><p><?php echo $grupa['opis']; ?></p></td>
                <td id="broj_korisnika_<?php echo $grupa['id']; ?>"><?php echo $grupa['broj_korisnika']; ?></td>
                <td><?php echo $grupa['vrijeme'] ?></td>
                <td>
                    <div class="btn-group-wrap">
                        <div class="btn-group">
                            <a class="btn" rel="tooltip" title="Uredi" href="<?php echo site_url('upravljanjeGrupama/uredi/' . $grupa['id']); ?>"><i class="icon-edit"></i>&nbsp;</a>
                            <a class="btn brisi" rel="tooltip" title="Briši" href="<?php echo site_url('upravljanjeGrupama/pobrisi/' . $grupa['id']); ?>"><i class="icon-trash"></i>&nbsp;</a>
                            <a class="btn dropdown-toggle" data-toggle='dropdown' href="javascript:void(0)"><i class="caret"></i></a>
                            <ul class="dropdown-menu">
                            	<li>
                                	<a href="javascript:void(0)" name="<?php echo $grupa['id']; ?>" class="dodaj_korisnike">
                                    	<i class="icon icon-plus-sign"></i>&nbsp;Dodaj korisnike
                                    </a>
                                </li>
                                <li>
                                	<a href="javascript:void(0)" name="<?php echo $grupa['id']; ?>" class="brisi_korisnike">
                                    	<i class="icon icon-trash"></i>&nbsp;Briši korisnike
                                    </a>                                
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        </table>
		<?php echo generiraj_paginaciju ($path, $brojac); ?>
        <?php } else { ?>
        <div class="alert alert-info" style="margin-bottom:0;">
        	<h4 class="alert-header">Upozorenje</h4>
            <p>Još nema nijedna grupa, možete dodati jednu, na linku iznad.</p>
        </div> 
        <?php } ?>
        <div id="myModal" class="modal hide fade">
        	<form class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Dodaj korisnike u grupu</h3>
                </div>
                <div class="modal-body">   

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary posaljiKorisnike" type="submit">Dodaj korisnike</button>
                </div>
            </form>
        </div>
        
        <div id="brisi_modal" class="modal hide fade">
        	<form class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Brisi korisnike iz grupe</h3>
                </div>
                <div class="modal-body">   

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary brisiKorisnike" type="submit">Briši korisnike</button>
                </div>
            </form>
        </div>

		
    </div>
    
</div>
<script type="text/javascript">
var id_grupe;
$("body").prepend($("<div></div>").attr('id', "big_loader"));

$(".dodaj_korisnike").click (function () {
		id_grupe = $(this).attr ('name');
		$("#big_loader").fadeIn();
		
		$.getJSON('<?php echo site_url('upravljanjeGrupama/daj_korisnike_za_dodati') ?>', {'id' : id_grupe}, function (data) {
			var global_div = $("<div></div>"); 
			for (i in data) {
				var input = $('<input />').attr('name', 'korisnik_cbx').attr('type', 'checkbox').val(data[i].id);
				var ancor = $('<a></a>').attr('href', decodeURIComponent(data[i].url)).html(data[i].puno_ime);
				var label = $('<label></label>').addClass('checkbox');
				label.append(input);
				label.append(ancor);
				global_div.append(label);
			}
			$('.modal-body').html(global_div);
			$("#big_loader").fadeOut();
			$("#myModal").modal('show');
		});
});

$(".brisi_korisnike").click (function () {
		id_grupe = $(this).attr ('name');
		$("#big_loader").fadeIn();
		
		$.getJSON('<?php echo site_url('upravljanjeGrupama/daj_korisnike_za_brisati') ?>', {'id' : id_grupe}, function (data) {
			var global_div = $("<div></div>"); 
			for (i in data) {
				var input = $('<input />').attr('name', 'korisnik_cbx').attr('type', 'checkbox').val(data[i].id);
				var ancor = $('<a></a>').attr('href', decodeURIComponent(data[i].url)).html(data[i].puno_ime);
				var label = $('<label></label>').addClass('checkbox');
				label.append(input);
				label.append(ancor);
				global_div.append(label);
			}
			$('#brisi_modal .modal-body').html(global_div);
			$("#big_loader").fadeOut();
			$("#brisi_modal").modal('show');
		});
});


$(".posaljiKorisnike").click (function () {
		var polja=[];
		$(":checkbox:checked").each(function (i) {
			polja[i] = $(this).val();
			$(this).remove();
		});		
		var polje = polja.join(",");
		
		if (id_grupe) {
			$("#big_loader").fadeIn();
			$.post('<?php echo site_url('upravljanjeGrupama/dodaj_korisnike') ?>', {'korisnici' : polje, 'grupa' : id_grupe}, function (data) {
				if (data.uspjeh == 1) {
					$('#myModal').modal('hide');
					$('#broj_korisnika_'+id_grupe).html(data.broj_korisnika);
				} else {
					alert (data.greska);	
				}
				$("#big_loader").fadeOut();
			}, 'json')	
		}
		return false;
								
}); 

$(".brisiKorisnike").click (function () {
		var polja=[];
		$(":checkbox:checked").each(function (i) {
			polja[i] = $(this).val();
			$(this).remove();
		});		
		var polje = polja.join(",");
		
		if (id_grupe) {
			$("#big_loader").fadeIn();
			$.post('<?php echo site_url('upravljanjeGrupama/brisi_korisnike') ?>', {'korisnici' : polje, 'grupa' : id_grupe}, function (data) {
				if (data.uspjeh == 1) {
					$('#brisi_modal').modal('hide');
					$('#broj_korisnika_'+id_grupe).html(data.broj_korisnika);
				} else {
					alert (data.greska);	
				}
				$("#big_loader").fadeOut();
			}, 'json')	
		}
		return false;
								
}); 
</script>

<?php
$this->load->view('static/footer');
?>