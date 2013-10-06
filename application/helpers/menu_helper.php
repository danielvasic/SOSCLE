<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('generirajMeni')) {
	function generirajMeni ($uloga, $aktivan) {
		$Administrator = array('kolegiji', 'sadrzaji', 'grupe', 'korisnici', 'forumi', 'profil', 'divider', 'odjava');
		$Ucenik = array('profil', 'divider', 'odjava');
		$Ucitelj = array('sadrzaji', 'forumi', 'profil', 'divider', 'odjava');
		switch ($uloga) {
			case 'Administrator' :
				$petlja = $Administrator;
				break;
			case 'Ucenik' :
				$petlja = $Ucenik;
				break;
			case 'Ucitelj' :
				$petlja = $Ucitelj;
				break;
		}
		
		
		$nazivi = array ('kolegiji' => 'Upravljanje kolegijima', 
						  'sadrzaji' => 'Upravljanje nastavnim sadržajem', 
						  'grupe' => 'Upravljanje grupama', 
						  'korisnici' => 'Upravljanje korisnicima', 
						  'forumi' => 'Upravljanje forumima', 
						  'profil' => 'Upravljanje osobnim podatcima',
						  'divider' => '',
						  'odjava' => 'Odjavi se');
		
		$linkovi = array ('kolegiji' => 'upravljanjeKolegijima/index', 
						  'sadrzaji' => 'upravljanjesadrzajem/index', 
						  'grupe' => 'upravljanjeGrupama/index', 
						  'korisnici' => 'upravljanjeKorisnicima/index', 
						  'forumi' => 'upravljanjeForumima/index', 
						  'profil' => 'upravljanjeProfilom/index',
						  'divider' => '',
						  'odjava' => 'loginKorisnika/odlogiraj');
		
		$klase   = array ('kolegiji' =>  'icon-file', 
						  'sadrzaji' => 'icon-book', 
						  'grupe' => 'icon-globe', 
						  'korisnici' => 'icon-user', 
						  'forumi' => 'icon-comment', 
						  'profil' => 'icon-cog' ,
						  'divider' => 'divider',
						  'odjava' => 'icon-off');
		$menu = "";
		foreach ($petlja as $key) {
			if ($key == 'divider') {
				$menu .= sprintf ("<li class=\"divider\"></li>");
			} else {
				if ($aktivan == $key) {
					$menu .= sprintf ("<li class=\"active\"><a href=\"%s\"><i class=\"%s icon-white\"></i>&nbsp;%s</a></li>", site_url($linkovi[$key]), $klase[$key], $nazivi[$key]);
				} else {
					$menu .= sprintf ("<li><a href=\"%s\"><i class=\"%s\"></i>&nbsp;%s</a></li>", site_url($linkovi[$key]), $klase[$key], $nazivi[$key]);
				}
			}
		}
		
		echo $menu;
	}
}

if (!function_exists('daj_meni_kolegija')) {
	function daj_meni_kolegija ($korisnik, $id = 0, $url = "kolegiji/index/") {
		$CI = &get_instance ();
		
		$CI->load->model('kolegij');
		
		$kolegiji = array ();
		if ($korisnik['uloga'] == "Administrator") {
			$kolegiji = $CI->kolegij->daj_sve("DESC");			
		} else {
			$CI->load->model('grupa_kolegij');
			$CI->load->model('grupa_korisnik');
			$grupe_korisnik = $CI->grupa_korisnik->daj_grupe_za_korisnika ($korisnik['id']);
			foreach ($grupe_korisnik as $grupa_korisnik) {
				$grupe_kolegij = $CI->grupa_kolegij->daj_kolegije_za_grupu($grupa_korisnik['id_grupe']);
				foreach ($grupe_kolegij as $grupa_kolegij) {
					$kolegij = 	$CI->kolegij->daj($grupa_kolegij['id_kolegija']);
					array_push ($kolegiji, $kolegij);
				}
			}
		}
		
		
		$var = "";
		foreach ($kolegiji as $kolegij) {
			$var .= "<li";
			if ($kolegij['id'] == $id) $var .= " class=\"active\" ";
			$var .= "><a href=\"". site_url($url.$kolegij['id']) ."\">" . $kolegij['ime'] . "</a></li>";	
		}
		return $var;
	}
}