<?php 
$podaci = array ('naslov' => "Profil korisnika ". $korisnik['ime']. " " .$korisnik['prezime'], 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'pocetna' => TRUE);
$this->load->view('static/header', $podaci);
?>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span9">
      <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo site_url('profil/pogledaj/'.$korisnik['id']); ?>"><i class="icon icon-user"></i>&nbsp;<?php echo $korisnik['ime']. " " .$korisnik['prezime']; ?></a></li>
        <li><a href="<?php echo site_url('profil/statistike/'.$korisnik['id']); ?>"><i class="icon icon-tasks"></i>&nbsp;Statistike</a></li>
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
        <h3><?php echo $korisnik['ime']. " " .$korisnik['prezime']; ?></h3>
        <hr />
        <fieldset>
          <div class="control-group">
            <div class="control-label"> <b>Uloga</b> </div>
            <div class="controls"><?php echo $korisnik['uloga']; ?></div>
          </div>
          <div class="control-group">
            <div class="control-label"> <b>E-mail</b> </div>
            <div class="controls"><a href="mailto:<?php echo $korisnik['email']; ?>"><?php echo $korisnik['email']; ?></a></div>
          </div>
          <div class="control-group">
            <div class="control-label"> <b>Grad</b> </div>
            <div class="controls"><?php echo $korisnik['grad']; ?></div>
          </div>
          <?php if ($korisnik['uloga'] != "Administrator") { ?>
          <div class="control-group">
            <div class="control-label"> <b>Izdvojene grupe</b> </div>
            <div class="controls">
              <?php 
						
						if (isset($grupe)) { foreach ($grupe as $grupa) {
					?>
              <span class="badge badge-info"><?php echo $grupa['ime'] ?></span>
              <?php 
					}}
					?>
            </div>
          </div>
          <div class="control-group">
            <div class="control-label"> <b>Kolegiji na koje je upisan</b> </div>
            <div class="controls">
              <?php 
						if (isset ($kolegiji)) {foreach ($kolegiji as $kolegij) {
					?>
              <a href="<?php echo $kolegij['url'] ?>"><?php echo $kolegij['ime'] ?></a>
              <?php 
					}}
					?>
            </div>
          </div>
          <?php } ?>
          <div class="control-group">
            <div class="control-label"> <b>Zadnji pristup sustavu</b> </div>
            <div class="controls">
              <p><?php echo $korisnik['zadnja_aktivnost']; ?></p>
            </div>
          </div>
        </fieldset>
        <h3>Nedavne aktivnosti</h3>
        <hr />
        <ul class="nav nav-tabs" id="navigacija">
          <li class="active"><a href="#navigacija" id="daj_lekcije">Lekcije</a></li>
          <li class="dropdown" id="nav-padajuca-lista"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ostale aktivnosti <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#navigacija" id="daj_forume"><span class="icon icon-comment"></span> Dodani forumi</a></li>
              <li><a href="#navigacija" id="daj_teme"><span class="icon icon-list-alt"></span> Dodane teme</a></li>
              <li><a href="#navigacija" id="daj_postove"><span class="icon icon-pencil"></span> Dodani postovi</a></li>
            </ul>
          </li>
        </ul>
        <div id="aktivnosti" class="tab-content">
          <?php foreach ($lekcije as $aktivnost) { ?>
          <p style="padding:5px;"> <a href="<?php site_url('profil/pogledaj/'.$aktivnost['korisnik']['id']); ?>"><?php echo $aktivnost['korisnik']['ime']; ?></a> u kolegiju <a href="<?php echo site_url('kolegiji/index/'.$aktivnost['kolegij']['id']); ?>"><?php echo $aktivnost['kolegij']['ime']; ?></a> <span class="label label-info"><?php echo $aktivnost['status']; ?></span>&nbsp; <a href="<?php echo site_url('rte/index/'.$aktivnost['lekcija']['id']); ?>"> <?php echo $aktivnost['lekcija']['ime'] ?></a> sa rezultatom <span class="label label-important"><?php echo $aktivnost['rezultat'] ?></span> <span class="ago" title="<?php echo $aktivnost['datum'] ?>"></span> </p>
          <?php } ?>
        </div>
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
</div>
<div id="chatovi"></div>
<script type="text/javascript" src="<?php echo base_url('js/chat.js'); ?>"></script> 
<script type="text/javascript">
$("body").ajaxSuccess (function () {
	$(".ago").timeago();	
});
$("body").prepend($("<div></div>").attr('id', 'big_loader'));

function napravi_post_aktivnost  (ime_korisnika, url_korisnika, ime_posta, url_posta, id_posta, ime_teme, url_teme, id_teme, datum) {
	var glavni_wrapper = $("<p></p>").css({'padding':'5px'});
	var korisnik = $("<a></a>").attr('href', url_korisnika).html(ime_korisnika);
	var post = $("<a></a>").attr('href', decodeURIComponent(url_posta)).html(ime_posta);
	var tema = $("<a></a>").attr('href', decodeURIComponent(url_teme)).html(ime_teme);
	glavni_wrapper.html(korisnik).append (" je u temi ").append (tema).append (" postavio novi odgovor ").append (post).append('&nbsp;').append($("<span></span>").addClass('ago').attr('title', datum));
	return glavni_wrapper;
}

