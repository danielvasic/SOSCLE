// JavaScript Document
var tema = function (TEMA_ID) {
	var id_teme = TEMA_ID;
	
	this.postavi_id = function(id) {
		id_teme = id;
	} 
	
	this.daj_id = function() {
		return id_teme;
	} 
	
	this.spasi = function (ime_teme, opis_teme) {
		if (ime_teme != "" && opis_teme != "") {
			$.post(CI_ROOT + 'index.php/teme/uredi/'+id_teme, {'ime' : ime_teme, 'opis' : opis_teme}, function (data) {
				if(data.greska == 0) {
					$("#ime_teme").html(ime_teme);
					$("#uredi_ui").modal('hide');	
				} else {
					$("#uredi_temu_greske").html("<b>Dogodila se greška</b><p>" + data.tekst + "</p>").show();
				}
				$("#big_loader").hide();
			}, 'json');
		} else {
			alert ("Niste unjeli sve potrebne podatke, molimo provjerite vaš unos.");	
		}
	}
	
	this.uredi = function () {
		$.getJSON(CI_ROOT+'index.php/teme/uredi/'+id_teme, {}, function (data) {
			if (data.greska == 0) {
				$('input[name=ime]').val(data.ime_teme);
				$('textarea[name=opis]').val(data.opis_teme);
				var nice = new nicEditors.findEditor('tema_uredi_opis');
				nice.setContent(data.opis_teme);
				$("#uredi_ui").modal('show');
				$("#big_loader").hide();
			} else {
				alert (data.tekst);
			}
			$("#big_loader").hide();
		});
	}
	
	this.zakljucaj_otkljucaj = function (that) {
		$.getJSON(CI_ROOT + 'index.php/teme/zakljucaj_otkljucaj/' + id_teme, {}, function (data) {
			if (data.uspjeh == 1) {
				if (data.status == 'otkljucan') {
					$('#komentiraj').removeClass('disabled');
					$('#refresh').removeClass('disabled');
					if(that.hasClass('active')) that.removeClass('active');
					that.attr('data-original-title', 'Zatvori temu');
				} else {
					$('#komentiraj').addClass('disabled');
					$('#refresh').addClass('disabled');	
					that.addClass('active');
					that.attr('data-original-title', 'Otvori temu');
				}
				location.reload();
			} else {
				alert ('Nešto je otišlo po krivu, molimo pokusajte kasnije.')	
			}	
		});
	}
}
