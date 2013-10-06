<?php 
$podaci = array ('naslov' => $sadrzaj['ime'], 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'forumi');
$this->load->view('static/header', $podaci);
?>
<script type="text/javascript" src="<?php echo base_url('js/scorm-api.js'); ?>"></script>
<script type="text/javascript">
var API = null;
var ID_SADRZAJA = '<?php echo $sadrzaj['id']; ?>';
var ID_POKUSAJA = '<?php echo $id_pokusaja; ?>';
var INDEX =  <?php echo $defaultScoIndex; ?>;
var SCOS = <?php echo $scos ?>;

$(document).ready(function(e) {
    API = new SCORM_API_1_2(ID_SADRZAJA);
	
	$("a.item[rel="+SCOS[INDEX].identifier+"]").parent().addClass('active');
	
	$("#nextSco").click(function () {
		if (INDEX < SCOS.length-1) {
			INDEX++;
		}
		
		$("#frameWindow").attr('src', SCOS[INDEX].href);
		$("a.item").parent().removeClass('active');
		$("a.item[rel="+SCOS[INDEX].identifier+"]").parent().addClass('active');
	});
	
	$("#prevSco").click(function(e) {
        if (INDEX > 0) {
			INDEX--;	
		}
		
		$("#frameWindow").attr('src', SCOS[INDEX].href);
		$("a.item").parent().removeClass('active');
		$("a.item[rel="+SCOS[INDEX].identifier+"]").parent().addClass('active');
    });
	
	$(".item").click (function () {
		var identifier = $(this).attr('rel');
		for (i in SCOS) {
			if (identifier == SCOS[i].identifier) INDEX = i;
		}
		$("a.item").parent().removeClass('active');
		$(this).parent().addClass('active');
	});
});

</script>
<script type="text/javascript">
function autoresize(id){
    var newheight;
    if(document.getElementById){
        newheight=document.getElementById(id).contentWindow.document .body.scrollHeight;
    }
    document.getElementById(id).height= (newheight) + "px";
}

function popup() {
	var url = $("#frameWindow").attr ("src");
	params  = 'width='+(screen.width);
	params += ', height='+(screen.height);
	params += ', top=0, left=0'
	params += ', fullscreen=yes';
	
	newwin=window.open(url,'SCO viewer 1.0', params);
	if (window.focus) {newwin.focus()}
	return false;
}
$('body').css({'background-image':'none'});
</script>
<div class="container-fluid">
  <div class="row-fluid">
  <ul class="breadcrumb">
    <li>Kolegiji</li>
    <li class="divider">/</li>
    <li><a href="<?php echo site_url("kolegiji/index/".$kolegij['id']); ?>"><?php echo $kolegij['ime']; ?></a></li>
    <li class="divider">/</li>
  	<li><?php echo $sadrzaj['ime']; ?></li>
  </ul>
  </div>
  <div class="row-fluid">
    <div class="span3">
      <div class="well">
        <ul class="nav nav-list">
          <li class="nav-header">Status lekcije</li>
          <li class="divider"></li>
        </ul>
        <table id="statusSco" class="table">
          <tr>
            <td><span rel="tooltip" id="bodovi" title="Bodovi" class="label label-info">100</span></td>
            <td><span rel="tooltip" id="status" title="Status lekcije" class="label label-warning">Incomplete</span></td>
          </tr>
        </table>
      </div>
      <?php if ($sadrzaj['vrsta_navigacije'] == 'tree' || $sadrzaj['vrsta_navigacije'] == 'both') { ?>
      <div class="well sidebar-nav-fixed">
        <?php foreach ($organizations as $organization) { ?>
        <ul class="nav nav-list">
          <li class="nav-header"><?php echo $organization->getTitle() ?></li>
          <li class="divider"></li>
          <?php
                        buildMenu($organization->getItems(), $resources, 0, $path);
          ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
      
      <div class="well">
        <ul class="nav nav-list">
          <li class="nav-header">Opcije</li>
          <li class="divider"></li>
        </ul>
        <center>
        <?php if ($sadrzaj['vrsta_navigacije'] == 'nextprev' || $sadrzaj['vrsta_navigacije'] == 'both') { ?>
        <div class="btn-group">
          	<a href="#prev" id="prevSco" title="Prošla lekcija" class="btn btn-navbar"><span class="icon icon-chevron-left"></span></a> 
            <a href="#next" id="nextSco" title="Sljedeća lekcija" class="btn btn-navbar"><span class="icon icon-chevron-right"></span></a>
        </div>
        <?php } ?>
        <div class="btn-group">
            <a href="<?php echo base_url('scorms/paketi/'.$sadrzaj['id'].'/imsmanifest.xml'); ?>" title="Pogledaj manifest" class="btn btn-navbar"><span class="icon icon-tag"></span></a>
            <a href="<?php echo base_url($sadrzaj['putanja']); ?>" title="Preuzmi SCORM paket" class="btn btn-navbar"><span class="icon icon-download-alt"></span></a>
            <a href="#manifest" onclick="popup();" title="Pogledaj u punoj rezoluciji" class="btn btn-navbar"><span class="icon icon-fullscreen"></span></a>
        </div>
        </center>
        <br class="clearfix" />
      </div>
    </div>
    <div class="span9" id="scoWindow">
      <iframe src="<?php echo $iframe; ?>" name="windowFrame" onload="autoresize('frameWindow')" id="frameWindow" height="auto" width="100%;" />
    </div>
  </div>
</div>
<div id="chatovi"></div>
<script type="text/javascript" src="<?php echo base_url("js/chat.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("stil/bootstrap/js/bootstrap.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("js/init.javascript.js"); ?>"></script>
</body>
</html>

<script type="text/javascript">
$(document).ready(function(e) {
	$(window).resize(function () { 
		autoresize ('frameWindow'); 
	});
	$("[rel=tooltip]").tooltip();
});
</script>