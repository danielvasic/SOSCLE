<?php 
class Postovi extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		$this->load->model ('korisnik');
		$this->load->model ('forum');
		$this->load->model ('tema');
		$this->load->model ('post');
		$this->load->model ('grupa_korisnik');
		$this->load->model ('grupa_forum');
		$this->load->library ("form_validation");
		
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
	
	public function index($id = 0) {
		if ($this->tema->postoji($id)) {
			$podaci['tema'] = $this->tema->daj($id);
			$podaci['forum'] = $this->forum->daj($podaci['tema']['id_foruma']);
			if ($this->provjeri_grupe($podaci['tema']['id_foruma']) ) {
				$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
				$this->load->view ('postovi', $podaci);
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Došlo je do pogreške nemate pristup ovoj temi.", 
					'forumi/index');
			}
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
	
	public function daj_postove ($id) {
		if ($this->tema->postoji($id)) {
			$tema = $this->tema->daj($id);
			if ($this->provjeri_grupe($tema['id_foruma'])) {
				$this->load->library ('uri');
				$stranica = $this->uri->segment(4)*BROJ_STAVKI;
				$podaci = $this->post->daj_postove_za_temu($id, $stranica);
				$json_postovi= array();
				header("Content-type:application/json");
				foreach ($podaci as $podatak) {
					$korisnik = $this->korisnik->daj_korisnika($podatak['id_korisnika']);
					$json_post = array (
							'id_posta' => $podatak['id_posta'],
							'ime_korisnika' => $korisnik['ime'] . " " . $korisnik['prezime'],
							'uloga' => $korisnik['uloga'],
							'url_korisnika' => site_url('profil/pogledaj/'.$korisnik['id']),
							'url_avatara' => base_url('avatari/128x128/'.$korisnik['avatar']), 
							'ime_posta' => $podatak['ime_posta'],
							'sadrzaj_posta' => $podatak['sadrzaj_posta'],
							'datum' => $podatak['vrijeme_posta'],
							'id_roditelja' => $podatak['id_roditelja'],
							'broj_odgovora' => $this->post->prebroji_broj_odgovora($podatak['id_posta']),
							'id_korisnika' => $this->logiraniKorisnik['id'],
							'id_autora' => $podatak['id_korisnika']
						);
					array_push($json_postovi, $json_post);
				}
				header ('Content-type: application/json');
				echo json_encode($json_postovi);
			} else {
				header ('Content-type: application/json');
				echo (json_encode (array ('greska' => 1, 'tekst' => 'Nemate pristup ovoj temi.')));
			}
		} else {
			header ('Content-type: application/json');
			echo (json_encode (array ('greska' => 1, 'tekst' => 'Ta tema ne postoji.')));
		}	
	}
	
	public function dodaj_post($id = 0) {
		if ($this->tema->postoji($id)) {
			$tema = $this->tema->daj($id);
			if ($this->provjeri_grupe($tema['id_foruma'])) {
				if ($this->provjeri_unos()) {
					$ime = $this->input->post('imePosta');
					$sadrzaj = $this->input->post('sadrzajPosta');
					$id_posta = $this->post->spasi ($this->logiraniKorisnik['id'], $id, $tema['id_foruma'], $ime, $sadrzaj);
					header ('Content-type: application/json');
					echo json_encode (
						array (
							'id_posta' => $id_posta,
							'ime_korisnika' => $this->logiraniKorisnik['ime'] . " " . $this->logiraniKorisnik['prezime'],
							'uloga' => $this->logiraniKorisnik['uloga'],
							'url_korisnika' => site_url('profil/pogledaj/'.$this->logiraniKorisnik['id']),
							'url_avatara' => base_url('avatari/128x128/'.$this->logiraniKorisnik['avatar']), 
							'ime_posta' => $ime,
							'sadrzaj_posta' => $sadrzaj,
							'datum' => date("Y-m-s G:i:s"),
							'greska' => 0,
							'id_korisnika' => $this->logiraniKorisnik['id'],
							'id_autora' => $this->logiraniKorisnik['id']
						)
					);
				} else {
					header ('Content-type: application/json');
					echo json_encode (
						array (
							'greska' => 1,
							'tekst' => validation_errors()
						)
					);	
				}
			} else {
				header ('Content-type: application/json');
				echo (json_encode (array ('greska' => 1, 'tekst' => 'Nemate pristup ovoj temi.')));
			}
		}
	}
	
	public function dodaj_odgovor($id = 0) {
		if ($this->tema->postoji($id)) {
			$tema = $this->tema->daj($id);
			if ($this->provjeri_grupe($tema['id_foruma'])) {
				if ($this->provjeri_unos_odgovora()) {
					$id_posta = $this->input->post('id_posta');
					$ime = $this->input->post('ime');
					$sadrzaj = $this->input->post('sadrzaj');
					$id_posta = $this->post->spasi ($this->logiraniKorisnik['id'], $id, $tema['id_foruma'], $ime, $sadrzaj, "", $id_posta);
					header ('Content-type: application/json');
					echo json_encode (
						array (
							'id_posta' => $id_posta,
							'ime_korisnika' => $this->logiraniKorisnik['ime'] . " " . $this->logiraniKorisnik['prezime'],
							'uloga' => $this->logiraniKorisnik['uloga'],
							'url_korisnika' => site_url('profil/pogledaj/'.$this->logiraniKorisnik['id']),
							'url_avatara' => base_url('avatari/128x128/'.$this->logiraniKorisnik['avatar']), 
							'ime_posta' => $ime,
							'sadrzaj_posta' => $sadrzaj,
							'datum' => date("Y-m-s G:i:s"),
							'greska' => 0,
							'broj_odgovora' => $this->post->prebroji_broj_odgovora($id_posta),
							'id_korisnika' => $this->logiraniKorisnik['id'],
							'id_autora' => $this->logiraniKorisnik['id']
						)
					);
				} else {
					header ('Content-type: application/json');
					echo json_encode (
						array (
							'greska' => 1,
							'tekst' => validation_errors()
						)
					);	
				}
			} else {
				header ('Content-type: application/json');
				echo (json_encode (array ('greska' => 1, 'tekst' => 'Nemate pristup ovoj temi.')));
			}
		}
	}
	
	public function daj_odgovore ($id_teme) {
		if ($this->tema->postoji($id_teme)) {
			$tema = $this->tema->daj($id_teme);
			if ($this->provjeri_grupe($tema['id_foruma'])) {
				$id_posta = $this->input->get('id_posta');
					
				header ('Content-type: application/json');
				$json_postovi = array ();
				$postovi = $this->post->daj_odgovore ($id_teme, $id_posta);
				foreach ($postovi as $post) {
					$korisnik = $this->korisnik->daj_korisnika ($post['id_korisnika']);
					
					$json_post = array (
							'id_posta' => $post['id'],
							'ime_korisnika' => $korisnik['ime'] . " " . $korisnik['prezime'],
							'uloga' => $korisnik['uloga'],
							'url_korisnika' => site_url('profil/pogledaj/'.$korisnik['id']),
							'url_avatara' => base_url('avatari/128x128/'.$korisnik['avatar']), 
							'ime_posta' => $post['ime'],
							'sadrzaj_posta' => $post['sadrzaj'],
							'datum' => $post['vrijeme'],
							'id_roditelja' => $post['id_roditelja'],
							'broj_odgovora' => $this->post->prebroji_broj_odgovora($post['id']),
							'id_korisnika' => $this->logiraniKorisnik['id'],
							'id_autora' => $post['id_korisnika']
						);
					array_push($json_postovi, $json_post);	
				}
				header ('Content-type: application/json');
				echo json_encode ($json_postovi);
			} else {
				header ('Content-type: application/json');
				echo (json_encode (array ('greska' => 1, 'tekst' => 'Nemate pristup ovoj temi.')));
			}
		}
	}
	
	
	
	public function uredi_post($id = 0) {
		if (count($_POST) > 0) {
			if ($this->post->postoji ($id) > 0) {
				$post = $this->post->daj($id);
				if ($post['id_korisnika'] == $this->logiraniKorisnik['id']) {
					$ime = $this->input->post ('imePosta');
					$sadrzaj = $this->input->post ('sadrzajPosta');
					
					if ($this->provjeri_unos()) {
						$this->post->azuriraj ($id, $this->logiraniKorisnik['id'], $post['id_teme'], $post['id_foruma'], $ime, $sadrzaj, "", $post['id_roditelja']);
						$json_post = array (
							'id_posta' => $post['id'],
							'ime_korisnika' =>  $this->logiraniKorisnik['ime'] . " " .  $this->logiraniKorisnik['prezime'],
							'uloga' =>  $this->logiraniKorisnik['uloga'],
							'url_korisnika' => site_url('profil/pogledaj/'. $this->logiraniKorisnik['id']),
							'url_avatara' => base_url('avatari/128x128/'. $this->logiraniKorisnik['avatar']), 
							'ime_posta' => $ime,
							'sadrzaj_posta' => $sadrzaj,
							'datum' => date ("Y-m-d G:i:s"),
							'id_roditelja' => $post['id_roditelja'],
							'broj_odgovora' => $this->post->prebroji_broj_odgovora($post['id']),
							'id_korisnika' => $this->logiraniKorisnik['id'],
							'id_autora' => $post['id_korisnika'],
							'greska' => 0
						);
						
						echo (json_encode ($json_post));
					} else {
						echo json_encode (array ("greska" => 1, "tekst" => validation_errors ()));
					}
				} else {
					echo json_encode (array ("greska" => 1, "tekst" => "Niste autor posta, nemožete ga uređivati."));	
				}
			}
		} else {
			if ($this->post->postoji ($id) > 0) {
				$post = $this->post->daj($id);
				echo json_encode (
					array (
							'ime' => $post['ime'], 
							'sadrzaj' => $post['sadrzaj']
					)
				);
			} else {
				echo json_encode (array ("greska" => 1, "tekst" => "Post ne postoji"));
			}
		}
	}
	
	private function provjeri_unos_odgovora () {
		$this->form_validation->set_message ('required', "Polje %s je obavezno.");
		$this->form_validation->set_message ('min_length', "Polje %s je prekratko.");
		$this->form_validation->set_message ('max_length', "Polje %s je predugo.");
		$this->form_validation->set_rules ('ime', "Ime", 'trim|required|xss_clean|min_length[5]|max_length[25]');
		$this->form_validation->set_rules ('sadrzaj', "Sadrzaj", 'trim|required|xss_clean|min_length[10]|max_length[2500]');	
		return $this->form_validation->run();
	}
	
	private function provjeri_unos () {
		$this->form_validation->set_message ('required', "Polje %s je obavezno.");
		$this->form_validation->set_message ('min_length', "Polje %s je prekratko.");
		$this->form_validation->set_message ('max_length', "Polje %s je predugo.");
		$this->form_validation->set_rules ('imePosta', "Ime", 'trim|required|xss_clean|min_length[5]|max_length[25]');
		$this->form_validation->set_rules ('sadrzajPosta', "Sadrzaj", 'trim|required|xss_clean|min_length[10]|max_length[2500]');	
		return $this->form_validation->run();
	}
}

?>