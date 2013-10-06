<?php 
	$podaci = array ('naslov' => "Dodaj korisnika", 'logiraniKorisnik' => $logiraniKorisnik, 'aktivan' => 'none', 'id_kolegija' => $kolegij['id']);
	$this->load->view('static/header', $podaci); 
?>

<div class="container-fluid">
<div class="row-fluid">
  <div class="span9">
    <div id="sadrzaj" style="border-top:1px solid #ddd;">
      <ul class="breadcrumb">
        <li>Kolegiji</li>
        <li class="divider">/</li>
        <li><?php echo $kolegij['ime']; ?></li>
      </ul>
      <?php if (count ($sadrzaji) > 0) { ?>
      <table class="table table-condensed table-bordered table-striped">
        <thead>
          <tr>
            <th width="10%"></th>
            <th width="90%">Naziv lekcije i opis</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sadrzaji as $sadrzaj) { ?>
          <tr>
            <td><img src="<?php echo base_url('stil/moj_stil/Address_Book.png'); ?>" rel="tooltip" title="Sadržaj broj <?php echo $sadrzaj['id'] ?>" /></td>
            <td style="text-align:justify"><b><a href="<?php echo site_url("rte/index/".$sadrzaj['id']); ?>"><?php echo $sadrzaj['ime'] ?></a></b>
              <p><?php echo $sadrzaj['opis'] ?></p>
              <br />
              <div class="btn-group pull-right"> <a href="<?php echo site_url("rte/index/".$sadrzaj['id']); ?>" class="btn btn-primary">Lekcija</a> <a href="<?php echo site_url("rte/index/".$sadrzaj['id']); ?>?novi_pokusaj=true" class="btn">Novi pokušaj</a> </div></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <div class="alert alert-info">
        <h4 class="alert-header">Upozorenje!</h4>
        <p>Nema nastavnih sadržaja, pogledajte u drugim kolegijima iz vaše grupe.</p>
      </div>
      <?php } ?>
    </div>
  </div>
  <div class="pagination pull-left">
    <?php  echo generiraj_paginaciju("kolegij/index/".$kolegij['id'], $broj_sadrzaja, 4); ?>
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
<div id="chatovi"></div>
<script type="text/javascript">
var STATUS = <?php echo $logiraniKorisnik['status']; ?>;
</script> 
<?php
$this->load->view('static/footer');
?>
