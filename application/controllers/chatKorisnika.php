<?php
session_start();
class ChatKorisnika extends CI_Controller {
	var $logiraniKorisnik;
	
	public function __construct () {
		parent::__construct();	
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		$this->load->model('korisnik');
		if (!$this->korisnik->je_logiran()) {
			redirect ('loginKorisnika/index');
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();
			if (!isset($_SESSION['povijest'])) {
				$_SESSION['povijest'] = array();	
			}
			
			if (!isset($_SESSION['otvoriProzore'])) {
				$_SESSION['otvoriProzore'] = array();	
			}
			$this->load->model('chat');
		}
	}
	
	public function zapocmi_chat () {
		$stavke ="";
		if (!empty ($_SESSION['otvoriProzore'])) {
			foreach ($_SESSION['otvoriProzore'] as $chatbox => $void) {
				$stavke .= $this->daj_bivse_sesije($chatbox);
			}	
		}
		
		if($stavke != '') { 
			$stavke = substr($stavke, 0, -1); 
		}
		
		header ('Content-type: application/json');
		echo '{';
		echo '"username" : "' . $this->logiraniKorisnik['ime'] . " " . $this->logiraniKorisnik['prezime'] . '", ';
		echo '"items" : [';
		echo $stavke;				 
		echo ']';
		echo '}';
		
	}
	
	private function daj_bivse_sesije ($chatbox) {
		$stavke = "";
		if (!empty ($_SESSION['povijest'][$chatbox])) {
			$stavke .= $_SESSION['povijest'][$chatbox];	
		}
		
		return $stavke;
	}
	
	public function posalji () {
			$za = $this->input->post('za');
			$poruka = $this->input->post ('poruka');
			$id = $this->logiraniKorisnik['id'];
			
			
			$_SESSION['otvoriProzore'][$za] = date("Y-m-d H:i:s", time());
			
			if(!isset($_SESSION['povijest'][$za])) {
				$_SESSION['povijest'][$za] = '';	
			}
			
			$korisnik = $this->korisnik->daj_korisnika($za);
			$puno_ime = $korisnik['ime'] . " " . $korisnik['prezime'];	
			$avatar = 	$korisnik['avatar']; 
			
			$_SESSION['povijest'][$za] .= "
				{
					\"s\": \"1\",
					\"f\": \"$za\", 
					\"n\": \"$puno_ime\",
					\"m\": \"$poruka\",
					\"i\": \"$avatar\"
				},";
			unset($_SESSION['tsChatProzori'][$za]);
			
			echo $this->chat->spasi_chat($za, $id, $poruka);
			exit();
	}
	
	public function provjeri_poruke () {
		$upit = $this->chat->daj_chatove_za($this->logiraniKorisnik['id']);
		$stavke = "";
		
		foreach ($upit as $vrijednost) {
			if(!isset($_SESSION['otvoriProzore'][$vrijednost['od']]) && isset($_SESSION['povijest'][$vrijednost['od']])) {
				$stavke = $_SESSION['povijest'][$vrijednost['od']];
			}
			
			$korisnik = $this->korisnik->daj_korisnika($vrijednost['od']);
			$puno_ime = $korisnik['ime'] . " " . $korisnik['prezime'];
			$avatar = 	$korisnik['avatar']; 
			
			$stavke .= "
				{
					\"s\": \"0\",
					\"f\": \"{$vrijednost['od']}\", 
					\"n\": \"$puno_ime\",
					\"m\": \"{$vrijednost['poruka']}\",
					\"i\": \"$avatar\"
				},";
				
			if(!isset($_SESSION['povijest'][$vrijednost['od']])) {
				$_SESSION['povijest'][$vrijednost['od']] = '';
			}
			
			$_SESSION['povijest'][$vrijednost['od']] = "
				{
					\"s\": \"0\",
					\"f\": \"{$vrijednost['od']}\", 
					\"n\": \"$puno_ime\",
					\"m\": \"{$vrijednost['poruka']}\",
					\"i\": \"$avatar\"
				},";
			unset($_SESSION['tsChatProzori'][$vrijednost['od']]);
			$_SESSION['otvoriProzore'][$vrijednost['od']] = $vrijednost['vrijeme'];
		}
		
		if (!empty ($_SESSION['otvoriProzore'])) {
			foreach ($_SESSION['otvoriProzore'] as $prozor => $vrijeme) {
				$sada = time () - strtotime($vrijeme);
				$formatirano_vrijeme = date ('Y-m-d G:i:s', strtotime($vrijeme));
				
				$od = $prozor;
				$korisnik = $this->korisnik->daj_korisnika($od);
				$puno_ime = $korisnik['ime'] . " " . $korisnik['prezime'];
				$avatar = $korisnik['avatar'];
				
				$poruka = "Poslano $formatirano_vrijeme";
				
				if($sada > 180) {
					$stavke .= "
					{
						\"s\": \"2\",
						\"f\": \"$od\", 
						\"n\": \"$puno_ime\",
						\"m\": \"$poruka\",
						\"i\": \"$avatar\"
					},";
					
					$_SESSION['povijest'][$od] = "
					{
						\"s\": \"2\",
						\"f\": \"$od\", 
						\"n\": \"$puno_ime\",
						\"m\": \"$poruka\",
						\"i\": \"$avatar\"
					},";
					$_SESSION['tsChatProzori'][$od] = 1;
				}
			}
		}
		
		$this->chat->azuriraj_status_chata($this->logiraniKorisnik['id']);
		
		if ($stavke != "") {
			$stavke = substr($stavke, 0, -1);	
		}
		header ('Content-type: application/json');
		echo '{';
		echo '"items" : [';
		echo $stavke;				 
		echo ']';
		echo '}';
		
	}
	
	public function zatvori_chat () {
		$index = $this->input->post('chatbox');
		
		unset ($_SESSION['otvoriProzore'][$index]);
		echo "1";
	}
	
	public function tko_je_online () {
		$vrijeme = date('c',time()-2*60);
		$this->db->where(array ('zadnja_aktivnost > ' => $vrijeme, 'id !=' => $this->logiraniKorisnik['id']));
		$upit = $this->db->get('korisnik');
		$polje_korisnika = array ();
		
		foreach ($upit->result() as $korisnik) {
			$korisnik = array (
				'id' => $korisnik->id,
				'avatar' =>  base_url('avatari/48x48/'.$korisnik->avatar),
				'avatar_img' => $korisnik->avatar,
				'ime' =>  $korisnik->ime,
				'prezime' =>  $korisnik->prezime,
				'puno_ime' => $korisnik->ime . " " . $korisnik->prezime,
				'status' => $korisnik->status
			);
			array_push ($polje_korisnika, $korisnik);
		}
		echo json_encode ($polje_korisnika);
	}
	
	public function postovi_status () {
		$status = $this->input->get('status');
		echo json_encode(array ('status' => $this->korisnik->azuriraj_status($this->logiraniKorisnik['id'], $status)));
	}	
}
?>