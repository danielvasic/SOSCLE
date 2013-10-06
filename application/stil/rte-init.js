var API_1484_11 = null;
var API = null;
var MANIFEST = null;
var NAVIGATION = null;
var UI = null;

$(document).ready(function (e) {
	$('body').prepend($('<div></div>').attr('id', 'big_loader'));
	$('body').prepend($('<div></div>').attr('id', 'fade'));
	
	$("#big_loader").show();
	$("#fade").show();

	$.ajax({
		type:'GET',
		url:CI_ROOT + 'scorms/paketi/' + ID_SADRZAJA + '/imsmanifest.xml',
		dataType:'xml',
		async:true,
		error:function(xhr) {
			alert(xhr.statusText);
		},
		success:function (xml) {
			
			MANIFEST = new minifest_parser (xml);
			UI = new rte_ui();
			NAVIGATION = new rte_navigation();
			
			MANIFEST.parse();
			
			UI.build_menu();
			NAVIGATION.set_default();

			API = new SCORM_API_1_2(ID_POKUSAJA);
			
		}	
	});
});

$("#iframe_window").bind('unload', function () { API.LMSFinish(""); });
$(document).bind('unload', function () { API.LMSFinish(""); });

$(".previous").click(function(e) {
  API = new SCORM_API_1_2(ID_POKUSAJA);
  NAVIGATION.previous();
});
  
$(".next").click(function(e) {
	API = new SCORM_API_1_2(ID_POKUSAJA);
	NAVIGATION.next();
	
});
  
$(".sub-open").live('click', function () {
  $(this).next('ul').slideToggle();	
});
  
$('.sco').live ('click', function (e) {
  $("#big_loader").show();
  $("#fade").show();
  
  NAVIGATION.set_current_item ($(this).attr('id'));
  NAVIGATION.set_current_sco($(this).attr('name'));
  var current_item = NAVIGATION.get_current_item();
  NAVIGATION.set_current_index (MANIFEST.get_item_index (current_item));
  UI.update_progress_bar ();
  
	  
  API = new SCORM_API_1_2(ID_POKUSAJA);
  
  $(".sco").removeClass('active');
  $(this).addClass('active');
  return false;
});
/**
* Client events
**/