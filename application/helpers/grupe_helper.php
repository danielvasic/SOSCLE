<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists ('broj_grupa')) {
	function broj_grupa ($id) {
		$ci = & get_instance();
		$ci->load->model ('grupa_kolegij');
		$ci->load->model('kolegij');
		$broj_grupa = 0;
		if ($ci->kolegij->postoji ($id)) {
			$broj_grupa = $ci->grupa_kolegij->broj_grupa($id);
		}	
		return "<span class=\"label label-danger\" id=\"kolegij_br_grupa_$id\">$broj_grupa</span>";
	}
}

if (!function_exists ('broj_sadrzaja')) {
	function broj_sadrzaja ($id) {
		$ci = & get_instance();
		$ci->load->model ('sadrzaj');
		$ci->load->model('kolegij');
		$broj_grupa = 0;
		if ($ci->kolegij->postoji ($id)) {
			$broj_sadrzaja = $ci->sadrzaj->prebroji_po_kolegiju($id);
		}	
		return "<span class=\"label label-danger\" id=\"kolegij_br_sadrzaja_$id\">$broj_sadrzaja</span>";
	}
}

if (!function_exists ('daj_ime_kolegija')) {
	function daj_ime_kolegija ($id) {
		$CI = &get_instance();
		$CI->db->where ('id', $id);
		$upit = $CI->db->get('kolegij');
		$kolegij = $upit->row_array ();
		return $kolegij['ime'];	
	}
}
?>