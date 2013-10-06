
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php echo $naslov ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/bootstrap/css/bootstrap.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("stil/mojStil.css"); ?>" />

<link type="text/css" rel="stylesheet" media="all" href="<?php echo base_url("stil/chat/css/chat.css"); ?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php echo base_url("stil/chat/css/screen.css"); ?>" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"></script>


<script type="text/javascript" src="<?php echo base_url("js/timeago.jquery.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("stil/nicedit/nicEdit.js"); ?>"></script>
<script type="text/javascript">
        var CI_ROOT = "<?php echo base_url() ?>";
		var STATUS = "<?php echo $logiraniKorisnik['status']; ?>";
		bkLib.onDomLoaded(function() { 	
			new nicEditors.allTextAreas({maxHeight:250, iconsPath : '<?php echo base_url('stil/nicedit/nicEditorIcons.gif');?>'});
		});
</script>

</head>

<body>
<div class="beta"></div>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
    	<div class="fluid-container">
        	<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
        	<a class="brand" id="logo" href="#">SOSCLE</a>
            <div class="nav-collapse">
            	<ul class="nav">
                	<li <?php if (isset ($pocetna)) { ?>class="active"<?php } ?>><a href="<?php echo site_url('profil/index'); ?>">Početna</a></li>
                    <li <?php if (isset ($forumi)) { ?>class="active"<?php } ?>><a href="<?php  echo site_url ('forumi'); ?>">Forumi</a></li>
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                    	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Kolegiji <b class="caret"></b></a>
                    	<ul class="dropdown-menu">
                        <div id="dropdown-innerdiv" class="facebook-scroll">
                        	<?php 
							if (isset($id_kolegija))
								echo daj_meni_kolegija($logiraniKorisnik, $id_kolegija);
							else
								echo daj_meni_kolegija($logiraniKorisnik);
							?>
                       </div>
                       </ul>
                    </li>
                </ul>
                <form class="navbar-search pull-left" onsubmit="return false;">
                	<input type="text" class="search-query span2" id="pretrazi" placeholder="Pretraži korisnike" autocomplete="off" />
                    <div id="rezultati" class="facebook-scroll">
                    	
                    </div>
                </form>
                <ul class="nav pull-right">
                	<li class="dropdown">
                    	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $logiraniKorisnik['ime']. " " .$logiraniKorisnik['prezime']; ?><b class="caret"></b></a>
                    	<ul class="dropdown-menu">
							<?php generirajMeni($logiraniKorisnik['uloga'], $aktivan); ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>