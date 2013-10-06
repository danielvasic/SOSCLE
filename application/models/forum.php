<?php
class forum extends CI_Model {
	public function __construct () {
		parent::__construct ();	
	}	
	
	public function spasi ($id_korisnika, $ime, $opis, $status) {
			$podaci = array (
				'id_korisnika' => $id_korisnika,
				'datum' => date ('Y-m-d G:i:s'),
				'ime' => $ime,
				'opis' => $opis,
				'status' => $status
			);
			$this->db->insert ('forum', $podaci);
			return $this->db->insert_id();
	}
	
	public function daj ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get('forum');
		return $upit->row_array ();
	}
	
	public function daj_za_korisnika ($id_korisnika) {
		$this->db->where ('id_korisnika', $id_korisnika);
		$upit = $this->db->get('forum');
		return $upit->row_array ();
	}
	
	public function daj_forume_za_korisnika ($id_korisnika, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->where ('id_korisnika', $id_korisnika);
		if (!($limit == 0 && $offset == 0)) $this->db->limit ($offset, $limit);
		$this->db->order_by ('datum', $order);
		$upit = $this->db->get ('forum');
		return $upit->result_array ();
	}
	
	public function azuriraj ($id, $id_korisnika, $ime, $opis, $status) {
			$podaci = array (
				'id_korisnika' => $id_korisnika,
				'datum' => date ('Y-m-d G:i:s'),
				'ime' => $ime,
				'opis' => $opis,
				'status' => $status
			);
			$this->db->where('id', $id);
			$this->db->update ('forum', $podaci);
			return $this->db->affected_rows();		
	}
	
	
	
	public function pobrisi ($id) {
		$this->db->where ('id', $id);
		$this->db->delete('forum');
		return $this->db->affected_rows();	
	} 
	
	public function daj_forume ($limit, $offset, $order = "ASC"){
		$this->db->select ('korisnik.ime AS ime_korisnika, korisnik.prezime AS prezime, forum.opis AS opis_foruma, forum.ime AS ime_foruma, forum.id AS id_foruma, korisnik.id AS id_korisnika, forum.status');
		$this->db->from ('forum');
		$this->db->join ('korisnik', 'forum.id_korisnika = korisnik.id', 'inner');
		$this->db->limit ($offset, $limit);
		$this->db->order_by('forum.id', $order);
		$upit = $this->db->get ();
		return $upit->result_array();
	}
	
	public function daj_forume_za_id ($id_foruma, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC"){
		$this->db->select ('korisnik.ime AS ime_korisnika, korisnik.prezime AS prezime, forum.opis AS opis_foruma, forum.ime AS ime_foruma, forum.id AS id_foruma, korisnik.id AS id_korisnika, forum.status');
		$this->db->where_in ('forum.id', $id_foruma);
		$this->db->from ('forum');
		$this->db->join ('korisnik', 'forum.id_korisnika = korisnik.id', 'inner');
		$this->db->limit ($offset, $limit);
		$this->db->order_by('forum.id', $order);
		$upit = $this->db->get ();
		return $upit->result_array();
	}
	
	
	
	public function broj_foruma () {
		return $this->db->count_all('forum');	
	}
	
	public function postoji ($id) {
		$this->db->where('id', $id);
		$result = $this->db->get('forum');	
		return $result->num_rows() > 0 ? TRUE : FALSE;
	} 
	
	
}
?>