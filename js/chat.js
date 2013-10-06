// JavaScript Document

function provjeriTkoJeOnline () {
	var global_div = $("<div></div>");
	$('.chat-loader').show();
	$.getJSON(CI_ROOT + 'index.php/chatKorisnika/tko_je_online', [], function (data) {																		
		for(i=0;i<data.length;i++) {
			var row_div = $("<div></div>").addClass('row-fluid').addClass('chat-row')
			var img_div = $("<div></div>").addClass('span3');
			var avatar = $("<img />").attr ('src', data[i].avatar).addClass('profile-image').addClass('thumbnail');
			var icon = $("<div></div>").addClass('span1').css({'margin-top' : '20px'}).css({'margin-left' : '10px'}).html($('<span></span>').addClass('icon-'+data[i].status));
			var ancor = $("<div></div>").addClass('span7').css({'margin-top' : '19px', 'margin-left' : '5px'}).html($('<a></a>').html(data[i].puno_ime).attr('href', 'javascript:void(0)').attr('onclick', 'chatWith(\'' + data[i].id + '\', \'' + data[i].puno_ime + '\', \'' + data[i].avatar_img + '\')'));
			img_div.append(avatar);
			row_div.append(img_div);
			row_div.append(icon);
			row_div.append(ancor);
			global_div.append(row_div);
			$('.chat-loader').hide();
		}
		$('#chat-body').html(global_div);
		$('.chat-disabled').hide();
	});
}

$('.chat-disabled').fadeIn();

var provjera = 5000;
var interval;
if (STATUS != 'offline') {
interval = setInterval(provjeriTkoJeOnline, provjera);
$('.chat-loader').show();
}

$('.postavi_status').click (function () {
	var status = $(this).attr('id');
	$.getJSON(CI_ROOT + 'index.php/chatKorisnika/postovi_status', {'status':status}, function (data) {
		$("#status_korisnika").removeClass().addClass('icon-' + data.status);

		if (data.status == 'offline') { 
			$('.chat-disabled').fadeIn();
			$('.chat-loader').hide();
			clearInterval(interval);
		} else {
			$('.chat-disabled').fadeOut();
			interval = setInterval(provjeriTkoJeOnline, provjera);
		}
		
	});
});
