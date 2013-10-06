<?php 

class grupa_forum extends CI_Model {
	public function __construct () {
		parent::__construct ();
			
	}
	
	public function daj_forume ($id_grupe) {
		$upit = $this->db->query ('
			SELECT *
			FROM forum
			WHERE id IN (
				SELECT id_foruma
				FROM grupa_forum 
				WHERE id_grupe \'' . $id_grupe . '\'
			)
		');	
		return $upit->result_array ();
	}
	
	public function daj_grupe_za_forume ($id_grupa = array ()) {
		$this->db->select ('id_foruma');
		$this->db->where_in ('id_grupe', $id_grupa);
		$upit = $this->db->get ('grupa_forum');
		return $upit->result_array ();
	}	
	
	public function daj_forume_za_grupu ($id_grupe) {
		$this->db->select ('id_foruma');
		$this->db->where ('id_grupe', $id_grupe);
		$upit = $this->db->get ('grupa_forum');
		return $upit->result_array ();
	}
	
	public function daj_grupe_za_forum ($id_foruma) {
		$this->db->select ('id_grupe');
		$this->db->where ('id_foruma', $id_foruma);
		$upit = $this->db->get ('grupa_forum');
		return $upit->result_array ();
	}
	
	
	
	public function daj_grupe ($id_foruma) {
		$upit = $this->db->query ('
			SELECT *
			FROM grupa
			WHERE id IN (
				SELECT id_grupe
				FROM grupa_forum 
				WHERE id_foruma =\'' . $id_foruma . '\'
			)
		');	
		return $upit->result_array ();
	}
	
	public function pobrisi_zapis ($id_grupe, $id_foruma) {
		$this->db->where ('id_grupe', $id_grupe);
		$this->db->where ('id_foruma', $id_foruma);
		$this->db->delete ('grupa_forum');
		return $this->db->affected_rows();	
	}
	
	public function pobrisi_forum ($id) {
		$this->db->where ('id_foruma', $id);
		$this->db->delete ('grupa_forum');	
		return $this->db->affected_rows();	
	}
	
	public function dodaj_zapis ($id_foruma, $id_grupe) {
		if (!$this->postoji_zapis($id_foruma, $id_grupe)) {
			$podaci = array (
				'id_foruma' => $id_foruma,
				'id_grupe' => $id_grupe
			);	
			$this->db->insert ('grupa_forum', $podaci);
			return $this->db->affected_rows ();
		}
		return -1;
	}
	
	public function postoji_zapis ($id_foruma, $id_grupe) {
		$this->db->where ('id_foruma', $id_foruma);
		$this->db->where ('id_grupe', $id_grupe);
		return $this->db->get('grupa_forum')->num_rows() > 0 ? TRUE : FALSE; 	
	}
}
?>