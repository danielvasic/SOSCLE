<?php 
$podaci = array ('naslov' => "Forum " . $forum['ime'], 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'forumi' => TRUE);
$this->load->view('static/header', $podaci);
?>
<div class="container-fluid" >
	<ul class="nav nav-tabs">
    	<li class="active"><a href="#"><i class="icon icon-list"></i>&nbsp; <?php echo $forum['ime']; ?></a></li>
    </ul>
    <div class="tab-content" id="sadrzaj">
    	<?php if ($forum['status'] == 'otkljucan') { ?>
        <a class="btn btn-primary btn-large pull-right" id="otvoriProzor" href="javascript:void(0)" style="margin-bottom:10px;">
        <span class="icon icon-plus"></span> Dodaj temu</a>
        <div id="myModal" class="modal hide fade">
        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Dodaj temu</h3>
            </div>
            <form class="form-horizontal">
            	<div class="modal-body" id="tijelo-prozora">
                	<div class="alert alert-error" id="greska_wrapper">
                    	<b>Dododila se greska : </b>
                        <p id="greska"></p>
                    </div>
                	<div class="control-group">
                    	<label class="control-label">Ime teme </label>
                        <div class="controls">
                        	<input type="text" name="ime" class="input input-large" placeholder="Ovdje upišite vaš naziv" />
                        </div>
                    </div>
                    <div class="control-group">
                    	<label class="control-label">Opis teme </label>
                        <div class="controls">
                        	<textarea name="opis" id="opis_teme" rows="3" class="input input-large" placeholder="Ovdje upišite vaš opis"></textarea>
                        </div>
                    </div>
                </div>
            	<div class="modal-footer">
                    <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-primary dodaj_temu"><span class="icon-white icon-plus"></span> Dodaj temu</button>
                            <button type="reset" class="btn">Poništi</button>
                	</div>
                </div>
            </form>
        </div>
        <br clear="all" />
        <?php } else { ?>
        <div class="alert alert-info">
        	<a class="btn btn-primary btn-large pull-right disabled" id="otvoriProzor" href="javascript:void(0)" style="margin-bottom:10px;">
        <span class="icon icon-plus"></span> Dodaj temu</a>
        	<h4 class="alert-heading">Upozorenje!</h4>
            <p>Forum je zaključan nemožete dodavati više tema.</p>
        </div>
        <?php } ?>
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url ('forumi'); ?>">Forumi</a></li><span class="divider">/</span>
            <li><?php echo $forum['ime']; ?></li>
        </ul>
        <?php if (count ($teme) > 0) { ?>
		<table id="tablica" class="table table-condensed table-striped">
        <thead>
        	<th width="5%"></th>
            <th width="55%">Naziv i opis teme</th>
            <th width="10%">Broj postova</th>
            <th width="10%">Vrijeme stvaranja</th>
            <th width="10%">Autor</th>
            <th width="10%">Zadnji post</th>
        </thead>
        <tbody>
        <?php foreach ($teme as $tema) { ?> 
        	<tr id="tema<?php echo $tema['id'] ?>">
            	<td>
                <?php if ($tema['status'] == 'otkljucan') { ?>
                <img src="<?php echo base_url ('stil/moj_stil/Chat.png'); ?>" />
                <?php } else { ?>
                <img src="<?php echo base_url ('stil/moj_stil/Chat_Lock.png'); ?>" />
				<?php } ?>  
                </td>
                <td style="text-align:justify">
                    	<b><a href="<?php echo site_url('postovi/index/'.$tema['id']); ?>"><?php echo $tema['ime'] ?></a></b>
                        <p><?php echo $tema['opis'] ?></p>
                </td>
                
                <td><span class="label label-danger"><?php echo broj_postova ($tema['id']); ?></span></td>
                <td><?php echo $tema['datum'] ?></td>
                <td><?php echo daj_korisnika($tema['id_korisnika']); ?></td>
                <td>
                	 <?php echo (zadnji_post($tema['id'])); ?>            
                </td>
            </tr>
        <?php } ?> 
        </tbody>
        </table>
		 <?php echo generiraj_paginaciju ('teme/index/'.$forum['id'], $broj_tema, 4); ?>
         <?php } else { ?>
         <p class="alert alert-info"><b>Info:</b> Nema dodanih tema u ovom forumu, možete dodati novu temu.</p>
         <?php } ?>
    </div>
    
