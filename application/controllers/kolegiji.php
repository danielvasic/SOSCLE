<?php
class Kolegiji extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		
		$this->load->helper(array ('paginacija_helper', 'menu_helper', 'greske_helper', 'forum_helper', 'grupe_helper'));
		$this->load->model('sadrzaj');
		$this->load->model('kolegij');
		$this->load->model('korisnik');
		$this->load->model('grupa_korisnik');
		$this->load->model('grupa_kolegij');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if ($this->korisnik->je_logiran()) {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();
		} else {
			redirect ('loginKorisnika/index');	
		}
	}
	
	public function index ($id) {
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if ($this->kolegij->postoji ($id)) {
			if ($this->provjeri_grupe($id)) {
				$this->load->library('uri');
				$limit = intval($this->uri->segment(4));
				$podaci['kolegij'] = $this->kolegij->daj ($id);
				$podaci['sadrzaji'] = $this->sadrzaj->daj_sa_kolegija ($id, $limit, BROJ_STAVKI, "DESC");
				$podaci['broj_sadrzaja'] = $this->sadrzaj->prebroji_po_kolegiju($id);
				$this->load->view ('prikazKolegija', $podaci);
			} else {
				prikazi_gresku (
						$this->logiraniKorisnik, 
						"Nemate pristup kolegiju koji ste odabrali.", 
						'profil/index');	
			}
		} else {
			prikazi_gresku (
						$this->logiraniKorisnik, 
						"Kolegij koji ste odabrali ne postoji.", 
						'profil/index');	
		}
	}
	
	private function provjeri_grupe ($id_kolegija) {
		if($this->logiraniKorisnik['uloga'] == "Administrator") return TRUE;
		$grupe_kolegiji = $this->grupa_kolegij->daj_grupe_za_kolegij($id_kolegija);
		$grupe_korisnici = $this->grupa_korisnik->daj_grupe_za_korisnika ($this->logiraniKorisnik['id']);
		foreach ($grupe_kolegiji as $grupa_kolegij) {
			foreach ($grupe_korisnici as $grupa_korisnik) {
				if ($grupa_korisnik['id_grupe'] === $grupa_kolegij['id_grupe']) return TRUE;	
			}
		}
		return FALSE;
	}
}
?>