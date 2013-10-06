<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists ('rrmdir')) {
	function rrmdir($dir) { 
	   if (is_dir($dir)) { 
		 $objects = scandir($dir); 
		 foreach ($objects as $object) { 
		   if ($object != "." && $object != "..") { 
			 if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
		   } 
		 } 
		 reset($objects); 
		 rmdir($dir); 
	   } 
	} 
}
if (!function_exists ('pobrisi_sadrzaj')) {
	function pobrisi_sadrzaj ($id, $logiraniKorisnik) {
		$CI = &get_instance();
		$CI->load->model('sadrzaj');
		if ($CI->sadrzaj->postoji ($id)) {
			$sadrzaj = $CI->sadrzaj->daj ($id);
			if ($sadrzaj['id_korisnika'] == $logiraniKorisnik['id']) {
				$CI->sadrzaj->pobrisi ($id);
				rrmdir ("./scorms/paketi/".$id);	
				return TRUE;
			} else {
				prikazi_gresku (
						$logiraniKorisnik, 
						"Nemate ovlasti da brišete nastavni sadrzaj jer ga niste Vi dodali.", 
						'upravljanjeSadrzajem/index');	
			}
		} else {
			prikazi_gresku (
						$logiraniKorisnik, 
						"Sadrzaj koji ste odabrali za brisanje ne postoji.", 
						'upravljanjeSadrzajem/index');		
		}	
	}	
}
?>