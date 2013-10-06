<?php
class chat extends CI_Model {
	public function __construct() {
		parent::__construct();	
	}	
	
	public function spasi_chat ($za, $od, $poruka) {
		$ubaci = array(
				"za" => $za,
				"od" => $od,
				"poruka" => $poruka,
				"vrijeme" => date("Y-m-d H:i:s", time()),
				"vidjeno" => "0"
		);
			
		$this->db->insert('chat', $ubaci);
		return $this->db->affected_rows();
	}
	
	public function daj_chatove_za ($za) {
		$this->db->where('za', $za);
		$this->db->where('vidjeno', '0');
		$this->db->order_by('vrijeme', 'desc');
		$query = $this->db->get('chat');
		return $query->result_array();
	}
	
	public function azuriraj_status_chata ($id) {
		$this->db->where ('za', $id);
		$this->db->where ('vidjeno', '0');
		$this->db->update ('chat', array ('vidjeno' => '1'));
	}
}
?>