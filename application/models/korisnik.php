<?php

class korisnik extends CI_Model {
	private $id;
	public function provjeri_login ($email, $lozinka) {
		$korisnicki_podaci = array ('email' => $email, 'lozinka' => $lozinka);		
		$this->db->where ($korisnicki_podaci);
		$query = $this->db->get('korisnik');
		if ($query->num_rows() > 0) {
			$red = $query->row_array ();
			return $red['id'];
		} else {
			return -1;
		}
	}
	
	public function daj_logiranog_korisnika () {
		return $this->daj_korisnika($this->id);	
	}
	
	public function je_logiran () {
		if ($this->session->userdata(PREFIX.'id')) {
			$id = $this->encrypt->decode ($this->session->userdata[PREFIX.'id']);
			$email = $this->encrypt->decode ($this->session->userdata[PREFIX.'email']);
			$lozinka = $this->encrypt->decode ($this->session->userdata[PREFIX.'lozinka']);
			
			$id = $this->provjeri_login ($email, $lozinka);
			if ($id > 0){ 
				$this->id = $id;
				$this->azuriraj_zadnju_aktivnost($id);
				return TRUE;
			} else {
				return FALSE;	
			}
		}
		
		if ($this->input->cookie(PREFIX.'id')) {
			$id = $this->encrypt->decode ($this->input->cookie[PREFIX.'id']);
			$email = $this->encrypt->decode ($this->input->cookie[PREFIX.'email']);
			$lozinka = $this->encrypt->decode ($this->input->cookie[PREFIX.'lozinka']);	
						
			$id = $this->provjeri_login ($email, $lozinka);
			if ($id > 0){ 
				$this->id = $id;
				$this->azuriraj_zadnju_aktivnost($id);
				return TRUE;
			} else {
				return FALSE;	
			}
		}
	}
	
	public function dodaj_korisnika ($ime, $prezime, $email, $lozinka, $uloga, $grad, $avatar = "", $opis = "") {
		$avatar = $this->provjeri_avatar($avatar, $uloga);
		$puno_ime = $ime . " " . $prezime;
		$spasi_korisnika = array (
			'ime' => $ime,
			'prezime' => $prezime,
			'puno_ime' => $puno_ime,
			'email' => $email,
			'lozinka' => $lozinka,
			'uloga' => $uloga,
			'grad' => $grad,
			'avatar' => $avatar,
			'opis' => $opis
		);
		$this->db->insert ('korisnik', $spasi_korisnika);
		return $this->db->insert_id();
	}
	
	public function postoji_mail ($email) {
		$this->db->where('email', $email);
		if ($this->db->count_all_results('korisnik') > 0) return TRUE;
		return FALSE;
	}
	
	public function azuriraj_lozinku ($email, $lozinka) {
		if ($this->postoji_mail($email)) {
			$spasi_korisnika = array (
				'lozinka' => md5($lozinka)						  
			);	
			$this->db->where ('email', $email);
			$this->db->update ('korisnik', $spasi_korisnika);
		} else {
			throw new Exception ("Email koji ste dali ne postoji.");	
		}
	}
	
	public function azuriraj_status ($id_korisnika, $status) {
		if ($status != "online" && $status != "offline" && $status != "zauzet") { $status = 'offline'; }
		if($this->postoji_korisnik($id_korisnika)) {
			$this->db->where ('id', $id_korisnika);
			$this->db->update ('korisnik', array ('status' => $status));
			return $status;
		}
	}
	
	public function azuriraj_zadnju_aktivnost ($id_korisnika) {
		if($this->postoji_korisnik($id_korisnika)) {
			$this->db->where ('id', $id_korisnika);
			$this->db->update ('korisnik', array ('zadnja_aktivnost' => date("Y-m-d H:i:s")));
		}
	}
	
	public function daj_korisnike ($pocetak, $limit, $order = "ASC") {
		$this->db->order_by('id', $order);
		$query = $this->db->get ('korisnik', $limit, $pocetak);
		return $query->result_array ();
	}
	
	public function daj_ucenike ($order = "ASC") {
		$this->db->where ('uloga', 'Ucenik');
		$this->db->order_by('id', $order);
		$query = $this->db->get ('korisnik');
		return $query->result_array ();
	}
	
	public function broj_korisnika () {
		$this->db->from('korisnik');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function brisi ($id) {
		$korisnik = $this->daj_korisnika($id);
		$this->pobrisi_avatar ($korisnik['avatar']);
		$this->db->delete('korisnik', array('id' => $id)); 	
	}
	
	public function postoji_korisnik ($id) {
		$this->db->from('korisnik');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? TRUE : FALSE;	
	}
	
	public function pretrazi ($vrijednost, $limit = 0, $offset = BROJ_STAVKI, $order = "DESC") {
			$this->db->like('ime', $vrijednost, 'both');
			$this->db->or_like('prezime', $vrijednost, 'both'); 
			$this->db->or_like('puno_ime', $vrijednost, 'both'); 	
			if (!($limit == 0 && $offset == 0)) $this->db->limit ($offset, $limit);
			$this->db->order_by ('id', $order);
			$upit = $this->db->get('korisnik');
			return $upit->result_array ();
	}
	public function daj_korisnika ($id) {
		$this->db->where ('id', $id);
		$query = $this->db->get ('korisnik');
		return $query->row_array();
	}
	
	public function uredi_korisnika ($id, $ime, $prezime, $email, $lozinka, $uloga, $grad, $avatar = "", $opis = ""){
		$avatar = $this->provjeri_avatar($avatar, $uloga);
		$puno_ime = $ime . " " . $prezime;
		$spasi_korisnika = array (
			'ime' => $ime,
			'prezime' => $prezime,
			'puno_ime' => $puno_ime,
			'email' => $email,
			'uloga' => $uloga,
			'grad' => $grad,
			'avatar' => $avatar,
			'opis' => $opis
		);
		
		if ($lozinka != "") $spasi_korisnika['lozinka'] = $lozinka;
		
		$this->db->where('id', $id);
		$this->db->update('korisnik', $spasi_korisnika);
		return $this->db->affected_rows();
	}
	
	public function pobrisi_avatar ($avatar) {
		$avatari = array ('administratorblank.png', 'studentblank.png', 'teacherblank.png');
		if (!in_array ($avatar, $avatari)) {
			unlink ('./avatari/48x48/'.$avatar);
			unlink ('./avatari/128x128/'.$avatar);
		}
	}
	
	private function provjeri_avatar ($avatar, $uloga) {
		$avatari = array ('administratorblank.png', 'studentblank.png', 'teacherblank.png');
		if ($avatar === "" || in_array ($avatar, $avatari)) {
			switch ($uloga) {
				case 'Ucenik':
					$avatar = "studentblank.png";
					break;
				case 'Ucitelj':
					$avatar = "teacherblank.png";
					break;	
				case 'Administrator':
					$avatar = "administratorblank.png";
					break;
			}	
		}
		return $avatar;
	}
	
	public function __construct () {
		parent::__construct();
		$this->load->library('session');
		$this->load->library("encrypt");
	}	
}
?>