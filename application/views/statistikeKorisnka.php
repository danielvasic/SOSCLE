<?php 
$podaci = array ('naslov' => "Statistike korisnika ". $korisnik['ime']. " " .$korisnik['prezime'], 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'pocetna' => TRUE);
$this->load->view('static/header', $podaci);
?>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span9">
      <ul class="nav nav-tabs">
        <li><a href="<?php echo site_url('profil/pogledaj/'.$korisnik['id']); ?>"><i class="icon icon-user"></i>&nbsp;<?php echo $korisnik['ime']. " " .$korisnik['prezime']; ?></a></li>
        <li class="active"><a href="<?php echo site_url('profil/statistike/'.$korisnik['id']); ?>"><i class="icon icon-tasks"></i>&nbsp;Statistike</a></li>
      </ul>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span9" id="sadrzaj">
      <div class="span3">
        <div class="thumbnail"> <img src="<?php echo base_url('avatari/128x128/'.$korisnik['avatar']); ?>" />
          <div class="caption">
            <h5>Nešto o meni:</h5>
            <p><?php echo $korisnik['opis']; ?></p>
          </div>
        </div>
      </div>
      <div class="span9">
        <h3><?php echo $korisnik['ime'] . " " . $korisnik['prezime']; ?></h3>
        <hr />
        <div class="control-group">
          <label class="control-label">
          <div class="btn-group"> <a href="#" class="btn btn-small btn-primary"><b>Broj postova:</b> <?php echo $broj_postova; ?></span> <a href="#" id="open_stats" name="<?php echo $korisnik['id'] ?>" class="btn btn-small"><i class="icon icon-tasks"></i> Pogledaj statstike</a> </div>
          </label>
          <div id="statistike_ui" class="modal hide fade">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h3>Broj postova</h3>
            </div>
            <div class="modal-body" id="statistike" style="width:500px; height:300px;"></div>
          </div>
        </div>
        <?php if (count ($statistike) > 0) { ?>
        <div class="control-group">
          <label class="control-label"><b>Nastavni sadržaji:</b></label>
          <table class="table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th>#</th>
                <th>Ime lekcije</th>
                <th>Kolegij</th>
                <th>Broj pokušaja</th>
                <th>Vrijeme</th>
                <th>Status</th>
                <th>Prosjek ostvarenih bodova</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($statistike as $statistika) { ?>
              <tr>
                <td><?php echo $statistika['id']; ?></td>
                <td><a href="<?php echo site_url('rte/index/'.$statistika['lekcija']['id']); ?>"> <?php echo $statistika['lekcija']['ime']; ?></a></td>
                <td><a href="<?php echo site_url('kolegiji/index/'.$statistika['kolegij']['id']); ?>"> <?php echo $statistika['kolegij']['ime']; ?></a></td>
                <td><a href="#" class="otvori_pokusaje btn btn-small" data-id="<?php echo $korisnik['id']; ?>" name="<?php echo $statistika['lekcija']['id']; ?>"> <?php echo $statistika['broj_pokusaja'] ?></a></td>
                <td><span class="badge badge-info"><?php echo $statistika['vrijeme']['vrijednost']; ?></span></td>
                <td><span class="badge badge-success"><?php echo daj_status($statistika['status']['vrijednost']); ?></span></td>
                <td><span class="badge badge-warning"><?php echo $statistika['rezultat']; ?></span></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <div id="pokusaji_ui" class="modal hide fade">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h3>Prikaz pokusaja za lekciju <a id="lekcija" href="#"></a></h3>
            </div>
            <ul class="nav nav-tabs" id="navigacija">
              <li class="active" style="margin-left:10px;"><a href="#" id="scorm_vars" class="otvori_pokusaje"<i class="icon icon-user"></i>&nbsp;SCORM podaci</a></li>
              <li><a href="#" class="graph"><i class="icon icon-tasks"></i>&nbsp;Graf</a></li>
            </ul>
            <div class="modal-body" id="pokusaji"> </div>
          </div>
        </div>
        <?php } else { ?>
        <div class="alert alert-info">
          <h4 class="alert-header">Obavijest</h4>
          <p>Nema podataka o lekcijama povezanih sa ovim korisnikom.</p>
        </div>
        <?php } ?>
      </div>
    </div>
      <div class="span3">
    <div class="chat">
      <div class="chat-header">
        <div class="row-fluid">
          <div class="span1" style="position:relative;">
            <button class="dropdown-toggle btn btn-mini" data-toggle="dropdown" style="width:15px; height:15px; padding:0;"><span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="#" class="postavi_status" id="online"><span class="icon-online"></span>&nbsp;Online</a></li>
              <li><a href="#" class="postavi_status" id="zauzet"><span class="icon-zauzet"></span>&nbsp;Zauzet</a></li>
              <li class="divider"></li>
              <li><a href="#" class="postavi_status" id="offline"><span class="icon-offline"></span>&nbsp;Odjavi se sa chata</a></li>
            </ul>
          </div>
          <div class="span9" style="margin-left:10px;"> <a href="#" class=""><?php echo $logiraniKorisnik['ime'] . " " . $logiraniKorisnik['prezime'] ?></a> </div>
          <div class="span1" style="margin-top:2px;"> <span id="status_korisnika" class="icon-<?php echo $logiraniKorisnik['status']; ?>"></span> </div>
        </div>
      </div>
      <div class="chat-body">
        <div class="chat-disabled" <?php if ($logiraniKorisnik['status'] == 'offline') { ?>style="display:block;"<?php } ?>>
          <div class="chat-loader" <?php if ($logiraniKorisnik['status'] == 'offline') { ?>style="display:none;"<?php } ?>></div>
        </div>
        <div id="chat-body"></div>
      </div>
    </div>
  </div>
</div>
<div id="chatovi"></div>
<script src="<?php echo base_url('stil/js/highcharts.js'); ?>"></script> 
<script src="<?php echo base_url('stil/js/modules/exporting.js'); ?>"></script> 
<script type="text/javascript">
$("body").prepend($("<div></div>").attr('id', 'big_loader'))
$(function () {
    var chart;
    $("#open_stats").click(function() {
		var id = $(this).attr('name');
		$("#big_loader").show();
    	$.getJSON(CI_ROOT+'index.php/profil/postovi_statistike/'+id, {}, function (data) {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'statistike',
					type: 'line',
					marginRight: 130,
					marginBottom: 25
				},
				title: {
					text: 'Broj postova korisnika',
					x: -20 //center
				},
				subtitle: {
					text: 'Od pocetka vremena',
					x: -20
				},
				xAxis: {
					categories: data.podaci.categories
				},
				yAxis: {
					title: {
						text: 'Broj postova'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+
							this.x +': '+ this.y +' postova';
					}
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'top',
					x: -10,
					y: 100,
					borderWidth: 0
				},
				series: [data.podaci]
			});
			chart.setSize($("#statistike").width(), $("#statistike").height());
			$("#statistike_ui").modal('show');
			$("#big_loader").hide();
			
		});
    }); 
});

