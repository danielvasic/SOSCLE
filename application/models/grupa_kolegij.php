<?php 
class Grupa_kolegij extends CI_Model {
	function daj_grupe_za_dodati ($id) {
		$sql = 
		   "SELECT * FROM grupa
			WHERE id NOT IN (
				SELECT id_grupe FROM grupa_kolegij
				WHERE id_kolegija = " . intval($id) . ")";
		$upit = $this->db->query ($sql);
		return $upit->result_array();	
	}
	
	function daj_grupe_za_brisati ($id) {
		$sql = 
		   "SELECT * FROM grupa
			WHERE id IN (
				SELECT id_grupe FROM grupa_kolegij
				WHERE id_kolegija = " . intval($id) . ")";
		$upit = $this->db->query ($sql);
		return $upit->result_array();	
	}
	
	public function dodaj_zapis ($id_grupe, $id_kolegija) {
		$zapis = array(
			'id_grupe' => intval($id_grupe),
			'id_kolegija' => intval($id_kolegija));
		$this->db->insert ('grupa_kolegij', $zapis);
		return $this->db->insert_id();	
	}
	
	public function pobrisi_grupu ($id_grupe) {
		$this->db->where ('id_grupe', intval($id_grupe));
		$this->db->delete ('grupa_kolegij');
		return $this->db->affected_rows();	
	}
	
	public function brisi_zapis ($id_grupe, $id_kolegija) {
		$this->db->where ('id_grupe', intval($id_grupe));
		$this->db->where ('id_kolegija', intval($id_kolegija));
		$this->db->delete ('grupa_kolegij');
		return $this->db->affected_rows();	
	}
	
	public function broj_grupa ($id) {
		$this->db->where('id_kolegija', $id);
		return $this->db->count_all_results('grupa_kolegij');	
	} 
	
	public function daj_grupe_za_kolegij ($id_kolegija) {
		$this->db->select('id_grupe');
		$this->db->where ('id_kolegija', $id_kolegija);
		$upit = $this->db->get('grupa_kolegij');
		return $upit->result_array ();		
	}
	
	public function daj_kolegije_za_grupu ($id_grupe) {
		$this->db->select('id_kolegija');
		$this->db->where ('id_grupe', $id_grupe);
		$upit = $this->db->get('grupa_kolegij');
		return $upit->result_array ();	
	}
}
?>