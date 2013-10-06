<?php
class Teme extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		$this->load->model ('korisnik');
		$this->load->model ('forum');
		$this->load->model ('tema');
		$this->load->model ('post');
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
		$this->load->library ('uri');
		$stranica = intval($this->uri->segment (4));
		if ($this->forum->postoji ($id)) {
			if ($this->provjeri_grupe($id)) {
				$podaci['stranica'] = $stranica;
				$podaci['forum'] = $this->forum->daj ($id);
				$podaci['teme'] = $this->tema->daj_za_forum($id, BROJ_STAVKI, $stranica);
				$podaci['broj_tema'] = $this->tema->po_forumima_prebroji($id);
				$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
				$this->load->view ('teme', $podaci);				
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Došlo je do pogreške nemate pristup ovom forumu.", 
					'forumi/index');	
			}
		} else {
			prikazi_gresku (
				$this->logiraniKorisnik, 
				"Došlo je do pogreške taj forum ne postoji.", 
				'forumi/index');
		}
	}
	
	private function provjeri_grupe ($id_foruma) {
		if($this->logiraniKorisnik['uloga'] == "Administrator") return TRUE;
		$grupe_foruma = $this->grupa_forum->daj_grupe_za_forum ($id_foruma);
		$grupe_korisnici = $this->grupa_korisnik->daj_grupe_za_korisnika ($this->logiraniKorisnik['id']);
		foreach ($grupe_foruma as $grupa_forum) {
			foreach ($grupe_korisnici as $grupa_korisnik) {
				if ($grupa_korisnik['id_grupe'] === $grupa_forum['id_grupe']) return TRUE;	
			}
		}
		return FALSE;
	}
	
	public function dodaj ($id) {
		$id = intval($id);
		if ($this->forum->postoji ($id)) {
			$forum = $this->forum->daj($id);
			if ($this->provjeri_grupe($id) || $this->logiraniKorisnik['uloga'] == "Administrator") {
				if ($forum['status'] == 'otkljucan') {
					if ($this->provjeri_unos()) {
						$ime_teme = $this->input->post ('ime');
						$opis_teme = $this->input->post ('opis');
						$id_teme = $this->tema->spasi($id, $this->logiraniKorisnik['id'], $ime_teme, $opis_teme);
						echo json_encode (
							array (
								'greska' => 0,
								'url_teme' => urlencode(site_url ('postovi/index/'.$id_teme)),
								'url_korisnika' => urlencode (site_url('profil/pogledaj/'. $this->logiraniKorisnik['id'])),
								'ime_korisnika' => $this->logiraniKorisnik['ime'] . " " . $this->logiraniKorisnik['prezime'], 
								'url_slike' => urlencode (base_url ('stil/moj_stil/chat.png')),
								'datum' => date ("Y-m-d G:i:s")
							)
						);
					} else {
						echo json_encode(
							array (
									"greska" => 1, 
									"tekst" => validation_errors())
						);	
					}
				} else {
					echo json_encode(
						array (
								"greska" => 1, 
								"tekst" => 'Nemate pristup ovom forumu.')
					);	
				}
			} else {
				echo json_encode(
					array (
							"greska" => 1, 
							"tekst" => 'Forum je zaključan nemožete dodavati nove teme.')
				);				
			}
		} else {
			echo json_encode (
				array (
						"greska" => 1, 
						"tekst" => "Forum u koji zelite dodati temu ne postoji.")
			);	
		}	
	}
	
	public function uredi ($id) {
		if (count($_POST) > 0) {
			if ($this->provjeri_unos()) {
				if ($this->tema->postoji ($id)) {
					$tema = $this->tema->daj($id);
					if ($tema['id_korisnika'] == $this->logiraniKorisnik['id']) {
						$ime_teme = $this->input->post ('ime');
						$opis_teme = $this->input->post ('opis');
						$this->tema->azuriraj ($tema['id'], $tema['id_foruma'], $tema['id_korisnika'], $ime_teme, $opis_teme);
						echo json_encode (array ('greska' => 0));
					} else {
						echo json_encode (
						array ('greska' => 1, "tekst" => "Temu koju ste izabrali ne možete uređivati.")
					);	
					}
				} else {
					echo json_encode (
						array ('greska' => 1, "tekst" => "Tema koju ste izabrali ne postoji.")
					);
				}
			} else {
				echo json_encode (
					array ('greska' => 1, "tekst" => validation_errors())
				);	
			}
		} else {
			if ($this->tema->postoji ($id)) {
				$tema = $this->tema->daj($id);
				if ($tema['id_korisnika'] == $this->logiraniKorisnik['id']) {
					echo json_encode (array('greska' => 0, 'ime_teme' => $tema['ime'], 'opis_teme' => $tema['opis']));
				} else {
					echo json_encode (array ('greska' => 1, 'tekst' => "Nemožete uređivati ovaj post jer nemate odgovarajuće privilegije."));	
				}
			} else {
				echo json_encode (array ('greska' => 1, 'tekst' => "Tema koju ste izabrali ne postoji."));
			}
		}	
	}
	
	public function zakljucaj_otkljucaj ($id) {
		if ($this->tema->postoji ($id)) {
			$tema = $this->tema->daj($id);
			if ($tema['status'] == 'otkljucan') $status = 'zakljucan';
			else $status = 'otkljucan';
			if ($this->logiraniKorisnik['id'] == $tema['id_korisnika']) {
				$this->tema->azuriraj($tema['id'], $tema['id_foruma'],  $tema['id_korisnika'], $tema['ime'], $tema['opis'], $status);
				echo json_encode (array("uspjeh" => 1, "status" => $status));	
			}	
		}	
	}
	
	public function brisi ($id) {
		if ($this->tema->postoji ($id)) {
			$tema = $this->tema->daj($id);
			if ($tema['id_korisnika'] == $this->logiraniKorisnik['id']) {
				$this->tema->pobrisi($id);
				$postovi = $this->post->daj_za_temu ($tema['id']);
				foreach ($postovi as $post) {
					$this->post->pobrisi($post['id']);
				}
				redirect ('teme/index/'. $tema['id_foruma']);
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Došlo je do pogreške niste ulogirani kao korisnik koji je pobrisao temu, stoga nemožete pobrisati temu.", 
					'forumi/index');	
			}	
		}	
	}	
	
	public function provjeri_unos () {
		$this->load->library ('form_validation');
		$this->form_validation->set_message ('required', 'Polje %s je obavezno.');
		$this->form_validation->set_message ('min_length', 'Polje %s je prekratko.');
		$this->form_validation->set_message ('max_length', 'Polje %s je predugo.');	
		
		$this->form_validation->set_rules ('ime', 'Ime', "required|xss_clean|min_length[6]|max_length[25]");
		$this->form_validation->set_rules ('opis', 'Opis', "required|xss_clean|min_length[20]|max_length[2500]");
		return $this->form_validation->run();
	}
}
?>