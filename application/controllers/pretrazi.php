<?php
class Pretrazi extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct ();
		$this->load->model ('korisnik');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if (!$this->korisnik->je_logiran()) { 
		 echo json_encode (array ("greska"=>1, "tekst" => "Niste ulogirani na sustav, molimo logirajte se da bi koristili ovu funkcionalnost."));
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();	
		}	
	}
	
	public function korisnika () {
		$vrijednost = $this->input->get('q');
		$rezultati = $this->korisnik->pretrazi($vrijednost);
		$korisnici = array ();
		foreach ($rezultati as $rezultat) {
			$korisnik = array (
				'ime' => $rezultat['puno_ime'],
				'url' => urlencode(site_url('profil/pogledaj/'.$rezultat['id'])),
				'avatar' => urlencode(base_url('avatari/48x48/'.$rezultat['avatar']))
			);
			array_push($korisnici, $korisnik);
		}	
		echo json_encode ($korisnici);
	}	
}
?>