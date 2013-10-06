// JavaScript Document

$(".alert").fadeIn();
$('.dropdown-toggle').dropdown();
$('[rel=tooltip]').tooltip();
$("#restore").click (function (e) {
	$("#greskaPovratak").hide();
	$("#uspjehPovratak").hide();
});

$("#refresh").button();


$('.brisi').click (function () {
	if (confirm ("Jeste li 100% sigurni da zelite pobrisati?")) {
			window.location($(this).attr('href'));
	}
	return false;
});

$("#vrati").click (function () {
							$("#loaderVratiLozinku").show();
							var email = $("#emailModal").val();
							var url = CI_ROOT+'index.php/loginKorisnika/vratiLozinku';
							if (email != "") {
								$.post(url, { "email": email}, function(data){
									$("#loaderVratiLozinku").hide();
									$('.alert').hide();
									if (data.greska == 0) {
										$("#uspjehPovratak").show();
										$("#emailModal").attr("value", "");
									} else {
										$("#greskaPovratak").show();
										$("#greskaTekst").html("Email adresa koju ste unjeli ne postoji u nasoj bazi podataka.");
									}
								}, "json");
								
							} else {
								$("#loaderVratiLozinku").hide();
								$("#greskaPovratak").show();
							}
			return false;
});

$("html").click (function () {
	$("#rezultati").slideUp('fast');
	$("#pretrazi").val("");
}); 

$("#pretrazi").bind ('keyup', function (e) {
	var pretraga = $(this).val();
	$.getJSON(CI_ROOT+'index.php/pretrazi/korisnika', {'q': pretraga}, function (data) {
		$("#rezultati").html("");
		$("#big_loader").show();
		if (data.length > 0) {
			for (i in data) {
				$("#rezultati").append(napravi_rezultat_pretrage(data[i].ime, data[i].url, data[i].avatar));	
			}	
		} else {
			var p = $("<p></p>").css({'padding' : '10px', 'color' : 'black', 'text-align':'center'}).html("Za pretragu ").append($("<i></i>").html('"'+pretraga+'"')).append(" nema rezultata.");	
			$("#rezultati").html(p);
		}
		$("#big_loader").hide();
		$("#rezultati").slideDown('fast');
	});	
});

function napravi_rezultat_pretrage (ime, url_korisnika, url_avatara) {
	var _link = $("<a></a>").attr('href', decodeURIComponent(url_korisnika));
	var _img = $("<img />").attr('src', decodeURIComponent(url_avatara)).addClass('thumbnail').css({'margin' : '5px'});
	var _table = $("<table></table>");
	var _tr = $("<tr></tr>");
	
	var _td1 = $("<td></td>").html(_img);
	var _td2 = $("<td></td>").html($("<span></span>").html(ime));
	
	_tr.append(_td1);
	_tr.append(_td2);
	_table.html(_tr);
	_link.html (_table);
	return _link;	
}