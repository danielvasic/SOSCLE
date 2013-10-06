<?php
class sadrzaj extends CI_Model {
	public function spasi ($id_kolegija, $id_korisnika, $ime, $opis, $putanja, $navigacija="both") {
		$podaci = array (
			'id_korisnika' => intval ($id_korisnika),
			'id_kolegija' => intval ($id_kolegija),
			'ime' => $ime,
			'opis' => $opis,
			'putanja' => $putanja,
			'vrsta_navigacije' => $navigacija
		);	
		$this->db->insert('sadrzaj', $podaci);
		return $this->db->insert_id();
	}
	
	public function daj ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get('sadrzaj');	
		return $upit->row_array ();
	}
	
	public function daj_sa_kolegija ($id, $limit = -1, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->where ('id_kolegija', $id);
		if ($limit >= 0)$this->db->limit ($offset, $limit);
		$this->db->order_by ('id', $order);
		$upit = $this->db->get('sadrzaj');
		return $upit->result_array ();
	}

	
	public function prebroji_po_kolegiju($id) {
		$this->db->where ('id_kolegija', $id);
		return $this->db->count_all_results('sadrzaj');
	}
	
	public function azuriraj ($id, $id_kolegija, $id_korisnika, $ime, $opis, $putanja, $navigacija="both") {
		$this->db->where ('id', $id);
		$podaci = array (
			'id_korisnika' => intval ($id_korisnika),
			'id_kolegija' => intval ($id_kolegija),
			'ime' => $ime,
			'opis' => $opis,
			'putanja' => $putanja,
			'vrsta_navigacije' => $navigacija
		);	
		$this->db->update('sadrzaj', $podaci);
		return $this->db->affected_rows();
	}
	
	public function pobrisi ($id) {
		$this->db->where('id', $id);
		$this->db->delete('sadrzaj');
		return $this->db->affected_rows();	
	}
	
	public function daj_uz_limit ($limit, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->limit($offset, $limit);
		$this->db->order_by ('sadrzaj.id', $order);
		$upit = $this->db->get('sadrzaj');
		return $upit->result_array();	
	}
	
	public function daj_sve () {
		$upit = $this->db->get('sadrzaj');	
		return $upit->result_array();
	}
	
	public function prebroji ($id = 0) {
		if ($id == 0) {
			return $this->db->count_all('sadrzaj');
		} else {
			$this->db->where('id', $id);
			return $this->db->count_all_results();
		}
	}	
	
	public function postoji ($id) {
		$this->db->where('id', $id);
		return $this->db->count_all_results('sadrzaj') > 0 ? TRUE : FALSE;	
	}
}
?>