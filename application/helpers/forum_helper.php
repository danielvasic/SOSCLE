<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists ('daj_korisnika')) {
	function daj_korisnika ($id_korisnika) {
		$CI =& get_instance();
		$CI->load->model('korisnik');
		$korisnik = $CI->korisnik->daj_korisnika($id_korisnika);
		return '<a href="' . site_url('profil/pogledaj/'.$korisnik['id']) . '">'. $korisnik['ime'] . " " . $korisnik['prezime'] . '</a>';
	}
}

if (!function_exists ('broj_tema')) {	
	function broj_tema ($id_foruma) {
		$CI =& get_instance();
		$CI->load->model('tema');
		$count = $CI->tema->po_forumima_prebroji($id_foruma);
		return $count;
	}
}

if (!function_exists ('zadnja_tema')) {
	
	function zadnja_tema ($id) {
		$CI =& get_instance();
		$CI->load->model ('tema');
		$red = $CI->tema->daj_za_forum($id,1,0);
	
		if (count ($red) == 1) {
			$url = site_url("teme/index/".$id."#tema".$red[0]['id']);
			return sprintf ('<a href="%s">%s</a><br />Autor: <a href="%s">%s</a><br />%s', 
			$url,
			$red[0]['ime'], 
			'profilKorisnika/'.$red[0]['id_korisnika'],
			daj_korisnika($red[0]['id_korisnika']), 
			$red[0]['datum']);
		} else {
			return "<p class='alert alert-warning'>Nema tema.</p>";	
		}
	}
}

if (!function_exists ('daj_ime_foruma')) {
	function daj_ime_foruma ($id) {
		$CI =& get_instance();
		$CI->load->model ('forum');
		$red = $CI->forum->daj($id);
		return $red['ime'];	
	}
}

if (!function_exists ('broj_postova')) {
	function broj_postova ($id_teme) {
		$CI =& get_instance();
		$CI->load->model ('tema');
		if ($CI->tema->postoji($id_teme)) {
			$CI->db->where ('id_teme', $id_teme);
			return $CI->db->count_all_results('post');	
		} 
		return 0;
	}	
}

if (!function_exists ('broj_postova_forum')) {
	function broj_postova_forum ($id_foruma) {
		$CI =& get_instance();
		$CI->load->model ('forum');
		if ($CI->forum->postoji($id_foruma)) {
			$CI->db->where ('id_foruma', $id_foruma);
			return $CI->db->count_all_results('post');	
		} 
		return 0;
	}	
}


if (!function_exists ('zadnji_post')) {
	
	function zadnji_post ($id) {
		$CI =& get_instance();
		$CI->load->model('tema');
		if($CI->tema->postoji($id)) {
			$CI->db->where ('id_teme', $id);
			$CI->db->order_by ('id', 'DESC');
			$upit = $CI->db->get('post');
			$red = $upit->row_array();
			if (count ($red)) {
				$url = site_url("postovi/index/".$red['id_teme']);
				return sprintf ('<a href="%s">%s</a><br />Autor: <a href="%s">%s</a><br />%s', 
				$url,
				$red['ime'], 
				site_url('profilKorisnika/'.$red['id_korisnika']),
				daj_korisnika($red['id_korisnika']), 
				$red['vrijeme']);
			} else {
				return "<p class='alert alert-warning'>Nema postova.</p>";	
		}
		} else {
			return "<p class='alert alert-error'>Tema ne postoji.</p>";		
		}
	}
}

?>