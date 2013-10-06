<?php 
class Instalacija extends CI_Controller {
	public function __construct () {
		parent::__construct ();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library ('form_validation');
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "TRUE") {
			redirect ('loginKorisnika/index');
		}
	}	
	
	public function index () {
		if ($this->config->item('instalirana_baza') == "TRUE") {
			redirect ('instalacija/napraviKorisnika');
		} else {
			$this->load->view ('instalacijaKorak1');
		}
	}
	
	public function postaviBazu () {
		if (count($_POST) > 0) {
			if ($this->provjeriUnosBaza() === TRUE) {
				$hostname = $this->input->post('host');
				$password = $this->input->post('lozinka');
				$user = $this->input->post('korime');
				$database = $this->input->post('baza');
				
				
				$this->load->helper('file');
				$this->load->helper('string');

				$konekcija = FALSE;
				if ($dbh = @mysql_connect($hostname,$user,$password)) {
					if (@mysql_select_db($database, $dbh)) {
						$konekcija = TRUE;
					}
				}
				
				if ($konekcija === TRUE) {
				$database_file = read_file('./application/config/database.php');
				$database_file = preg_replace('/\$db\[\'default\'\]\[\'hostname\'\](.*?)\=(.*?)\"(.*?)\"/','$db[\'default\'][\'hostname\'] = "'.$hostname.'"',$database_file);
				$database_file = preg_replace('/\$db\[\'default\'\]\[\'username\'\](.*?)\=(.*?)\"(.*?)\"/','$db[\'default\'][\'username\'] = "'.$user.'"',$database_file);
				$database_file = preg_replace('/\$db\[\'default\'\]\[\'password\'\](.*?)\=(.*?)\"(.*?)\"/','$db[\'default\'][\'password\'] = \''.$password.'\'',$database_file);
				$database_file = preg_replace('/\$db\[\'default\'\]\[\'database\'\](.*?)\=(.*?)\"(.*?)\"/','$db[\'default\'][\'database\'] = "'.$database.'"',$database_file);
				
				write_file('./application/config/database.php',$database_file,'w');
				
				$autoload_file = read_file('./application/config/autoload.php');
				$autoload_file = preg_replace('/\$autoload\[\'libraries\'\](.*?)\=(.*?)array(.*?)\((.*?)\)/','$autoload[\'libraries\'] = array(\'database\')',$autoload_file);
				write_file('./application/config/autoload.php',$autoload_file,'w');
				$sql_file = file_get_contents("soscle.sql");
				
				if ($sql_file) {
					foreach (explode(";\n", $sql_file) as $sql) {
						$sql = trim($sql);
						@mysql_query($sql, $dbh);
					}
				}
				$this->spasi_u_config('instalirana_baza', 'TRUE');
				redirect('instalacija/napraviKorisnika');
				} else {
					$podaci['greska'] = "Nemogu se spojiti na bazu podataka molimo provjerite vaše podatke.";
					$this->load->view ('instalacijaKorak1', $podaci);
				}
			} else {
				$podaci['greska'] = validation_errors();
				$this->load->view ('instalacijaKorak1', $podaci);
			}
		}
	}
	
	public function napraviKorisnika () {
		if ($this->config->item('instalirana_baza') == "TRUE") {
				if ($this->config->item('instaliran_korisnik') == "TRUE") {
					redirect ('instalacija/postaviSmtp');
				} else {
					if (count ($_POST) > 0) {
						if ($this->provjeriUnosKorisnik()) {
							$this->load->database ();
							$this->load->model ('korisnik');
							
							$ime = $this->input->post ('ime');
							$prezime = $this->input->post ('prezime');
							$lozinka = $this->input->post ('lozinka');
							$email = $this->input->post ('email');
							$grad = $this->input->post ('grad');
							
							
							$this->korisnik->dodaj_korisnika ($ime, $prezime, $email, $lozinka, "Administrator", $grad, "" ,"");
							$this->spasi_u_config('instaliran_korisnik', 'TRUE');
							redirect ('instalacija/postaviSmtp');
						} else {
							$podaci['greska'] = validation_errors();
							$this->load->view ('instalacijaKorak2', $podaci);	
						}
					} else {
						$this->load->view ('instalacijaKorak2');	
					}
				}
		} else {
			redirect ('instalacija/index');
		}
	}
	
	public function postaviSmtp () {
		if ($this->config->item('instalirana_baza') == "TRUE") {
			if ($this->config->item('instaliran_korisnik') == "TRUE") {
				if (count($_POST)> 0) {
					if ($this->provjeriUnosSmtp()) {
						$port = $this->input->post('port');
						$host = $this->input->post('host');
						$ime = $this->input->post('ime');
						$lozinka = $this->input->post('lozinka');
						
						if ($port == '587') $host_prefix = "tls://";
						if ($port == '465') $host_prefix = "ssl://";
						
						$this->spasi_u_config('smtp_host', $host_prefix.$host);
						$this->spasi_u_config('smtp_user', $ime);
						$this->spasi_u_config('smtp_pass', $lozinka);
						$this->spasi_u_config('smtp_port', $port);
											
						$this->spasi_u_config('instalirano', "TRUE");
						$podaci['uspjeh'] = "Uspješno ste instalirali sustav na lokaciju: ". base_url() . " uskoro ćete biti preusmjereni.";
						
						$this->load->view('instalacijaKorak3', $podaci);
					} else {
						$podaci['greska'] = validation_errors();
						$this->load->view('instalacijaKorak3', $podaci);	
					}
				} else {
					$this->load->view('instalacijaKorak3');
				}
			} else {
				redirect ('instalacija/napraviKorisnika');
			}	
		} else {
			redirect ('instalacija/index');
		}
	}
	
	private function spasi_u_config ($ime, $vrijednost) {
		$this->load->helper('file');
		$config_file = read_file ('./application/config/instalacija.php');
		$config_file = preg_replace('/\$config\[\''.$ime.'\'\](.*?)\=(.*?)\"(.*?)\"/','$config[\''.$ime.'\'] = "'.$vrijednost.'"', $config_file);
		write_file('./application/config/instalacija.php',$config_file,'w');
	}
	
	private function provjeriUnosSmtp () {
		$this->form_validation->set_message ('required', 'Polje %s je obavezno.');
		$this->form_validation->set_message ('min_length', 'Polje %s je prekratko.');
		$this->form_validation->set_message ('max_length', 'Polje %s je predugo.');
		$this->form_validation->set_message ('valid_email', 'Email adresa nije ispravnog formata.');
		
		$this->form_validation->set_rules ('host', 'Host', 'required|xss_clean|trim|min_length[4]|max_length[25]');	
		$this->form_validation->set_rules ('ime', 'Korisničko ime', 'required|xss_clean|trim|min_length[4]|max_length[50]|valid_email');	
		$this->form_validation->set_rules ('lozinka', 'Lozinka', 'required|xss_clean|trim');
		$this->form_validation->set_rules ('port', 'Port', 'required|xss_clean|trim');
		return $this->form_validation->run();	
	}
	
	private function provjeriUnosKorisnik () {
		$this->form_validation->set_message ('required', 'Polje %s je obavezno.');
		$this->form_validation->set_message ('min_length', 'Polje %s je prekratko.');
		$this->form_validation->set_message ('max_length', 'Polje %s je predugo.');
		$this->form_validation->set_message ('valid_email', 'Email adresa nije ispravnog formata.');
		
		$this->form_validation->set_rules ('ime', 'Ime', 'required|xss_clean|trim|min_length[4]|max_length[25]');	
		$this->form_validation->set_rules ('prezime', 'Prezime', 'required|xss_clean|trim|min_length[4]|max_length[25]');	
		$this->form_validation->set_rules ('lozinka', 'Lozinka', 'md5|required');
		$this->form_validation->set_rules ('grad', 'Grad', 'required|min_length[2]|max_length[25]');	
		$this->form_validation->set_rules ('email', 'Email', 'required|xss_clean|trim|valid_email');
		return $this->form_validation->run();	
	}
	
	private function provjeriUnosBaza () {
		$this->form_validation->set_message ('required', 'Polje %s je obavezno.');
		$this->form_validation->set_message ('min_length', 'Polje %s je prekratko.');
		$this->form_validation->set_message ('max_length', 'Polje %s je predugo.');
		$this->form_validation->set_rules ('host', 'Ime poslužitelja', 'required|xss_clean|trim|min_length[4]|max_length[25]');	
		$this->form_validation->set_rules ('baza', 'Ime baze podataka', 'required|xss_clean|trim|min_length[4]|max_length[25]');	
		$this->form_validation->set_rules ('lozinka', 'Lozinka', 'xss_clean|trim');	
		$this->form_validation->set_rules ('korime', 'Korisničko ime', 'required|xss_clean|trim|min_length[4]|max_length[25]');
		return $this->form_validation->run();	
	}
}
?>