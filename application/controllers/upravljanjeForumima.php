<?php
class upravljanjeForumima extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct ();
		$this->load->model ('korisnik');
		$this->load->model ('forum');
		$this->load->model ('grupa');
		$this->load->model ('tema');
		$this->load->model ('post');
		$this->load->model ('grupa_forum');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		
		
		$this->load->helper (array ('form', 'menu_helper', 'paginacija_helper', 'greske_helper', 'forum_helper', 'dopustenja_helper'));
		
		if ($this->korisnik->je_logiran ()) {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika ();
			if (!provjeri_dopustenja($this->logiraniKorisnik['uloga'], 'forumi')) redirect ('profil/index');
		} else {
			redirect ("loginKorisnika/index");	
		}	
	}
	
	public function index ($stranica = 0) {
		$stranica = intval($stranica);
		$podaci ['forumi'] = $this->forum->daj_forume($stranica, BROJ_STAVKI, "ASC");
		$podaci ['logiraniKorisnik'] = $this->logiraniKorisnik;
		$podaci ['broj_foruma'] = $this->forum->broj_foruma ();		
		$this->load->view ('administracijaForuma', $podaci);
	}
	
	public function dodaj () {
		$podaci ['logiraniKorisnik'] = $this->logiraniKorisnik;
		$podaci ['grupe'] = $this->grupa->daj_sve_grupe();
		if (count($_POST) > 0) {
			if ($this->provjeri_unos()) {
				$ime = $this->input->post('imeForuma');
				$opis = $this->input->post('opisForuma');
				$grupe = $this->input->post('grupe');
				$status = $this->input->post('status');
				$id_foruma = $this->forum->spasi($this->logiraniKorisnik['id'], $ime, $opis, $status);
				foreach ($grupe as $grupa) {
					if ($this->grupa->postoji_grupa ($grupa))
					$this->grupa_forum->dodaj_zapis ($id_foruma, $grupa);
				}
				$podaci ['preusmjeri'] = TRUE;
				$podaci ['success'] = TRUE;
				$this->load->view ('dodajForum', $podaci);	
			} else {
				$podaci['greska'] = TRUE;
				$this->load->view ('dodajForum', $podaci);	
			}
		
		} else {
			$this->load->view ('dodajForum', $podaci);
		}
	}
	
	public function uredi ($id = 0) {
		$id = intval($id);
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if ($this->forum->postoji ($id)) {
			$podaci['forum_grupe'] = $this->grupa_forum->daj_grupe($id);
			$podaci['forum'] = $this->forum->daj($id);
			$podaci['grupe'] = $this->grupa->daj_sve_grupe();
			if (count($_POST) > 0) {
					$ime = $this->input->post('imeForuma');
					$opis = $this->input->post('opisForuma');
					$grupe = $this->input->post('grupe');
					$status = $this->input->post('status');
					if ($this->provjeri_unos() == TRUE) {
						$this->forum->azuriraj ($id, $this->logiraniKorisnik['id'], $ime, $opis, $status);
						$this->grupa_forum->pobrisi_forum($id);
						foreach ($grupe as $grupa) {
							if ($this->grupa->postoji_grupa ($grupa))
							$this->grupa_forum->dodaj_zapis ($id, $grupa);
						}
						redirect ('upravljanjeForumima/index');	
					} else {
						$podaci['greska'] = TRUE;
						$this->load->view ('urediForum', $podaci);	
					}
			} else {
				$this->load->view ('urediForum', $podaci);		
			}
		} else {
					prikazi_gresku (
						$this->logiraniKorisnik, 
						"Došlo je do pogreške taj forum ne postoji.", 
						'upravljanjeForumima/index');	
		}
	}
	
	public function toggle_zakljucaj () {
		$id = intval($this->input->get ('id'));
		if ($this->forum->postoji ($id)) {
			$red = $this->forum->daj ($id);
			if ($red ['status'] == 'zakljucan') {
				$this->forum->azuriraj ($id, $this->logiraniKorisnik['id'], $red['ime'], $red['opis'], 'otkljucan');
				echo json_encode (
					array (
						'greska' => 0, 
						'url_slike' => urlencode (base_url('stil/moj_stil/Folder_Open.png')), 
						'status' => 'Zaključaj')
				);
			} else {
				$this->forum->azuriraj ($id, $this->logiraniKorisnik['id'], $red['ime'], $red['opis'], 'zakljucan');
				echo json_encode (
					array (
						'greska' => 0, 
						'url_slike' => urlencode (base_url('stil/moj_stil/Lock.png')),
						'status' => 'Otključaj'
					)
				);
			}
		} else {
			echo json_encode (array ('greska' => 1, 'tekst' => 'Problem prilikom azuriranja podataka forum ne postoji.'));
		}	
	}
	
	public function brisi ($id = 0) {
		if ($this->forum->postoji ($id)) {
			$teme = $this->tema->daj_za_forum ($id);
			foreach ($teme as $tema) {
				$postovi = $this->post->daj_za_temu ($tema['id']);
				$this->tema->pobrisi ($tema['id']);
				foreach ($postovi as $post) {
					$this->post->pobrisi($post['id']);
				}	
			}
			if ($this->forum->pobrisi ($id) > 0) {
				$this->grupa_forum->pobrisi_forum ($id);
				redirect ('upravljanjeForumima/index');				
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Došlo je do pogreške vaš zapis nije pobrisan iz baze podataka molimo pokusajte ponovo.", 
					'upravljanjeForumima/index');
			}	
		} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Došlo je do pogreške vaš zapis nije pobrisan iz baze podataka jer ne taj forum ne postoji.", 
					'upravljanjeForumima/index');			
		}
	}
	
	public function provjeri_unos () {
		$this->load->library('form_validation');
		$this->form_validation->set_message ('required', 'Polje %s je obavezno.');
				
		$this->form_validation->set_message ('required', "Polje %s je obavezno.");
		$this->form_validation->set_message ('min_length', "Duljina polja %s je prekratka.");
		$this->form_validation->set_message ('max_length', "Duljina polja %s je preduga.");
		
		$this->form_validation->set_rules ('imeForuma', "Ime foruma", 'required|xss_clean|min_length[5]|max_length[25]');
		$this->form_validation->set_rules ('grupe', "Grupe", 'required|xss_clean');
		$this->form_validation->set_rules ('status', "Status foruma", 'required|xss_clean');
		$this->form_validation->set_rules ('opisForuma', "Opis foruma", 'required|xss_clean|min_length[25]');
		
		
		return $this->form_validation->run();	
	}
}
?>