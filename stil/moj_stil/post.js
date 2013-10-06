// JavaScript Document

var post = function (STATUS, kolacic_prikazi_komentare) {
	var prikazi_komentare = kolacic_prikazi_komentare;
	var zadnji_odgovor_id = 0;
	var id;
	var status = STATUS;
	
	this.postavi_id = function (id_posta) { id = id_posta; }
	this.daj_id = function () { return id; }
	this.daj_prikazi_komentare = function () { return prikazi_komentare; }
	this.postavi_prikazi_komentare = function (vrijednost) { prikazi_komentare = vrijednost; }
	
	this.ucitaj = function () {
		$.getJSON(CI_ROOT + 'index.php/postovi/daj_postove/' + TEMA.daj_id(), {}, function (data){
			for (i in data) {
				var post = napravi_post(data[i]);
				$("#postovi").append(post);
				if (prikazi_komentare == 'prikazi') daj_odgovore(data[i].id_posta);
				post.show('slow');		
			};
			$("#big_loader").hide();
			$("#fade").hide();
		});
	}
	
	this.ucitaj_jos = function (id_stranice) {
		$.getJSON(CI_ROOT + 'index.php/postovi/daj_postove/' + TEMA.daj_id() +'/' + id_stranice, {}, function (data) {
			if(data.length != 0) {
				for(i in data) {
					var post = napravi_post(data[i]);
					$("#postovi").append(post);
					if (prikazi_komentare == 'prikazi')daj_odgovore(data[i].id_posta);
				}
				var novi_btn= $("<a></a>").attr('href', 'javascript:void(0)').addClass('btn').addClass('btn-large').addClass('ucitaj_jos').attr('name', parseInt(id_stranice)+1).html($("<span></span>").addClass('icon').addClass('icon-plus-sign')).append('&nbsp;Još rezultata').attr('id', 'jos_'+(parseInt(id_stranice)+1)).bind('click', function(e){ucitaj_jos (e)});
				$("#btn-center").html(novi_btn);
			} else {
				var obavijest = $("<div></div>").css({'text-align':'center'}).attr('id', 'nema_postova').addClass('alert').addClass('alert-info').html('Nema više objava.'); 
				$("#sadrzaj").append(obavijest);
				$("#nema_postova").fadeIn();	
			}
			$("#jos_"+id_stranice).hide();
		});	
	}
	
	this.spasi = function (ime, sadrzaj) {
		if(ime != "" && sadrzaj != "") {
				$.post($("#forma_za_dodavanje").attr('action'), {'imePosta':ime, 'sadrzajPosta' :sadrzaj}, function (data) {
					if (data.greska == 0) {
						var post = napravi_post(data);
						$("#postovi").prepend(post);
						$("#komentiraj_ui").modal('hide');
						$('textarea[name=sadrzajPosta]').val("");
						$("#komentiraj_ui").bind('hidden', function () { post.slideDown(); });
						zadnji_odgovor_id = data.id_posta;
					} else {
						$("#dodaj_post_greske").html("<b>Nastala je greška: </b>" + data.tekst).fadeIn();
					}
					
					$("#big_loader").hide();
					$("#fade").hide();
				}, 'json');
		} else {
			$("#dodaj_post_greske").html("<b>Nastala je greška: </b><p>Niste unjeli sve potrebne podatke.</p>").fadeIn();
			$("#big_loader").fadeOut();
			$("#fade").hide();
		}		
	}
	
	this.uredi = function (id_posta, ime, sadrzaj) {
		if (ime != "" && sadrzaj != "") {
			$.post(CI_ROOT + 'index.php/postovi/uredi_post/' + id_posta, {'imePosta' : ime, 'sadrzajPosta' : sadrzaj}, function  (data){
				if (data.greska == 0) {
					var post = napravi_edit_post(data);
					$('#post'+data.id_posta).html(post);
					$("#uredi_post_ui").modal('hide');
				} else {
					$("#uredi_post_greske").html('<b>Dogodila se greška: </b><p>' + data.tekst + "</p>");
					$("#uredi_post_greske").show();
				}
			}, 'json')
		} else {
			alert ("Niste unjeli sve podatke, molimo provjerite Vaš unos.");	
		}
		$("#big_loader").hide();
	}
	
	this.daj_post = function (id_posta) {
		var post;
		$.ajax({
			url : CI_ROOT + 'index.php/postovi/uredi_post/' + id_posta,
			dataType: "json",
			type: "GET",
			async: false,
			data: {},
			success : function (data) { post = data; }
		});
		return post;	
	}
	
	this.odgovori = function (ime, sadrzaj) {
		if(ime != "" && sadrzaj != "") {
			$.post(CI_ROOT + 'index.php/postovi/dodaj_odgovor/' + TEMA.daj_id(), {'id_posta' : id, 'ime' : ime, 'sadrzaj' : sadrzaj}, 
			function (data){
				if (data.greska == 0) {
					var odgovor = napravi_post(data);
					$("#post_odgovori_"+id).prepend(odgovor);
					$("#dodaj_odgovor").modal('hide');
					zadnji_odgovor_id = id;
				} else {
					$("#odgovoriGreske").html(data.tekst);
					$("#odgovoriGreske").show();	
				}
				$("#big_loader").hide();
				}, 'json')
		} else {
			alert ("Niste unjeli sve potrebne podatke, molimo provjerite vaš unos.");	
		}
	}
	
	this.daj_nove_postove = function () {
		if (zadnji_odgovor_id > 0) {
			$("#loader_provjera_postova").show();
			$.getJSON(CI_ROOT + 'index.php/postovi/veci_postovi/' + TEMA.daj_id(), {'id_odgovora':zadnji_odgovor_id}, function (data){
				for (i in data) {
					zadnji_odgovor_id = data[i].id_posta;
					var post = napravi_post(data[i]);
					if(data[i].id_roditelja == 0) {
						$("#postovi").prepend(post);
					} else {
						$("#post_odgovori_"+data[i].id_roditelja).prepend(post);
					}
					if (prikazi_komentare == 'prikazi') daj_odgovore(data[i].id_posta);
				};
				$("#loader_provjera_postova").hide();
			});	
		}
	}
	
	function daj_odgovore (id) {
		var id_posta = id;
		$.getJSON (CI_ROOT + 'index.php/postovi/daj_odgovore/'+ TEMA.daj_id(), {'id_posta' : id_posta}, function (data) {
			$("#post_odgovori_"+id_posta).html("");
			for (i in data) {
				zadnji_odgovor_id = data[i].id_posta;
				var odgovor = napravi_post(data[i]);
				$("#post_odgovori_"+id_posta).prepend(odgovor);
				daj_odgovore (data[i].id_posta);
			}
			$("#post_odgovori_"+id_posta).fadeIn();
		});	
	}
	
	function napravi_edit_post (post) {
		var post_header = $("<div></div>").addClass ('post-header');
		var ime_posta = $("<b></b>").html(post.ime_posta);
		var user_2 = $("<a></a>").attr("href", post.url_korisnika).html(post.ime_korisnika);
		
		post_header.html(post.ime_posta);
		post_header.append (" postavio/la ");
		post_header.append (user_2);
		post_header.append (" na dan " + post.datum);
		
		var strjelica = $("<div></div>").addClass("strjelica");
		var post_body = $("<div></div>").addClass("post-body").html(post.sadrzaj_posta);
		
		var post_footer = $("<div></div>").addClass("post-footer");
		
		var btn_group = $("<div></div>").addClass("btn-group").addClass("pull-right");
		
		var btn_odgovori = $("<button></button>").addClass('btn').addClass('odgovori').attr("name", post.id_posta).attr("title", "Odgovori").attr('rel', 'tooltip');
		var icon_odgovori = $("<span></span>").addClass('icon').addClass('icon-comment');
		btn_odgovori.html(icon_odgovori);
		if (post.id_korisnika == post.id_autora) {
		var btn_uredi = $("<button></button>").addClass('btn').addClass('uredi').attr("name", post.id_posta).attr("title", "Uredi").attr('rel', 'tooltip');
		var icon_uredi = $("<span></span>").addClass('icon').addClass('icon-edit');
		btn_uredi.html(icon_uredi);
		}
		
		var btn_diskusija = $("<button></button>").addClass('btn').addClass('diskusija').attr("name", post.id_posta).attr("title", "Osvježi").attr('rel', 'tooltip');
		var icon_diskusija = $("<span></span>").addClass('icon').addClass('icon-refresh');
		btn_diskusija.html(icon_diskusija);
		
		btn_group.html(btn_odgovori);
		if (post.id_korisnika == post.id_autora) {btn_group.append(btn_uredi);}
		btn_group.append(btn_diskusija);
		
		post_footer.html(btn_group);
		post_footer.append($('<br />').attr('clear', 'all'));
		
		text_wrap = $("<div></div>");
		
		text_wrap.html(post_header)
		text_wrap.append(strjelica);
		text_wrap.append(post_body);
		text_wrap.append(post_footer);
		return text_wrap;
	}
	
	function napravi_post (post) {
		var red = $("<div></div>").addClass("row-fluid").addClass("post_wrapper").attr('id', "post_wrapper"+post.id_posta);
		var thumb_wrapper = $("<div></div>").addClass("span1");	
		var thumb = $("<div></div>").addClass("whitebg");
		
		var thumb_img = $("<a></a>").attr("href", post.url_korisnika).addClass('thumbnail').append($("<img />").attr("src", post.url_avatara).width('86')).attr("target", "_blank");
	
		thumb.append (thumb_img);
	
		thumb_wrapper.html (thumb);
		
		red.html(thumb_wrapper);
		
		var text_wrap = $("<div></div>").attr('id', 'post'+post.id_posta);
		var post_wrapper = $("<div></div>").addClass ('span11').addClass('post');
		var post_header = $("<div></div>").addClass ('post-header');
		var ime_posta = $("<b></b>").html(post.ime_posta);
		var user_2 = $("<a></a>").attr("href", post.url_korisnika).html(post.ime_korisnika);
		
		var sub_post = $("<div></div>").addClass('post-odgovori').attr('id', 'post_odgovori_' + post.id_posta);
		
		post_header.html(post.ime_posta);
		post_header.append (" postavio/la ");
		post_header.append (user_2);
		post_header.append (" na dan " + post.datum);
		
		var strjelica = $("<div></div>").addClass("strjelica");
		var post_body = $("<div></div>").addClass("post-body").html(post.sadrzaj_posta);
		
		var post_footer = $("<div></div>").addClass("post-footer");
		if (status == 'otkljucan') {
		var btn_group = $("<div></div>").addClass("btn-group").addClass("pull-right");
		
		var btn_odgovori = $("<button></button>").addClass('btn').addClass('odgovori').attr("name", post.id_posta).attr("title", "Odgovori").attr('rel', 'tooltip');
		var icon_odgovori = $("<span></span>").addClass('icon').addClass('icon-comment');
		btn_odgovori.html(icon_odgovori);
		if (post.id_korisnika == post.id_autora) {
		var btn_uredi = $("<button></button>").addClass('btn').addClass('uredi').attr("name", post.id_posta).attr("title", "Uredi").attr('rel', 'tooltip');
		var icon_uredi = $("<span></span>").addClass('icon').addClass('icon-edit');
		btn_uredi.html(icon_uredi);
		}
		var btn_diskusija = $("<button></button>").addClass('btn').addClass('diskusija').attr("name", post.id_posta).attr("title", "Osvježi").attr('rel', 'tooltip');
		var icon_diskusija = $("<span></span>").addClass('icon').addClass('icon-refresh');
		btn_diskusija.html(icon_diskusija);
		btn_group.html(btn_odgovori);
		if (post.id_korisnika == post.id_autora) {btn_group.append(btn_uredi);}
		btn_group.append(btn_diskusija);
		
		post_footer.html(btn_group);
		}
		post_footer.append($('<br />').attr('clear', 'all'));
	
		text_wrap.html(post_header)
		text_wrap.append(strjelica);
		text_wrap.append(post_body);
		text_wrap.append(post_footer);
		
		post_wrapper.html(text_wrap);
		post_wrapper.append(sub_post);
		
		red.append(post_wrapper);
		return red;
	}	
}