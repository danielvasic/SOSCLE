<?php
session_start();
class LoginKorisnika extends CI_Controller {
	public function __construct () {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('korisnik');
		$this->load->library('session');
		$this->load->library('encrypt');
		$this->load->helper('url');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
	}	
	
	public function index () {
		$podaci = array (
			'error' => FALSE,
			'potvrda' => FALSE
		);
		$id = $this->korisnik->je_logiran();
		if ($id) {
			redirect ('profil');
			$this->korisnik->azuriraj_status ($korisnik['id'], 'online');
			$this->korisnik->azuriraj_zadnju_aktivnost ($id);
		}
		if (count ($_POST)) {
			$podaci['error'] = TRUE;
			if ($this->provjeriPolja () === TRUE) {
				$podaci['error'] = FALSE;
				$email = $this->input->post ('email');
				$lozinka = $this->input->post ('lozinka');
				//echo $lozinka;
				$zapamti_me = $this->input->post('zapamti_me');
				$id = $this->korisnik->provjeri_login($email, $lozinka);
				if ($id > 0) {
					$korisnik = $this->korisnik->daj_korisnika ($id);
					$this->korisnik->azuriraj_status ($korisnik['id'], 'online');
					$this->korisnik->azuriraj_zadnju_aktivnost ($korisnik['id']);
					$sesijaKorisnik = array (
						PREFIX.'id' => $this->encrypt->encode ($korisnik['id']),
						PREFIX.'email' => $this->encrypt->encode ($korisnik['email']),
						PREFIX.'lozinka' => $this->encrypt->encode ($korisnik['lozinka'])
					);
					
					$this->session->set_userdata($sesijaKorisnik);
					if ($zapamti_me == "TRUE") {
						foreach ($sesijaKorisnik as $key => $value)
							$this->input->cookie($key, $value);
					}
					$podaci['success'] = TRUE;
					$this->load->view ('loginKorisnika', $podaci);
				} else {
					$podaci['loginError'] = TRUE;
					$this->load->view ('loginKorisnika', $podaci);
				}
			} else {
				$podaci['error'] = TRUE;
				$this->load->view ('loginKorisnika', $podaci);	
			}
		} else {
			
			$this->load->view ('loginKorisnika', $podaci);	
		}	
	}
	
	public function odlogiraj () {
		$this->load->helper('cookie');
		
		if($this->korisnik->je_logiran()) {
			$korisnik = $this->korisnik->daj_logiranog_korisnika();
		} else {
			die();
		}

		$this->korisnik->azuriraj_status ($korisnik['id'], 'offline');
		$session = array (
			PREFIX."id" => "",
			PREFIX."email" => "",
			PREFIX."lozinka" => "",
		);
		if($this->session->userdata(PREFIX.'id'))
		{
			$this->session->unset_userdata($session);
		}
		if($this->input->cookie('id'))
		{
			foreach($session as $key => $value)
				delete_cookie($key);
		}	
		redirect ('loginKorisnika/index');
	}
	
	public function vratiLozinku () {
		$email = $this->input->post('email');
		if ($email != "") {
			if ($this->korisnik->postoji_mail($email)) {
				$config = array(
					'protocol' => 'mail',
					'mailtype'  => $this->config->item('mailtype'), 
					'charset'   => $this->config->item('charset')
				);
					
				$this->load->library('email', $config);
				
				$lozinka = $this->generatorLozinke(rand(6, 15));
				try {
					$this->korisnik->azuriraj_lozinku ($email, $lozinka);
				} catch (Exception $e) {
					echo json_encode (array ("greska" => 1, "poruka" => $e->getMessage()));	
				}
				
				$this->email->to($email);
				$this->email->from($this->config->item('smtp_user'));
				$this->email->subject("Zahtjev za novom lozinkom");
				$this->email->message("<h3>Pozdrav!</h3><p>Primili smo zahtjev za novom lozinkom na sustavu SOSCLE, Vaša nova lozinka je " . $lozinka . "<br /> Sada se možete ponovo prijaviti na sustav <a href=\"" . site_url('loginKorisnika/index') . "\">ovdje</a> sa ovom email adresom.");


				$result = $this->email->send();

				if ($result) { 
					echo json_encode (array ("greska" => 0)); 
				} else {
					echo json_encode (array ("greska" => 1, "tekst" => "Sustav nije uspio poslati poruiku na email adresu."));

				} 	
			} else {
				echo json_encode (array ("greska" => 1));	
			}
		}
	}
	
	private function generatorLozinke ($duljina = 6) {
		return substr(md5(rand().rand()), 0, $duljina);
	}
	
	public function provjeriPolja (){
		$this->form_validation->set_message ('required', 'Polje %s je obavezno');
		$this->form_validation->set_rules ("email", "Email", "trim|xss_clean|required|valid_email");
		$this->form_validation->set_rules ("lozinka", "Lozinka", "trim|xss_clean|required|md5");
		return $this->form_validation->run();
	}
	
}
?>