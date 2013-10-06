<?php 
class Grupa extends CI_Model {
	public function __construct () {
		parent::__construct ();	
	}	
	
	public function daj_grupu ($id) {
		$id = intval($id);
		if ($this->postoji_grupa($id)) {
			$this->db->where ('id', $id);
			$query = $this->db->get('grupa');
			return $query->row_array ();
		}
	}

	public function spasi ($ime, $opis) {
		$grupa = array ("ime" => $ime, "opis" => $opis, "vrijeme" => date("Y-m-d G:i:s"));
		$this->db->insert('grupa', $grupa);
		return $this->db->affected_rows();
	}
	
	public function pobrisi ($id) {
		if ($this->postoji_grupa($id)) {
			$this->db->where ('id', $id);
			$this->db->delete('grupa');
			return $this->db->affected_rows();	
		}
	}
	
	public function azuriraj ($id, $ime, $opis) {
		if ($this->postoji_grupa($id)) {
			$this->db->where('id', $id);
			$podaci = array (
				'ime' => $ime,
				'opis' => $opis);
			$this->db->update ('grupa', $podaci);
			return $this->db->affected_rows();	
		}
	}
	
	public function daj_grupe ($limit, $offset, $order = "DESC") {
		$this->db->select ('COUNT(id_grupe) as broj_korisnika, grupa.id AS id, ime, vrijeme, opis');
		$this->db->from ('grupa');
		$this->db->join('grupa_korisnik', 'grupa.id = grupa_korisnik.id_grupe', 'left');
		$this->db->group_by ('id');
		$this->db->limit ($offset, $limit);
		$this->db->order_by('id', $order);
		$query = $this->db->get();
		return $query->result_array ();
	}
	
	public function daj_sve_grupe () {
		$upit = $this->db->get('grupa');
		return $upit->result_array ();	
	}
	
	public function postoji_grupa ($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('grupa');
		return $query->num_rows() > 0 ? TRUE : FALSE;
	}
	
	public function broj_grupa () {
		return $this->db->count_all('grupa');
	}
}
?>