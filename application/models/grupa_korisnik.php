<?php 

class grupa_korisnik extends CI_Model {
	public function __construct () {
		parent::__construct ();	
	}
	
	public function pobrisi ($id) {
		$this->db->where ('id_grupe', $id);
		$this->db->delete ('grupa_korisnik');
		return $this->db->affected_rows();	
	}
	
	public function daj_korisnike_za_dodati ($id) {
			$result = $this->db->query ('
				SELECT ime, prezime, id 
				FROM korisnik 
				WHERE uloga != \'Administrator\' 
				AND id NOT IN (
					SELECT id_korisnika 
					FROM grupa_korisnik 
					WHERE id_grupe = \'' . intval($id) . '\')');
			return $result->result_array ();
	}
	
	public function daj_grupe_za_korisnika ($id) {
		$this->db->select('id_grupe');
		$this->db->where ('id_korisnika', $id);	
		$upit =$this->db->get ('grupa_korisnik');
		return $upit->result_array ();
	}
	
	public function daj_korisnike_za_grupu ($id) {
		$this->db->select('id_korisnika');
		$this->db->where ('id_grupe', $id);	
		$upit =$this->db->get ('grupa_korisnik');
		return $upit->result_array ();
	}
	
	public function daj_korisnike_za_brisati ($id) {
			$result = $this->db->query ('
				SELECT ime, prezime, id 
				FROM korisnik 
				WHERE uloga != \'Administrator\' 
				AND id IN (
					SELECT id_korisnika 
					FROM grupa_korisnik 
					WHERE id_grupe = \'' . intval($id) . '\')');
			return $result->result_array ();
	}
	
	public function brisi_korisnika ($id_korisnika, $id_grupe) {
		if ($this->provjeri_zapis($id_korisnika, $id_grupe)) {
			$this->db->where('id_korisnika', $id_korisnika);
			$this->db->where('id_grupe', $id_grupe);		
			$this->db->delete ('grupa_korisnik');
			return $this->db->affected_rows();
		}
	}
	
	public function dodaj_korisnika ($id_korisnika, $id_grupe) {
		if (!$this->provjeri_zapis($id_korisnika, $id_grupe)) {
			$grupa_korisnik = array (
				'id_korisnika' => $id_korisnika,
				'id_grupe' => $id_grupe
			);	
			$this->db->insert ('grupa_korisnik', $grupa_korisnik);
			return $this->db->insert_id();
		} else {
			return -1;	
		}
	}
	
	public function broj_korisnika ($id_grupe) {
		$this->db->where('id_grupe', $id_grupe);
		return $this->db->count_all_results('grupa_korisnik');
	}
	
	private function provjeri_zapis ($id_korisnika, $id_grupe){
		$this->db->where(array (
			'id_korisnika' => $id_korisnika,
			'id_grupe' => $id_grupe
		));
		return $this->db->count_all_results('grupa_korisnik') > 0 ? TRUE : FALSE ;	
	}
}
?>