<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists ('prikazi_gresku')) {
	function prikazi_gresku ($korisnik, $greska, $url, $preusmjeri = TRUE, $vrijeme = 5){
		$CI = & get_instance();
		$podaci['logiraniKorisnik'] = $korisnik;
		$podaci['greska'] = $greska;
		$podaci['preusmjeri'] = TRUE;
		$podaci['url'] = $url;
		$podaci['vrijeme'] = 5;
		$CI->load->view ('static/error', $podaci);	
	}
}
?>