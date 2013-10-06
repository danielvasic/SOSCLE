<?php 
class Kolegij extends CI_Model {
	
	public function daj ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get ('kolegij');
		return $upit->row_array ();
	}	
	
	public function daj_uz_limit ($limit, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->order_by ('id', $order);
		$this->db->limit ($offset, $limit);
		$upit = $this->db->get ('kolegij');
		return $upit->result_array ();	
	}
	
	public function daj_sve ( $order = "ASC") {
		$this->db->order_by ('id', $order);
		$upit = $this->db->get ('kolegij');
		return $upit->result_array();	
	}
	
	public function postoji ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get ('kolegij');
		return $upit->num_rows () > 0 ? TRUE : FALSE;	
	} 
	
	public function uredi ($id, $id_korisnika, $ime, $opis, $datum = "") {
		if ($datum == "") $datum = date ('Y-m-d G:i:s');
		$kolegij = array (
			"ime" => $ime,
			"opis" => $opis,
			"id_korisnika" => $id_korisnika,
			"datum" => $datum
		);	
		$this->db->where ('id', $id);
		$this->db->update ('kolegij', $kolegij);
		return $this->db->affected_rows ();
	}
	
	public function prebroji () {
		return $this->db->count_all('kolegij');	
	}
	
	public function spasi ($id_korisnika, $ime, $opis, $datum = "") {
		if ($datum == "") $datum = date ("Y-m-d G:i:s");
		$kolegij = array (
			"ime" => $ime,
			"opis" => $opis,
			"id_korisnika" => $id_korisnika,
			"datum" => $datum
		);
		$this->db->insert ('kolegij', $kolegij);
		return $this->db->insert_id ();
	}
	
	public function brisi ($id) {
		$this->db->where ('id', $id);
		$this->db->delete ('kolegij');
		return $this->db->affected_rows ();
	}
}
?>