function napravi_lekciju_aktivnost (ime_korisnika, id_korisnika, id_kolegija, ime_kolegija, id_lekcije, ime_lekcije, status_lekcije, rezultat_lekcije, datum) {
	var glavni_wrapper = $("<p></p>").css({'padding':'5px'});
	var korisnik = $("<a></a>").attr('href', CI_ROOT + '/index.php/profil/pogledaj/'+id_korisnika).html(ime_korisnika);
	var kolegij = $("<a></a>").attr('href', CI_ROOT + '/index.php/rte/index/' + decodeURIComponent(id_kolegija)).html(ime_kolegija+"&nbsp;");
	var lekcija = $("<a></a>").attr('href', CI_ROOT + '/index.php/rte/index/' + decodeURIComponent(id_lekcije)).html("&nbsp;"+ime_lekcije);
	var status = $("<span></span>").addClass('label').addClass('label-info').html(status_lekcije);
	var rezultat = $("<span></span>").addClass('label').addClass('label-important').html(rezultat_lekcije);
	glavni_wrapper.html(korisnik).append (" u kolegiju ").append (kolegij).append (status).append (lekcija).append(' sa rezultatom ').append (rezultat).append("&nbsp;").append($("<span></span>").addClass('ago').attr('title', datum));
	return glavni_wrapper;	
}

function napravi_temu_aktivnost (ime_korisnika, url_korisnika, ime_teme, url_teme, id_teme, datum) {
	var glavni_wrapper = $("<p></p>").css({'padding':'5px'});
	var korisnik = $("<a></a>").attr('href', url_korisnika).html(ime_korisnika);
	var tema = $("<a></a>").attr('href', decodeURIComponent(url_teme)).html(ime_teme);
	glavni_wrapper.html(korisnik).append (" napravio novu temu pod nazivom ").append (tema).append('&nbsp;').append($("<span></span>").addClass('ago').attr('title', datum));
	return glavni_wrapper;
}

function napravi_forum_aktivnost (ime_korisnika, url_korisnika, ime_foruma, url_foruma, id_foruma, datum) {
	var glavni_wrapper = $("<p></p>").css({'padding':'5px'});
	var korisnik = $("<a></a>").attr('href', url_korisnika).html(ime_korisnika);
	var tema = $("<a></a>").attr('href', decodeURIComponent(url_foruma)).html(ime_foruma);
	glavni_wrapper.html(korisnik).append (" napravio novi forum pod nazivom ").append (tema).append('&nbsp;').append($("<span></span>").addClass('ago').attr('title', datum));
	return glavni_wrapper;	
}

$("#daj_postove").click(function(e) {
	$("#big_loader").show();
	$("#navigacija li").removeClass("active");
	$("#nav-padajuca-lista").addClass('active');
    $.getJSON('<?php echo site_url('profil/postovi/'.$korisnik['id'])?>', {}, function (data) {
			var postovi = $("<div></div>");
			for (i in data) {
				postovi.append (napravi_post_aktivnost(data[i].ime_korisnika, data[i].url_korisnika, data[i].ime_posta, data[i].url_posta, data[i].id_posta, data[i].ime_teme, data[i].url_teme, data[i].id_teme, data[i].datum));
			}
			$("#aktivnosti").html(postovi);
			$("#big_loader").hide();
	});
});

$("#daj_lekcije").click(function(e) {
	$("#big_loader").show();
	$("#navigacija li").removeClass("active");
	$(this).parent().addClass('active');
    $.getJSON('<?php echo site_url('profil/lekcije/'.$korisnik['id'])?>', {}, function (data) {
			var lekcije = $("<div></div>");
			for (i in data) {
				lekcije.append (napravi_lekciju_aktivnost(data[i].korisnik.ime, data[i].korisnik.id, data[i].kolegij.id, data[i].kolegij.ime, data[i].lekcija.id, data[i].lekcija.ime, data[i].status, data[i].rezultat, data[i].datum));
			}
			$("#aktivnosti").html(lekcije);
			$("#big_loader").hide();
	});
});

$("#daj_teme").click(function(e) {
	$("#big_loader").show();
	$("#navigacija li").removeClass("active");
	$("#nav-padajuca-lista").addClass('active');
    $.getJSON('<?php echo site_url('profil/teme/'.$korisnik['id'])?>', {}, function (data) {
			var postovi = $("<div></div>");
			for (i in data) {
				postovi.append (napravi_temu_aktivnost(data[i].ime_korisnika, data[i].url_korisnika, data[i].ime_teme, data[i].url_teme, data[i].id_teme, data[i].datum));
			}
			$("#aktivnosti").html(postovi);
			$("#big_loader").hide();
	});
});

$("#daj_forume").click(function(e) {
	$("#big_loader").show();
	$("#navigacija li").removeClass("active");
	$("#nav-padajuca-lista").addClass('active');
    $.getJSON('<?php echo site_url('profil/forumi/'.$korisnik['id'])?>', {}, function (data) {
			var forumi = $("<div></div>");
			for (i in data) {
				forumi.append (napravi_forum_aktivnost(data[i].ime_korisnika, data[i].url_korisnika, data[i].ime_foruma, data[i].url_foruma, data[i].id_foruma, data[i].datum));
			}
			$("#aktivnosti").html(forumi);
			$("#big_loader").hide();
	});
});
</script>
<?php $this->load->view ('static/footer'); ?>
