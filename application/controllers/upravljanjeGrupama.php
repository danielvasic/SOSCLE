<?php
class upravljanjeGrupama extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		
		$this->load->model('korisnik');
		$this->load->model ('grupa');
		$this->load->model ('grupa_korisnik');
		$this->load->model ('grupa_kolegij');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		
		$this->load->helper (array ('form', 'menu_helper', 'paginacija_helper', 'greske_helper', 'dopustenja_helper'));
		
		if (!$this->korisnik->je_logiran()) { 
			redirect ('loginKorisnika/index');
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();	
			if (!provjeri_dopustenja($this->logiraniKorisnik['uloga'], 'grupe')) redirect ('profil/index');
		}
	}
	
	public function index ($id = 0) {
		$stranica = intval($id);
		$podaci['grupe'] = $this->grupa->daj_grupe ($id, BROJ_STAVKI);
		$podaci['korisnici'] = $this->korisnik->daj_ucenike();
		$podaci['path'] = 'upravljanjeGrupama/index';
		$podaci['brojac'] = $this->grupa->broj_grupa ();
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		$this->load->view ('administracijaGrupa', $podaci);
	}
	
	public function pobrisi ($id = 0) {
		$id = intval ($id);
		$rez = $this->grupa->pobrisi ($id);
		$this->grupa_korisnik->pobrisi ($id);
		$this->grupa_kolegij->pobrisi_grupu ($id);
		if ($rez > 0) {
			redirect ('upravljanjeGrupama/index');	
		} else {
			prikazi_gresku (
				$this->logiraniKorisnik, 
				"Doslo je do greske prilikom brisanja grupe molimo pokusajte ponovo.", 
				'upravljanjeGrupama/index');
		}
	}
	
	public function dodaj () {
		$podaci = array ('logiraniKorisnik' => $this->logiraniKorisnik);
		if (count($_POST) > 0) {
			if ($this->provjeri_unos () === TRUE) {
				$ime = $this->input->post ('imeGrupe');
				$opis = $this->input->post ('opisGrupe');
				if ($this->grupa->spasi($ime, $opis) > 0) { 
					$podaci['preusmjeri'] = TRUE;
					$podaci['success'] = TRUE;
					$this->load->view('dodajGrupu', $podaci);
				} else {
					$podaci ['url'] = 'upravljanjeGrupama/index';
					$podaci ['preusmjeri'] = TRUE;
					$podaci ['greska'] = 'Graška prilikom dodavanja u bazu podataka, molimo pokušajte ponovo.';
				}
			} else {
				$podaci['greska'] = TRUE;
				$this->load->view('dodajGrupu', $podaci);
			}
		} else {
			$this->load->view('dodajGrupu', $podaci);
		}	
	}
	
	public function uredi ($id = 0) {
		$id = intval($id);
		$podaci = array ('logiraniKorisnik' => $this->logiraniKorisnik);
		if (count ($_POST) > 0) {
			$ime = $this->input->post ('imeGrupe');
			$opis = $this->input->post ('opisGrupe');
	
			$podaci['imeGrupe'] = $ime;
			$podaci['opisGrupe'] = $opis;
			$podaci['id'] = $id;
			if ($this->provjeri_unos() === TRUE) {

				$this->grupa->azuriraj ($id, $ime, $opis);
				$podaci['preusmjeri'] = TRUE;
				$podaci['success'] = TRUE;
				$this->load->view('urediGrupu', $podaci);
			} else {
				$this->grupa->azuriraj ($id, $ime, $opis);
				$podaci['greska'] = TRUE;				
				$this->load->view('urediGrupu', $podaci);				
			}
		} else {
			$grupa = $this->grupa->daj_grupu ($id);
			$podaci['imeGrupe'] = $grupa['ime'];
			$podaci['opisGrupe'] = $grupa['opis'];
			$podaci['id'] = $grupa['id'];
			$this->load->view('urediGrupu', $podaci);					
		}
	}
	
	public function daj_korisnike_za_dodati () {
		$id = $this->input->get ('id');
		$json = array ();
		$korisnici = $this->grupa_korisnik->daj_korisnike_za_dodati ($id);
		foreach ($korisnici as $red) {
			$resp['id'] = $red['id'];
			$resp['url'] = urlencode(site_url('profilKorisnika/'.$red['id']));
			$resp['puno_ime'] = $red['ime'] . " " . $red['prezime'];
			array_push ($json, $resp);
		}
		echo json_encode ($json);
	}
	
	public function daj_korisnike_za_brisati () {
		$id = $this->input->get ('id');
		$json = array ();
		$korisnici = $this->grupa_korisnik->daj_korisnike_za_brisati ($id);
		foreach ($korisnici as $red) {
			$resp['id'] = $red['id'];
			$resp['url'] = urlencode(site_url('profilKorisnika/'.$red['id']));
			$resp['puno_ime'] = $red['ime'] . " " . $red['prezime'];
			array_push ($json, $resp);
		}
		echo json_encode ($json);
	}
	
	public function dodaj_korisnike () {
		if (count($_POST)) {
			$id_korisnika = $this->input->post ('korisnici');
			$id_grupe = $this->input->post ('grupa');
			$id_ovi = explode(",", $id_korisnika);
			foreach ($id_ovi as $key => $value) {
				$this->grupa_korisnik->dodaj_korisnika ($value, $id_grupe);	
			}
			echo json_encode (array ("uspjeh" => 1,'broj_korisnika' => $this->grupa_korisnik->broj_korisnika($id_grupe)));
		} else {
			echo json_encode (array ("uspjeh" => 0,'greska' => "Došlo je do greške."));
		}
	}
	
	public function brisi_korisnike () {
		if (count($_POST)) {
			$id_korisnika = $this->input->post ('korisnici');
			$id_grupe = $this->input->post ('grupa');
			$id_ovi = explode(",", $id_korisnika);
			foreach ($id_ovi as $key => $value) {
				$this->grupa_korisnik->brisi_korisnika ($value, $id_grupe);	
			}
			echo json_encode (array ("uspjeh" => 1,'broj_korisnika' => $this->grupa_korisnik->broj_korisnika($id_grupe)));
		} else {
			echo json_encode (array ("uspjeh" => 0,'greska' => "Došlo je do greške."));
		}
	}
	
	private function provjeri_unos () {
		$this->load->library('form_validation');
		$this->form_validation->set_message ('required', "Polje %s je obavezno.");
		$this->form_validation->set_message ('min_length', "Duljina polja %s je prekratka.");
		$this->form_validation->set_message ('max_length', "Duljina polja %s je preduga.");
		
		$this->form_validation->set_rules ('imeGrupe', "Ime grupe", 'required|min_length[2]|max_length[25]|xss_clean');
		$this->form_validation->set_rules ('opisGrupe', "Opis grupe", 'required|min_length[25]|xss_clean');
		
		return $this->form_validation->run();
	}
}
?>