$(".graph").click(function(e) {
	var id = $(this).attr('name');
	var id_korisnika = $(this).attr('data-id');
	
	$("#big_loader").show();
	
	$("#navigacija li").removeClass('active');
	$(this).parent('li').addClass('active');
	$.getJSON(CI_ROOT+'index.php/profil/graf_pokusaja/', {'id_sadrzaja' : id, 'id_korisnika' : id_korisnika}, function (data) { 
		chart = new Highcharts.Chart({
				chart: {
					renderTo: 'pokusaji',
					type: 'line',
					marginRight: 130,
					marginBottom: 25
				},
				title: {
					text: 'Postignuti rezultati po pokusijima',
					x: -20 //center
				},
				subtitle: {
					text: data.korisnik,
					x: -20
				},
				xAxis: {
					categories: data.categories
				},
				yAxis: {
					title: {
						text: 'Broj bodova'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+
							this.x +': '+ this.y +' bodova';
					}
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'top',
					x: -10,
					y: 100,
					borderWidth: 0
				},
				series: [data.podaci]
			});
			$("#big_loader").hide();
	
	});
});

$(".otvori_pokusaje").click(function (e) {
	var id = $(this).attr('name');
	var id_korisnika = $(this).attr('data-id');
	$("#navigacija li").removeClass('active');
	$("#scorm_vars").attr('name', id).attr('data-id', id_korisnika).bind('click', function (e) {$(this).parent('li').addClass('active');}).parent('li').addClass('active');
	$(".graph").attr('name', id).attr('data-id', id_korisnika);
	$("#big_loader").show();
	$.getJSON(CI_ROOT+'index.php/profil/statistike_pokusaja/', {'id_sadrzaja' : id, 'id_korisnika' : id_korisnika}, function (data) {
		$("#pokusaji").html("");
		for (i in data) {	
			if (i == 0) $("#lekcija").attr('href', CI_ROOT+'index.php/rte/index/'+data[i].sadrzaj_id).html(data[i].sadrzaj_ime);
			$("#pokusaji").append(napravi_tablicu_pokusaja (data[i]));
			$("#pokusaji").append($("<br />"));
		}
		
		$("#big_loader").hide();
		$("#pokusaji_ui").modal('show');
	});
});

function napravi_tablicu_pokusaja (pokusaj) {
	var table = $("<table></table>").addClass('table').addClass('table-striped').addClass('table-bordered')
	var _tr1 = $("<tr></tr>");
	var _tr2 = $("<tr></tr>");
	
	var _th1 = $("<th></th>").attr("colspan", "2").html($("<h3></h3>").html("Pokusaj br. " + pokusaj.id));
	_tr1.html(_th1);
	table.html(_tr1);
	
	var _th2 = $("<th></th>").attr("colspan", "2").html($("<h4></h4>").html("SCORM varijable"));
	_tr2.html(_th2);
	table.append(_tr2);
	var varijable = pokusaj.vrijednosti;
	
	for (i in varijable) {
		if (i == 0) {
			var _tr3 = $("<tr></tr>");
			var _td1 = $("<td></td>").attr('colspan', '2').html(varijable[i].sco_title);
			_tr3.html(_td1);
			table.append(_tr3);
		}
		var _tr4 = $("<tr></tr>");
		
		var _td2 = $("<td></td>").html(varijable[i].element);
		var _td3 = $("<td></td>").html(varijable[i].vrijednost);
		_tr4.append (_td2);
		_tr4.append (_td3);
		table.append(_tr4);	
	}
    return table;
}
</script> 
<script type="text/javascript" src="<?php echo base_url('stil/chat.js'); ?>"></script>
<?php 
$this->load->view ("static/footer");
?>
