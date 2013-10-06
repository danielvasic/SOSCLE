<?php
class Scormvarijable extends CI_Model {
	public function __construct () {
		parent::__construct ();	
	}	
	
	public function spasi ($id_pokusaja, $sco_id, $sco_title, $element, $vrijednost) {
		$podaci = array (
			'id_pokusaja' => $id_pokusaja,
			'sco_id' => $sco_id,
			'element' => $element,
			'vrijednost' => $vrijednost,
			'sco_title' => $sco_title
		);
		$this->db->insert ('scormvarijable', $podaci);
		return $this->db->insert_id();	
	}
	
	public function azuriraj ($id, $id_pokusaja, $sco_id, $sco_title, $element, $vrijednost) {
		$podaci = array (
			'id_pokusaja' => $id_pokusaja,
			'sco_id' => $sco_id,
			'element' => $element,
			'vrijednost' => $vrijednost,
			'sco_title' => $sco_title
		);
		$this->db->where('id', $id);
		$this->db->update ('scormvarijable', $podaci);
		return $this->db->affected_rows();	
	}
	
	public function azuriraj_element ($id_pokusaja, $sco_id, $element, $vrijednost) {
		$podaci = array (
			'vrijednost' => $vrijednost,
		);
		$this->db->where('id_pokusaja', $id_pokusaja);
		$this->db->where('element', $element);
		$this->db->where('sco_id', $sco_id);
		$this->db->update ('scormvarijable', $podaci);
		return $this->db->affected_rows();	
	}
	
	public function postoji_element ($id_pokusaja, $sco_id, $element) {
		$this->db->where('id_pokusaja', $id_pokusaja);
		$this->db->where('sco_id', $sco_id);
		$this->db->where('element', $element);
		$upit = $this->db->get('scormvarijable');
		$red = $upit->row_array ();
		if (isset($red['id']))
			return $red['id'];
		else
			return -1;
	}
	
	public function daj_za_sco_element ($id_pokusaja, $sco_id, $element) {
		$this->db->where('id_pokusaja', $id_pokusaja);
		$this->db->where('sco_id', $sco_id);
		$this->db->where('element', $element);
		$upit = $this->db->get('scormvarijable');
		return $upit->row_array ();
	}
	
	public function daj_za_element ($id_pokusaja, $element) {
		$this->db->where('id_pokusaja', $id_pokusaja);
		$this->db->where('element', $element);
		$upit = $this->db->get('scormvarijable');
		return $upit->row_array ();
	}
	
	public function daj ($sco_id, $id_pokusaja) {
		$this->db->where('sco_id', $sco_id);
		$this->db->where('id_pokusaja', $id_pokusaja);
		$upit = $this->db->get('scormvarijable');
		return $upit->result_array();
	} 
	
	public function daj_za_pokusaj ($id_pokusaja) {
		$this->db->where('id_pokusaja', $id_pokusaja);
		$upit = $this->db->get('scormvarijable');
		return $upit->result_array();
	}
	
	
	
	public function daj_za_id ($id) {
		$this->db->where('id', $id);
		$upit = $this->db->get('scormvarijable');
		return $upit->result_array ();	
	}
}
?>