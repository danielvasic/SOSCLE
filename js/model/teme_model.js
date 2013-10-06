window.Tema = Backbone.Model.Extend ({
	url_root : CI_ROOT + 'index.php/teme/',
	
	defaults: function () {
		id = null;
		ime = "";
		opis = "";
		id_korisnika = null;
		id_foruma = null;
		datum = "";
		status = "otkljucan"
	}
});

window.Teme = Backbone.Collection.extend ({
	
	url : CI_ROOT + 'index.php/teme/daj_teme',
	model : Tema,
	
	ucitaj_teme : function (id_stranice) {
		var url = (id_stranice == '') ? url : url + "/" + id_stranice;
		var self = this;
		console.log('Ucitavam teme');
		
		$.ajax({
			url : url,
			type : "GET",
			dataType: "JSON",
			error : function (jqXHR, textStatus, errorThrown) {console.log ("Nastala je greska br. " + errorThrown + ": " + textStatus );},
			success : function (data) { self.reset (data); }	
		});
	}
});