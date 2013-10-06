<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists ('daj_bodove_za_lekciju')) {
	function daj_bodove_za_lekciju ($id_sadrzaja, $id_korisnika) {
		$CI = &get_instance();
		
		$CI->load->model ('pokusaj');
		$CI->load->model ('scormvarijable');
		
		$pokusaji = $CI->pokusaj->daj_za($id_korisnika, $id_sadrzaja, 0, 0, "DESC");

		$rezultat = 0;
		$brojac = 0;
		foreach ($pokusaji as $pokusaj) {
			$scorm_var = $CI->scormvarijable->daj_za_element($pokusaj['id'], 'cmi.core.score.raw');
			$rezultat += floor($scorm_var['vrijednost']);
			$brojac++;		
		}
		
		if ($brojac != 0)
		return round($rezultat/$brojac, 1);
		else 
		return 0;
	}
}
?>