</div>

<script type="text/javascript">
var stranica = <?php echo $stranica; ?>;
$('body').prepend($("<div></div>").attr('id', 'big_loader')); 
$('#otvoriProzor').click(function () {
	$("#myModal").modal('show');
	$("#greska_wrapper").hide();
});
$(".dodaj_temu").click(function (e) {
	var ime = $('input[name=ime]').val();
	var nice = new nicEditors.findEditor('opis_teme');
	var opis = nice.getContent();
	
	$("#big_loader").fadeIn();
	if (ime != "" && opis != "") {
		$.post('<?php echo site_url ('teme/dodaj/'.$forum['id']); ?>', {'ime' : ime, 'opis' : opis}, function (data) {
		if(data.greska == 0)  {
			if (stranica == 0) {
					var table = $('#tablica');
					var row = $('<tr></tr>').css({'display' : 'none'});
					var slika = $('<td></td>').html($("<img />").attr('src', decodeURIComponent(data.url_slike)));
					var ime_opis = $("<td></td>").css({'text-align':'justify'}).html (
												$('<b></b>').html(
														$('<a></a>').attr('href', decodeURIComponent(data.url_teme)).html(ime)
													)
												).append($('<p></p>').html(opis)).width('825px');
					var broj_postova = $("<td></td>").html('0');
					var datum = $("<td></td>").html(data.datum);
					var autor = $("<td></td>").html(
						$("<a></a>").attr('href', decodeURIComponent(data.url_korisnika)).html(data.ime_korisnika)
					);
					var alert_p_tag = $("<p></p>");
					alert_p_tag.addClass('alert');
					alert_p_tag.addClass('alert-warning');
					alert_p_tag.html('Nema postova');
					alert_p_tag.fadeIn();
					var zadnji_post = $("<td></td>").html(alert_p_tag);
				
					row.append(slika);
					row.append(ime_opis);
					row.append(broj_postova);
					row.append(datum);
					row.append(autor);
					row.append(zadnji_post);
					
					if (table.length > 0) {
						table.prepend (row);
					} else {
						var row_head = $("<tr></tr>");
						
						var slika_head = $("<th></th>").html("#").width('5%');
						var ime_opis_head = $("<th></th>").html("Ime i opis teme").width('55%');
						var broj_postova_head = $("<th></th>").html("Broj postova").width('10%');	
						var datum = $("<th></th>").html("Vrijeme stvaranja").width('10%');
						var autor_head = $("<th></th>").html("Autor").width('10%');
						var zadnji_post_head = $("<th></th>").html("Zadnji post").width('10%');
						
						row_head.append (slika_head);
						row_head.append (ime_opis_head);
						row_head.append(broj_postova_head);
						row_head.append(datum);
						row_head.append(autor_head);
						row_head.append(zadnji_post_head);
						
						var tablica = $("<table></table>").attr('id', 'tablica').addClass('table');
						var thead = $("<thead></thead>").html(row_head);
						
						tablica.html(thead);
						tablica.append(row);
						$('.alert-info').fadeOut();
						$('#sadrzaj').append(tablica);
					}
					$('input[name=ime]').val("");
					$('textarea[name=opis]').val("");
					$('#myModal').modal('hide');
					$("#myModal").bind('hidden', function () {
						row.fadeIn();	
					}); 
				} else {
					var success = $("<div></div>").addClass('alert').addClass ('alert-success');
					$('#sadrzaj').prepend(success);
					$("#myModal").modal('hide');
					$("#myModal").bind('hidden', function () {
						success.html('<b>Uspjeh!</b> Dodali ste novu temu kliknite <a href="<?php echo site_url('teme/index/'.$forum['id']); ?>">ovdje</a> da biste je vidjeli.')
						success.fadeIn();
					});
					
				} 	
			} else {
				$('#greska').html(data.tekst);
				$("#greska_wrapper").show();
			}
			$("#big_loader").fadeOut();
		}, 'json');
	} else {
		alert ("Niste unjeli sva potrebna polja.");
		$("#big_loader").fadeOut();	
	}
	return false;
});
</script>
<?php 
$this->load->view ('static/footer');
?>