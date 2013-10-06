<?php
class UpravljanjeProfilom extends CI_Controller {
	var $logiraniKorisnik;
	
	public function __construct() {
		parent::__construct();
		$this->load->model('korisnik');
		$this->load->helper (array ('form', 'menu_helper', 'greske_helper', 'dopustenja_helper'));
		$this->load->library('form_validation');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if (!$this->korisnik->je_logiran()) { 
			redirect ('loginKorisnika/index');
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();	
			if (!provjeri_dopustenja($this->logiraniKorisnik['uloga'], 'profil')) redirect ('profil/index');
		}
		
	}	
	
	public function index(){
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if (count($_POST) > 0) {
			$nova_lozinka = $this->input->post ('nova_lozinka');
			$stara_lozinka = $this->input->post ('stara_lozinka');
			
			if ($nova_lozinka != "" || $stara_lozinka != "") {
				$nova_lozinka = TRUE;	
			} else {
				$nova_lozinka = FALSE;
			}
			if ($this->provjeriUnos($nova_lozinka) === TRUE) {
				$ime = $this->input->post('ime');
				$prezime = $this->input->post('prezime');
				$email = $this->input->post('email');
				if ($nova_lozinka) {
					$lozinka = $this->input->post('nova_lozinka');
				} else {
					$lozinka = $this->logiraniKorisnik['lozinka'];	
				}
				$grad = $this->input->post('grad');
				$opis = $this->input->post('opis');
					
				if ($_FILES['avatar']['name'] != "") {
					$this->korisnik->pobrisi_avatar ($this->logiraniKorisnik['avatar']);
					$avatar =  $this->ucitajDatoteku();
				} else {
					$avatar = $this->logiraniKorisnik['avatar'];
				}
				
				$this->korisnik->uredi_korisnika ($this->logiraniKorisnik['id'], $ime, $prezime, $email, $lozinka, $this->logiraniKorisnik['uloga'], $grad, $avatar, $opis);
				if ($nova_lozinka == FALSE) {
				$podaci['uspjeh'] = TRUE;
				$podaci['korisnik'] = $this->korisnik->daj_korisnika($this->logiraniKorisnik['id']);
				$this->load->view ('urediProfil', $podaci);
				} else {
					redirect('loginKorisnika/index');	
				}
			} else {
				$podaci['greska'] = TRUE;
				$podaci['korisnik'] = $this->korisnik->daj_korisnika($this->logiraniKorisnik['id']);
				$this->load->view('urediProfil', $podaci);
			}
		} else {
			$podaci['korisnik'] = $this->logiraniKorisnik;
			$this->load->view('urediProfil', $podaci);
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
	
	private function provjeriUnos ($nova_lozinka = TRUE) {
			/*Postavke vlastitih poruka koje će se prikazati*/
			$this->form_validation->set_message ('required', 'Polje %s je obavezno');
			$this->form_validation->set_message ('min_length', 'Polje %s je prekratko');
			$this->form_validation->set_message ('max_length', 'Polje %s je predugo');
			$this->form_validation->set_message ('valid_email', 'Email adresa %s je pogresna');
			/*Postavke vlastitih poruka koje će se prikazati*/
			
			/*Postavke zahtjevanih polja*/
			$this->form_validation->set_rules ("ime", "Ime", "trim|required|xss_clean|min_length[3]|max_length[25]");
			$this->form_validation->set_rules ("prezime", "Prezime", "trim|required|xss_clean|min_length[3]|max_length[25]");
			$this->form_validation->set_rules ("email", "Email", "trim|required|valid_email|callback_provjeriMail");
			if ($nova_lozinka == TRUE) {
				$this->form_validation->set_rules ("stara_lozinka", "Stara lozinka", "trim|required|min_length[6]|md5|callback_provjeriLozinku");
				$this->form_validation->set_rules ("nova_lozinka", "Nova lozinka", "trim|required|min_length[6]|md5");
			}

			$this->form_validation->set_rules ("grad", "Grad", "trim|required|xss_clean|min_length[3]|max_length[25]");
			/*Postavke zahtjevanih polja*/
			
			/*Vrati dali su sva polja popunjena*/
			return $this->form_validation->run();
	}
	
	public function provjeriLozinku ($lozinka) {
		if ($this->korisnik->provjeri_login ($this->logiraniKorisnik['email'], $lozinka) < 0) {
			$this->form_validation->set_message	('provjeriLozinku', "Unesena stara lozinka nije ispravna.");
			return FALSE;
		}
		return TRUE;
	}
	
	public function provjeriMail ($email) {
		$korisnik = $this->korisnik->daj_korisnika ($this->logiraniKorisnik['id']);
		if ($korisnik['email'] === $email) return TRUE; 
		if ($this->korisnik->postoji_mail ($email)) {
			$this->form_validation->set_message	('provjeriMail', "Email adresa - " . $email . " je zauzeta molimo pokušajte sa drugom adresom.");
			return FALSE;
		}
		return TRUE;
	}
}
?>