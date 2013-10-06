<?php
class Pokusaj extends CI_Model {
	public function __construct () {
		parent::__construct();	
	}
	
	
	public function daj ($id = "", $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		if ($id != "")	$this->db->where ('id', $id);
		if (!($limit == 0 && $offset == 0)) $this->db->limit ($offset, $limit);
		$this->db->order_by ('datum', $order);
		$upit = $this->db->get('pokusaj');
		return $upit->result_array ();
	}
	
	public function daj_za ($id_korisnika, $id_sadrzaja, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->where ('id_korisnika', $id_korisnika);
		$this->db->where ('id_sadrzaja', $id_sadrzaja);
		if (!($limit == 0 && $offset == 0))$this->db->limit ($offset, $limit);
		$this->db->order_by ('id', $order);
		$upit = $this->db->get('pokusaj');
		return $upit->result_array ();
	}
	
	public function daj_za_korisnika_grupirano ($id_korisnika, $limit = 0, $offset = 0, $order = "ASC") {
		$upit = $this->db->query ('
			SELECT COUNT( * ) AS broj_pokusaja,  `id_korisnika` ,  `id_sadrzaja`, `id` 
			FROM (
			 `pokusaj`
			)
			WHERE  `id_korisnika` =  '. $id_korisnika .'
			GROUP BY id_sadrzaja
			ORDER BY  `id` '.$order.' 
		');
		return $upit->result_array ();
	}
	
	public function spasi ($id_korisnika, $id_sadrzaja) {
		$podaci = array (
			'id_korisnika' => $id_korisnika,
			'id_sadrzaja' => $id_sadrzaja,
		);	
		$this->db->set('datum', 'NOW()', FALSE); 
		$this->db->insert ('pokusaj', $podaci);
		return $this->db->insert_id();
	}
	
	public function azuriraj ($id, $id_korisnika, $id_sadrzaja) {
		$podaci = array (
			'id_korisnika' => $id_korisnika,
			'id_sadrzaja' => $id_sadrzaja,
			'datum' => date('Y-m-d H:i:s')
		);	
		$this->db->where ('id', $id);
		$this->db->update ('pokusaj', $podaci);
		return $this->db->insert_id();
	}
	
	public function daj_za_korisnika ($id_korisnika, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->where ('id_korisnika', $id_korisnika);
		if (!($limit == 0 && $offset == 0)) $this->db->limit ($offset, $limit);
		$this->db->order_by ('datum', $order);
		$upit = $this->db->get ('pokusaj');
		return $upit->result_array ();
	}
	
	public function postoji ($id) {
		$this->db->where('id', $id);
		return $this->db->count_all_results('pokusaj') > 0 ? TRUE : FALSE;
	}
}
?>