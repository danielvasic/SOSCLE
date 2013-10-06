// JavaScript Document

var post = function (STATUS, kolacic_prikazi_komentare) {
	var prikazi_komentare = kolacic_prikazi_komentare;
	var id;
	var status = STATUS;
	var that = this;
	
	this.postavi_id = function (id_posta) { id = id_posta; }
	this.daj_id = function () { return id; }
	this.daj_prikazi_komentare = function () { return prikazi_komentare; }
	this.postavi_prikazi_komentare = function (vrijednost) { prikazi_komentare = vrijednost; }
	this.daj_id_zadnjeg_odgovora = function () { return zadnji_odgovor_id; }
	
	this.ucitaj = function () {
		$.ajax({
			url:CI_ROOT + 'index.php/postovi/daj_postove/' + TEMA.daj_id(), 
			data: {},
			dataType: 'json',
			async:false,
			type: 'GET',  
			success:
			function (data){
				for (i in data) {
					var post = napravi_post(data[i]);
					$("#postovi").append(post);
					if (prikazi_komentare == 'prikazi') that.daj_odgovore(data[i].id_posta);
					post.show('slow');		
				};
				$("#big_loader").hide();
				$("#fade").hide();
			}
		});
	}
	
	this.ucitaj_jos = function (id_stranice) {
		$.ajax({
			url:CI_ROOT + 'index.php/postovi/daj_postove/' + TEMA.daj_id() +'/' + id_stranice, 
			data:{},
			dataType: 'json',
			async:false,
			type: 'GET', 
			success: 
			function (data) {
				if(data.length != 0) {
					for(i in data) {
						var post = napravi_post(data[i]);
						$("#postovi").append(post);
						if (prikazi_komentare == 'prikazi')that.daj_odgovore(data[i].id_posta);
					}
					var novi_btn= $("<a></a>").attr('href', 'javascript:void(0)').addClass('btn').addClass('btn-large').addClass('ucitaj_jos').attr('name', parseInt(id_stranice)+1).html($("<span></span>").addClass('icon').addClass('icon-plus-sign')).append('&nbsp;Još rezultata').attr('id', 'jos_'+(parseInt(id_stranice)+1)).bind('click', function(e){ucitaj_jos (e)});
					$("#btn-center").html(novi_btn);
				} else {
					var obavijest = $("<div></div>").css({'text-align':'center'}).attr('id', 'nema_postova').addClass('alert').addClass('alert-info').html('Nema više objava.'); 
					$("#sadrzaj").append(obavijest);
					$("#nema_postova").fadeIn();	
				}
				$("#jos_"+id_stranice).hide();
			}
		});	
	}
	
	this.spasi = function (ime, sadrzaj) {
		if(ime != "" && sadrzaj != "") {
				$.ajax({
					url:$("#forma_za_dodavanje").attr('action'), 
					data:{'imePosta':ime, 'sadrzajPosta' :sadrzaj},
					dataType: 'json',
					async:false,
					type: 'POST', 
					success: 
					function (data) {
						if (data.greska == 0) {
							var post = napravi_post(data);
							$("#postovi").prepend(post);
							$("#komentiraj_ui").modal('hide');
							$('textarea[name=sadrzajPosta]').val("");
							$("#komentiraj_ui").bind('hidden', function () { post.slideDown(); });
						} else {
							$("#dodaj_post_greske").html("<b>Nastala je greška: </b>" + data.tekst).fadeIn();
						}
						$("#big_loader").hide();
						$("#fade").hide();
					}
				
				});
		} else {
			$("#dodaj_post_greske").html("<b>Nastala je greška: </b><p>Niste unjeli sve potrebne podatke.</p>").fadeIn();
			$("#big_loader").fadeOut();
			$("#fade").hide();
		}		
	}
	
	this.uredi = function (id_posta, ime, sadrzaj) {
		if (ime != "" && sadrzaj != "") {
			$.ajax({
				url:CI_ROOT + 'index.php/postovi/uredi_post/' + id_posta, 
				data: {'imePosta' : ime, 'sadrzajPosta' : sadrzaj},
				dataType: 'json',
				type: 'POST', 
				async:false,
				success:
				function  (data){
					if (data.greska == 0) {
						var post = napravi_edit_post(data);
						$('#post'+data.id_posta).html(post);
						$("#uredi_post_ui").modal('hide');
					} else {
						$("#uredi_post_greske").html('<b>Dogodila se greška: </b><p>' + data.tekst + "</p>");
						$("#uredi_post_greske").show();
					}
				}
			});
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
			async:false,
			type: "GET",
			data: {},
			success : function (data) { post = data; }
		});
		return post;	
	}
	
	this.odgovori = function (ime, sadrzaj) {
		if(ime != "" && sadrzaj != "") {
			$.ajax({
				url:CI_ROOT + 'index.php/postovi/dodaj_odgovor/' + TEMA.daj_id(), 
				data: {'id_posta' : id, 'ime' : ime, 'sadrzaj' : sadrzaj}, 
				dataType: 'json',
				async:false,
				type: 'POST', 
				success: function (data){
					$("#big_loader").hide();
					if (data.greska == 0) {
						var odgovor = napravi_post(data);
						$("#post_odgovori_"+id).prepend(odgovor);
						$("#dodaj_odgovor").modal('hide');
					} else {
						$("#odgovoriGreske").html(data.tekst);
						$("#odgovoriGreske").show();	
					}
				}
			})
		} else {
			alert ("Niste unjeli sve potrebne podatke, molimo provjerite vaš unos.");
			$("#big_loader").hide();	
		}
	}
	
	this.daj_odgovore = function (id) {
		var id_posta = id;
		$.ajax ({
			url: CI_ROOT + 'index.php/postovi/daj_odgovore/'+ TEMA.daj_id(), 
			data: {'id_posta' : id_posta},
			dataType: 'json',
			type: 'GET', 
			success: 
			function (data) {
				$("#post_odgovori_"+id_posta).html("");
				for (i in data) {
					var odgovor = napravi_post(data[i]);
					$("#post_odgovori_"+id_posta).prepend(odgovor);
					$(".diskusija[name="+id_posta+"]").addClass('active');
					that.daj_odgovore (data[i].id_posta);
				}
				$("#post_odgovori_"+id_posta).fadeIn();
			}
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
		if (prikazi_komentare == 'prikazi') btn_diskusija.addClass('active');
		var icon_diskusija = $("<span></span>").addClass('icon').addClass('icon-plus');
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
		if (prikazi_komentare == 'prikazi') btn_diskusija.addClass('active');
		var icon_diskusija = $("<span></span>").addClass('icon').addClass('icon-plus');
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