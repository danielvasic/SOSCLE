<?php
session_start();
class UpravljanjeKorisnicima extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct() {
		parent::__construct();
		$this->load->model('korisnik');
		$this->load->helper (array ('form', 'menu_helper', 'paginacija_helper', 'greske_helper', 'dopustenja_helper'));
		$this->load->library('form_validation');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if (!$this->korisnik->je_logiran()) { 
			redirect ('loginKorisnika/index');
			if (!provjeri_dopustenja($this->logiraniKorisnik['uloga'], 'korisnici')) redirect ('profil/index');
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();	
		}
		
	}
	
	public function index ($stranica = 0) {
		$podaci['korisnici'] = $this->korisnik->daj_korisnike(intval($stranica), BROJ_STAVKI, "DESC");
		$podaci['path'] = 'upravljanjeKorisnicima/index/';
		$podaci['brojac'] = $this->korisnik->broj_korisnika();
		$podaci['logiraniKorisnik'] =$this->logiraniKorisnik;		
		$this->load->view('administracijaKorisnika', $podaci);
	}
	
	public function uredi ($id) {
		$podaci = array ('greska' => FALSE, 'potvrda' => FALSE);
		$korisnik = $this->korisnik->daj_korisnika ($id);
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if(count($_POST) > 0) {
			$podaci['potvrda'] = TRUE;
			if ($this->provjeriUnos(TRUE) === TRUE) {
					$id = $this->input->post('id');
					$ime = $this->input->post('ime');
					$prezime = $this->input->post('prezime');
					$email = $this->input->post('email');
					$uloga = $this->input->post('uloga');
					$lozinka = $this->input->post('lozinka');
					$grad = $this->input->post('grad');
					$opis = $this->input->post('opis');
					
					if ($_FILES['avatar']['name'] != "") {
						$this->korisnik->pobrisi_avatar ($korisnik['avatar']);
						$avatar =  $this->ucitajDatoteku();
					} else {
						$avatar = $korisnik['avatar'];
					}
					
					$this->korisnik->uredi_korisnika ($id, $ime, $prezime, $email, $lozinka, $uloga, $grad, $avatar, $opis);
					$podaci['preusmjeri'] = TRUE;
					$podaci['greska'] = FALSE;
					$podaci['korisnik'] = $this->korisnik->daj_korisnika($id);
					$this->load->view ('urediKorisnika', $podaci);
			} else {
				$podaci['preusmjeri'] = TRUE;
				$podaci['greska'] = TRUE;
				$podaci['korisnik'] = $korisnik;	
				$this->load->view ('urediKorisnika', $podaci);
			}
		} else {
			if ($this->korisnik->postoji_korisnik ($id)) {
				$podaci['korisnik'] = $korisnik; 
				$podaci['greska'] = FALSE; 
				$podaci['potvrda'] = FALSE;
				$this->load->view ('urediKorisnika', $podaci);
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Korisnik čije podatke želite urediti ne postoji u našoj bazi podataka, biti ćete preusmjereni za 5 sekundi.", 
					'upravljanjeKorisnicima/index');
			}
		}
	}
	

	public function brisi ($idKorisnika) {
			$podaci['logiraniKorisnik'] =$this->logiraniKorisnik;	
			$id = intval ($idKorisnika);
			if ($this->korisnik->postoji_korisnik($id)) {
				$this->korisnik->brisi($id);
				redirect ('upravljanjeKorisnicima/index');
			} else {
				$podaci['error'] = TRUE;
				$podaci['tekst'] = "Korisnik kojeg ste odabrali ne postoji u našoj bazi podataka."; 
				$podaci['paginacija'] = $this->napraviPaginaciju ('upravljanjeKorisnicima/index/', $this->korisnik->broj_korisnika());
				$podaci['korisnici'] = $this->korisnik->daj_korisnike(0, BROJ_STAVKI);																																					
				$this->load->view ('administracijaKorisnika', $podaci);	
			}
	}
	
	public function dodaj () {
		$podaci = array ('greska' => FALSE, 'potvrda' => FALSE);
		$podaci['logiraniKorisnik'] =$this->logiraniKorisnik;	
			if (count($_POST) > 0) {
				$podaci['potvrda'] = TRUE;
				if ($this->provjeriUnos() === TRUE) {
					$ime = $this->input->post('ime');
					$prezime = $this->input->post('prezime');
					$email = $this->input->post('email');
					$uloga = $this->input->post('uloga');
					$lozinka = $this->input->post('lozinka');
					$grad = $this->input->post('grad');
					$opis = $this->input->post('opis');
					$avatar = $_FILES['avatar']['name'] != "" ? $this->ucitajDatoteku() : "";
					
					if ($this->korisnik->dodaj_korisnika($ime, $prezime, $email, $lozinka, $uloga, $grad, $avatar, $opis) > 0) {
						$podaci['preusmjeri'] = TRUE;
						$this->load->view ('dodajKorisnika', $podaci);	
					} else {
						$podaci['greska'] = TRUE;
						$this->load->view ('dodajKorisnika', $podaci);
					}
					
				} else {
					$podaci['greska'] = TRUE;
					$this->load->view ('dodajKorisnika', $podaci);
				}	
			} else {
				/*učitaj pogled ako korisnik još nije pokušao unjeti podatke*/
				$this->load->view ("dodajKorisnika", $podaci);
			}
	}
	
	private function ucitajDatoteku() {
		$config['upload_path'] = './avatari/128x128/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '5120';

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('avatar')) {
			$config['source_image'] = "./avatari/128x128/" . $this->upload->file_name;
			$config['maintain_ratio'] = true;
			$config['width'] = 128;
			$config['height'] = 128;
			$this->load->library('image_lib', $config);
			if ($this->image_lib->resize()) {
				$this->image_lib->clear();
				unset($config);
				$this->napraviSlicicu("./avatari/128x128/", $this->upload->file_name);
				$this->image_lib->clear();
				return $this->upload->file_name;
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Sustav nije uspio promjenit veličinu slike dogodila se sljedeća greška: " . $this->image_lib->display_errors(), 
					'upravljanjeKorisnicima/index');
				$this->load->view ('static/error', $podaci);
				
				
				return "";
			}
		} else {
			prikazi_gresku (
					$this->logiraniKorisnik, 
					"Sustav nije uspio učitati sliku dogodila se sljedeća greška: " . $this->upload->display_errors(), 
					'upravljanjeKorisnicima/index');
			return "";
		}
	}
	
	private function napraviSlicicu($putanja, $nazivSlike){
		
		/*server mora imati instaliranu biblioteku GD2 za rad sa slikama*/
		$config['image_library'] = 'GD2';
		$config['new_image'] = "./avatari/48x48/".$nazivSlike;
        $config['source_image'] = $putanja.$nazivSlike;
        $config['maintain_ratio'] = FALSE;
		$config['create_thumb'] = TRUE;
        $config['width'] = 48;
        $config['height'] = 48;

		/*Inicializacija konfiguracije*/
		$this->image_lib->initialize($config);
		
		/*Rezanje slike na određenu dimanziju*/
		if(!$this->image_lib->crop()) { 
			prikazi_gresku (
					$this->logiraniKorisnik, 
					"Sustav nije uspio napraviti sličicu dogodila se sljedeća greška: " . $this->image_lib->display_errors(), 
					'upravljanjeKorisnicima/index');
		}
    } 
	
	private function provjeriUnos ($uredi = FALSE) {
			/*Postavke vlastitih poruka koje će se prikazati*/
			$this->form_validation->set_message ('required', 'Polje %s je obavezno');
			$this->form_validation->set_message ('min_length', 'Polje %s je prekratko');
			$this->form_validation->set_message ('max_length', 'Polje %s je predugo');
			$this->form_validation->set_message ('valid_email', 'Email adresa koju ste unjeli je pogresna');
			/*Postavke vlastitih poruka koje će se prikazati*/
			
			/*Postavke zahtjevanih polja*/
			$this->form_validation->set_rules ("ime", "Ime", "trim|required|xss_clean|min_length[3]|max_length[25]");
			$this->form_validation->set_rules ("prezime", "Prezime", "trim|required|xss_clean|min_length[3]|max_length[25]");
			
			if ($uredi) {
				$this->form_validation->set_rules ("lozinka", "Lozinka", "trim|min_length[6]|md5");
				$this->form_validation->set_rules ("email", "Email", "trim|required|valid_email|callback_provjeriMailUredi");
			} else {
				$this->form_validation->set_rules ("email", "Email", "trim|required|valid_email|callback_provjeriMail");
				$this->form_validation->set_rules ("lozinka", "Lozinka", "trim|required|min_length[6]|md5");
			}
			$this->form_validation->set_rules ("uloga", "Uloga", "trim|required|xss_clean");
			$this->form_validation->set_rules ("grad", "Grad", "trim|required|xss_clean|min_length[3]|max_length[25]");
			/*Postavke zahtjevanih polja*/
			
			/*Vrati dali su sva polja popunjena*/
			return $this->form_validation->run();
	}
	
	public function provjeriMail ($email) {
		if ($this->korisnik->postoji_mail ($email)) {
			$this->form_validation->set_message	('provjeriMail', "Email adresa - " . $email . " je zauzeta molimo pokušajte sa drugom adresom.");
			return FALSE;
		}
		return TRUE;
	}
	
	public function provjeriMailUredi ($email) {
		$korisnik = $this->korisnik->daj_korisnika ($this->input->post('id'));
		if ($korisnik['email'] === $email) return TRUE; 
		if ($this->korisnik->postoji_mail ($email)) {
			$this->form_validation->set_message	('provjeriMail', "Email adresa - " . $email . " je zauzeta molimo pokušajte sa drugom adresom.");
			return FALSE;
		}
		return TRUE;
	}
}

?>