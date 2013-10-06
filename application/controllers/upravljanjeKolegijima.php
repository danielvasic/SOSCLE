<?php 
class UpravljanjeKolegijima extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct ();
				
		$this->load->helper (array ('form', 'menu_helper', 'paginacija_helper', 'greske_helper',  'forum_helper', 'grupe_helper', 'dopustenja_helper'));
		$this->load->model ('kolegij');
		$this->load->model ('sadrzaj');
		$this->load->model ('grupa_kolegij');
		$this->load->model ('korisnik');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if ($this->korisnik->je_logiran ()) {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika ();
			if (!provjeri_dopustenja($this->logiraniKorisnik['uloga'], 'kolegiji')) redirect ('profil/index');
		} else {
			redirect ("loginKorisnika/index");	
		}		
	}	
	
	public function index ($id=0) {
		$podaci ['kolegiji'] = $this->kolegij->daj_uz_limit ($id, BROJ_STAVKI, "DESC");
		$podaci ['broj_stavki'] = $this->kolegij->prebroji ();
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		$this->load->view ('administracijaKolegija', $podaci);
	}
	
	public function dodaj () {
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if (count ($_POST) > 0) {
			if ($this->provjeri_unos()) {
				$ime = $this->input->post ('ime');
				$opis = $this->input->post ('opis');
				$insert_id = $this->kolegij->spasi ($this->logiraniKorisnik['id'], $ime, $opis);
				if ($insert_id > 0) {
					redirect ('upravljanjeKolegijima/index');
				} else {
					prikazi_gresku (
						$this->logiraniKorisnik, 
						"Došlo je do prilikom dodavanja u bazu podataka, molimo pokusajte ponovo.", 
						'upravljanjeKolegijima/dodaj');	
				}
				
			} else {
				$podaci['greska'] = validation_errors ();
				$this->load->view ('dodajKolegij', $podaci);
			}
		} else {
			$this->load->view ('dodajKolegij', $podaci);
		}
	}
	
	public function uredi ($id) {
		if ($this->kolegij->postoji ($id)) {
			$podaci['kolegij'] = $this->kolegij->daj($id);
			$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
			if ($podaci['kolegij']['id_korisnika'] == $this->logiraniKorisnik['id']) {
				if (count($_POST) > 0) {
					if ($this->provjeri_unos()) {
						$ime = $this->input->post('ime');
						$opis = $this->input->post('opis');
						
						$this->kolegij->uredi($podaci['kolegij']['id'], $podaci['kolegij']['id_korisnika'], $ime, $opis);
						redirect ('upravljanjeKolegijima/index');
					} else {
						$podaci['greska'] = validation_errors();
						$this->load->view('urediKolegij', $podaci);	
					}
				} else {
					$this->load->view('urediKolegij', $podaci);	
				}
			} else {
				prikazi_gresku (
						$this->logiraniKorisnik, 
						"Došlo je do prilikom uređivanja kolegija, nemate ovlasti za uređivanje kolegija.", 
						'upravljanjeKolegijima/index');	
			}
		} else {
			prikazi_gresku (
						$this->logiraniKorisnik, 
						"Kolegij koji ste odabrali ne postoji.", 
						'upravljanjeKolegijima/index');
		}	
	} 
	
	public function brisi ($id) {
		if ($this->kolegij->postoji ($id)) {
			$kolegij = $this->kolegij->daj ($id);

			if ($this->logiraniKorisnik['id'] == $kolegij['id_korisnika']){
				$sadrzaji = $this->sadrzaj->daj_sa_kolegija($id);
				$this->load->helper ('delete_helper');
				foreach ($sadrzaji as $sadrzaj) {
					pobrisi_sadrzaj($sadrzaj['id'], $this->logiraniKorisnik);
				}
				$this->kolegij->brisi ($id);
				redirect ('upravljanjeKolegijima/index');	
			} else {
				prikazi_gresku (
						$this->logiraniKorisnik, 
						"Došlo je do prilikom brisanja kolegija, nemate ovlasti za brisanje kolegija.", 
						'upravljanjeKolegijima/index');	
			}
		} else {
			prikazi_gresku (
						$this->logiraniKorisnik, 
						"Kolegij koji zelite pobrisati ne postoji.", 
						'upravljanjeKolegijima/index');		
		}
	}
	
	public function daj_za_brisati ($id) {
		$grupe = $this->grupa_kolegij->daj_grupe_za_brisati ($id);
		echo json_encode ($grupe);
	}
	
	
	public function daj_za_dodati ($id) {
		$grupe = $this->grupa_kolegij->daj_grupe_za_dodati ($id);
		echo json_encode ($grupe);
		
	}
	
	public function dodaj_grupe ($id) {
		if (count ($_POST) > 0) {
			if ($this->kolegij->postoji ($id)) {
				$polja = $this->input->post ('grupe');
				$polja = explode(",", $polja);
				
				foreach ($polja as $grupa_id) {
					$this->grupa_kolegij->dodaj_zapis ($grupa_id, $id);	
				}
				
				echo json_encode (array ("broj_grupa" => $this->grupa_kolegij->broj_grupa($id)));
			}
		}	
	}
	
	public function brisi_grupe ($id) {
		if (count ($_POST) > 0) {
			if ($this->kolegij->postoji ($id)) {
				$polja = $this->input->post ('grupe');
				$polja = explode(",", $polja);
				
				foreach ($polja as $grupa_id) {
					$this->grupa_kolegij->brisi_zapis ($grupa_id, $id);	
				}
				
				echo json_encode (array ("broj_grupa" => $this->grupa_kolegij->broj_grupa($id)));
			}
		}	
	}
	
	
	public function provjeri_unos () {
		$this->load->library ('form_validation');
		$this->form_validation->set_message ("required", "Polje %s je obavezno.");
		$this->form_validation->set_message ("min_length", "Sadržaj polja %s je prekratak.");
		$this->form_validation->set_message ("max_length", "Sadržaj polja %s je predug.");
		
		
		$this->form_validation->set_rules ('ime', 'Ime', 'required|trim|xss_clean|min_length[6]|max_length[25]');
		$this->form_validation->set_rules ('opis', 'Opis', 'required|trim|xss_clean|min_length[10]');	
		return $this->form_validation->run();
	}
}

?>