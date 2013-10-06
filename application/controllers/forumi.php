<?php
class Forumi extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		$this->load->model ('korisnik');
		$this->load->model ('forum');
		$this->load->model ('grupa_korisnik');
		$this->load->model ('grupa_forum');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		$this->load->helper (array ('paginacija_helper', 'menu_helper', 'greske_helper', 'forum_helper'));
			
		if (!$this->korisnik->je_logiran()) { 
			redirect ('loginKorisnika/index');
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();	
		}
	}	
	
	public function index ($id = 0) {
		$id = intval ($id);
		
		if ($this->logiraniKorisnik['uloga'] == 'Administrator') {
			$podaci['forumi'] = $this->forum->daj_forume ($id, BROJ_STAVKI, "DESC");
			$podaci['broj_foruma'] = $this->forum->broj_foruma ();
		} else {
			$grupe = $this->grupa_korisnik->daj_grupe_za_korisnika ($this->logiraniKorisnik['id']);
			$id_grupa = array ();
			$forumi = array ();

			foreach ($grupe as $grupa) {
					array_push ($id_grupa, $grupa['id_grupe']);
			}
			if (count($id_grupa) > 0)
				$forumi = $this->grupa_forum->daj_grupe_za_forume($id_grupa);
			
			
			if (count($forumi) > 0) {
				$id_foruma = array ();
				foreach ($forumi as $forum) {
					array_push ($id_foruma, $forum['id_foruma']);
				}
				$id_foruma = array_unique($id_foruma);
				$podaci['forumi'] = $this->forum->daj_forume_za_id ($id_foruma, $id, BROJ_STAVKI, "DESC");
				$podaci['broj_foruma'] = count ($id_foruma);
			} else {
				$podaci['forumi'] = array ();
				$podaci['broj_foruma'] = 0;
			}
		}
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		$this->load->view ('forumi', $podaci);
	}
}
?>