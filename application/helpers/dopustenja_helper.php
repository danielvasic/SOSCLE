<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists ('provjeri_dopustenja')) {
	function provjeri_dopustenja ($uloga, $akcija) {
		$Administrator = array('kolegiji', 'sadrzaji', 'grupe', 'korisnici', 'forumi', 'profil', 'divider', 'odjava');
		$Ucenik = array('profil', 'divider', 'odjava');
		$Ucitelj = array('sadrzaji', 'forumi', 'profil', 'divider', 'odjava');
		
		if ($uloga == "Administrator") { if (!in_array ($akcija, $Administrator)) { return FALSE; } }
		if ($uloga == "Ucenik") { if (!in_array ($akcija, $Ucenik)) { return FALSE; } }
		if ($uloga == "Ucitelj") { if (!in_array ($akcija, $Ucitelj)) { return FALSE; } }
		return TRUE;
	}
}
?>