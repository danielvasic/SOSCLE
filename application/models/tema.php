<?php
class Tema extends CI_Model {
	public function __construct () {
		parent::__construct ();	
	}	
	
	public function daj ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get ('tema');
		return $upit->row_array();	
	}
	
	
	
	public function daj_za_forum ($id, $limit = 0, $offset = 0, $order = "DESC") {
		$this->db->where ('id_foruma', $id);
		$this->db->order_by ('id', $order);
		if (!($limit == 0 && $offset == 0)) $this->db->limit ($limit, $offset);
		$upit = $this->db->get ('tema');
		return $upit->result_array();	
	}
	
	public function daj_za_korisnika ($id_korisnika, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->where ('id_korisnika', $id_korisnika);
		if (!($limit == 0 && $offset == 0)) $this->db->limit ($offset, $limit);
		$this->db->order_by ('datum', $order);
		$upit = $this->db->get ('tema');
		return $upit->result_array ();
	}
	
	public function spasi ($id_foruma, $id_korisnika, $ime, $opis, $status = "otkljucan") {
		$podaci = array (
			'id_foruma' => $id_foruma,
			'id_korisnika' => $id_korisnika,
			'ime' => $ime, 
			'opis' => $opis,
			'datum' => date ('Y-m-d G:i:s'),
			'status' => $status
		);	
		$this->db->insert ('tema', $podaci);
		return $this->db->insert_id ();
	}
	
	public function azuriraj ($id_teme, $id_foruma, $id_korisnika, $ime, $opis, $status = "otkljucan") {
		$this->db->where('id', $id_teme);
		$podaci = array (
			'id_foruma' => $id_foruma,
			'id_korisnika' => $id_korisnika,
			'ime' => $ime, 
			'opis' => $opis,
			'datum' => date ('Y-m-d G:i:s'),
			'status' => $status
		);	
		$this->db->update ('tema', $podaci);
		return $this->db->affected_rows ();		
	}
	
	public function pobrisi ($id) {
		$this->db->where ('id', $id);
		$this->db->delete ('tema');
		return $this->db->affected_rows ();	
	}
	
	public function postoji ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get ('tema');
		return $upit->num_rows () > 0 ? TRUE : FALSE;	
	}
	
	public function prebroji () {
		return $this->db->count_all('tema');
	}
	
	public function po_forumima_prebroji ($id) {
		$this->db->where ('id_foruma', $id);
		return $this->db->count_all_results('tema');
	}
	
	public function po_korisnicima_prebroji ($id) {
		$this->db->where ('id_korisnika', $id);
		return $this->db->count_results('tema');
	}
}
?>