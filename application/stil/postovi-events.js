// JavaScript Document

$('body').prepend($("<div></div>").attr("id", "big_loader"));
$("#big_loader").show();
$("#fade").show();
if (POST.daj_prikazi_komentare() == 'prikazi') $("#prikazi").addClass('active');
POST.ucitaj();

$("#prikazi").click(function(e) {
    if ($(this).hasClass('disabled')) return;
	if (POST.daj_prikazi_komentare() == 'skrij' || POST.daj_prikazi_komentare() == null) {
		$.cookie('prikazi_komentare', 'prikazi');
		POST.postavi_prikazi_komentare('prikazi');
	} else {
		$.cookie('prikazi_komentare', 'skrij');
		POST.postavi_prikazi_komentare('skrij');
	}
	location.reload();
});



$("#komentiraj").click(function(e) {
	if ($(this).hasClass('disabled')) return;
    $("#komentiraj_ui").modal('show');
	$('#dodaj_post_greske').hide();
});

$("#spasi_temu").click(function () {
	var ime_teme = $('input[name=ime]').val();
	var nice = new nicEditors.findEditor('tema_uredi_opis');
	var opis_teme = nice.getContent();
	$("#big_loader").show();
	TEMA.spasi (ime_teme, opis_teme);
	return false;
});

$("#uredi").click(function (e) {
	$("#big_loader").show();
	$("#uredi_temu_greske").hide();
	TEMA.uredi();
});

$(".ucitaj_jos").live('click', function(e) {
	var id_sadrzaja = $(this).attr('name');
	POST.ucitaj_jos(id_sadrzaja);
});

$(".odgovori").live("click", function (e) {
	var id_posta = $(this).attr('name');
	$("#dodaj_odgovor").modal('show');	
	$("#odgovoriGreske").hide();
	POST.postavi_id(id_posta);
});

$(".diskusija").live("click", function (e) {
	var id_posta = $(this).attr('name');
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
		$("#post_odgovori_"+id_posta).slideUp();	
	} else {
		$("#big_loader").show();
		POST.daj_odgovore(id_posta);
		$("#big_loader").hide();
		$("#post_odgovori_"+id_posta).slideDown();
		$(this).addClass('active');	
	}
});

$("#spasi_odgovor").click (function () {
	var ime = $('input[name=imeOdgovora]').val();
	var nice = new nicEditors.findEditor('sadrzajOdgovora');
	var sadrzaj = nice.getContent();
	
	$("#big_loader").show();
	POST.odgovori(ime, sadrzaj);
	
	$('input[name=imeOdgovora]').val("");
	$('textarea[name=sadrzajOdgovora]').val("");
	new nicEditors.findEditor('sadrzajOdgovora');
	return false;
});

$("#spasi_post").click(function(e) {
	if ($("#komentiraj").hasClass('disabled')) return;
    var ime = $('input[name=imePosta]').val();
	var nice = new nicEditors.findEditor('sadrzajPosta');
	var sadrzaj = nice.getContent();
	$("#big_loader").show();
	$("#fade").show();
	POST.spasi(ime, sadrzaj);
	return false;
});

$("#zatvori").click (function () {
	TEMA.zakljucaj_otkljucaj($(this));
});

$(".uredi").live('click', function () {
	var id_posta = $(this).attr('name');
	$("#big_loader").show();
	var post = POST.daj_post (id_posta);
	POST.postavi_id (id_posta);
	$('input[name=imeUredjenogPosta]').val(post.ime);
	$('textarea[name=sadrzajUredjenogPosta]').val(post.sadrzaj);
	var nice = new nicEditors.findEditor('sadrzajUredjenogPosta');
	nice.setContent(post.sadrzaj);
	$("#uredi_post_ui").modal('show');
	$("#big_loader").hide();
	$("#uredi_post_greske").hide();
});

$("#spasi_uredeni_post").bind('click', function (e) {
		var ime = $('input[name=imeUredjenogPosta]').val(); 
		var nice = new nicEditors.findEditor('sadrzajUredjenogPosta')
		var sadrzaj = nice.getContent();
		$("#big_loader").show();
		POST.uredi(POST.daj_id(), ime, sadrzaj);
		return false;
});
