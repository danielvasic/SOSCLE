<?php

class Upravljanjesadrzajem extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		
		$this->load->helper(array ('paginacija_helper', 'menu_helper', 'greske_helper', 'forum_helper', 'grupe_helper', 'delete_helper', 'dopustenja_helper'));
		$this->load->model('sadrzaj');
		$this->load->model('kolegij');
		$this->load->model('korisnik');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if ($this->korisnik->je_logiran()) {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();
			if (!provjeri_dopustenja($this->logiraniKorisnik['uloga'], 'sadrzaji')) redirect ('profil/index');
		} else {
			redirect ('loginKorisnika/index');	
		}
	}
	
	public function dodaj () {
		$podaci ['logiraniKorisnik'] = $this->logiraniKorisnik;
		$podaci['kolegiji'] = $this->kolegij->daj_sve();
		if (count ($_POST) > 0) {
			if ($this->provjeri_unos()) {
				try {
					$ime =$this->input->post('ime');
					$opis = $this->input->post('opis');
					$id_kolegija = $this->input->post ('kolegij');
					$navigacija = $this->input->post('navigacija');
					if ($this->kolegij->postoji ($id_kolegija)) {
						$id = $this->sadrzaj->spasi ($id_kolegija, $this->logiraniKorisnik['id'], $ime, $opis, "/dummy_path/", $navigacija);
						$path = $this->ucitaj_paket($id);
						$this->sadrzaj->azuriraj($id, $id_kolegija, $this->logiraniKorisnik['id'], $ime, $opis, $path, $navigacija);
						$podaci['preusmjeri'] = TRUE;
						$podaci['uspjeh'] = TRUE;
						$this->load->library ('unzip');
						$this->unzip->extract(".".$path, "./scorms/paketi/". $id . "/");
						$this->load->view('dodajSadrzaj', $podaci);
					}
				} catch (Exception $e) {
					$podaci['greska'] = $e->getMessage();
					$this->load->view('dodajSadrzaj', $podaci);	
					pobrisi_sadrzaj($e->getCode(), $this->logiraniKorisnik);
				}
			} else {
				$podaci['greska'] = validation_errors ();
				$this->load->view('dodajSadrzaj', $podaci);	
			}
		} else {

			$this->load->view('dodajSadrzaj', $podaci);
		}
	}
	
	public function index ($id = 0) {
		$podaci['sadrzaji'] = $this->sadrzaj->daj_uz_limit($id, BROJ_STAVKI, "DESC");
		$podaci['broj_sadrzaja'] = $this->sadrzaj->prebroji();
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		$podaci['path'] = 'upravljanjeSadrzajem/index';
		$this->load->view('administracijaSadrzaja', $podaci);
	}
	
	public function brisi ($id) {
		if (pobrisi_sadrzaj($id, $this->logiraniKorisnik)) {
			redirect ('upravljanjeSadrzajem/index');	
		}
	}
	
	public function uredi ($id) {
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if (count($_POST) > 0) {
			if ($this->provjeri_unos() === TRUE) {
				if ($this->sadrzaj->postoji ($id)) {
					$ime = $this->input->post('ime');
					$opis = $this->input->post('opis');
					$id_kolegija = $this->input->post('kolegij');
					$navigacija = $this->input->post('navigacija');
					if ($this->kolegij->postoji ($id_kolegija)) {
						if ($_FILES['scormFile']['name'] != "") {
							try {
								$this->load->helper ('delete_helper');
								rrmdir ("./scorms/paketi/".$id);	
								$path = $this->ucitaj_paket($id);
								$this->load->library ('unzip');
								$this->unzip->extract(".".$path, "./scorms/paketi/". $id . "/");
							} catch (Exception $e) {
								prikazi_gresku (
									$this->logiraniKorisnik, 
									$e->getMessage(), 
									'upravljanjeSadrzajem/uredi/'.$id);	
							}
						} else {
							$sadrzaj = $this->sadrzaj->daj($id);
							$path = $sadrzaj['putanja'];
						}
						$this->sadrzaj->azuriraj($id, $id_kolegija, $this->logiraniKorisnik['id'], $ime, $opis, $path, $navigacija);
						$podaci['preusmjeri'] = TRUE;
						$podaci['uspjeh'] = TRUE;
						$podaci['sadrzaj'] = $this->sadrzaj->daj($id);
						$podaci['kolegiji'] = $this->kolegij->daj_sve();
						$this->load->view('urediSadrzaj', $podaci);
					} else {
						prikazi_gresku (
							$this->logiraniKorisnik, 
							"Sadrzaj koji ste odabrali za uređivanje ne postoji.", 
							'upravljanjeSadrzajem/index');
					}
				} else {
					prikazi_gresku (
						$this->logiraniKorisnik, 
						"Sadrzaj koji ste odabrali za uređivanje ne postoji.", 
						'upravljanjeSadrzajem/index');
				}
			} else {
				$podaci['greske'] = validation_errors ();
				$this->load->view ('dodajSadrzaj', $podaci);	
			} 
		} else {
			if ($this->sadrzaj->postoji ($id)) {
				$sadrzaj = $this->sadrzaj->daj($id);
				if ($sadrzaj['id_korisnika'] == $this->logiraniKorisnik['id']) {
					$podaci['sadrzaj'] = $this->sadrzaj->daj($id);
					$podaci['kolegiji'] = $this->kolegij->daj_sve();
					$this->load->view('urediSadrzaj', $podaci);
				} else {
					prikazi_gresku (
						$this->logiraniKorisnik, 
						"nemate ovlasti za uređivanje ovog paketa.", 
						'upravljanjeSadrzajem/index');	
				}	
			} else {
				prikazi_gresku (
						$this->logiraniKorisnik, 
						"Sadrzaj koji ste odabrali za uređivanje ne postoji.", 
						'upravljanjeSadrzajem/index');	
			}
		}	
	}
	
	private function ucitaj_paket ($id) {
		mkdir ("./scorms/paketi/".$id, 0777);
		$config['upload_path'] = "./scorms/paketi/".$id."/";
		$config['allowed_types'] = "zip";
		$config['max_size'] = "".(25*1024);
		
		$this->load->library ('upload', $config);
		
		if ($this->upload->do_upload('scormFile')) {
			return "/scorms/paketi/". $id . "/" . $this->upload->file_name;
		} else {
			throw new Exception($this->upload->display_errors (), $id);
		}
	}
	
	public function provjeri_unos () {
		$this->load->library ('form_validation');
		$this->form_validation->set_message ('required', "Polje %s je obavezno.");
		$this->form_validation->set_message ('min_length', "Sadrzaj polja %s je prekratak.");
		$this->form_validation->set_message ('max_length', "Sadrzaj polja %s je predug.");
		
		$this->form_validation->set_rules ('ime', "Ime", "required|trim|xss_clean|min_length[6]|max_length[25]");
		$this->form_validation->set_rules ('opis', "Opis", "required|trim|xss_clean|min_length[6]|max_length[2500]");
		$this->form_validation->set_rules ('kolegij', "Kolegij", "required|trim|xss_clean");
		$this->form_validation->set_rules ('navigacija', "Navigacija", "required|trim|xss_clean");
		return $this->form_validation->run();
	}	
}

?>