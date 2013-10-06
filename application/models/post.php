<?php

class Post extends CI_Model {
	public function __construct () {
		parent::__construct ();	
	}
	
	public function daj_postove_za_temu ($id_teme, $limit, $offset = BROJ_STAVKI, $order = "DESC") {
		$this->db->select (
			'post.id AS id_posta, post.id_roditelja AS id_roditelja, korisnik.id AS id_korisnika, forum.id AS id_foruma, post.ime AS ime_posta, post.sadrzaj AS sadrzaj_posta, post.vrijeme AS vrijeme_posta'
		);
		$this->db->from ('post');
		$this->db->join ('tema', 'post.id_teme = tema.id', 'left');
		$this->db->join ('korisnik', 'post.id_korisnika = korisnik.id', 'left');
		$this->db->join ('forum', 'post.id_foruma = forum.id', 'left');
		
		$this->db->where ('id_teme' , $id_teme);
		$this->db->where ('id_roditelja', 0);
		$this->db->limit ($offset, $limit);
		$this->db->order_by ('post.id', $order);
		$upit = $this->db->get ();
		
		return $upit->result_array ();
			
	}
	
	public function prebroji_broj_odgovora ($id) {
		$this->db->where('id_roditelja', $id);
		return $this->db->count_all_results('post');	
	}
	
	public function daj ($id) {
		$this->db->where('id', $id);
		$upit = $this->db->get('post');
		return $upit->row_array();	
	}
	
	public function daj_postove_za_korisnika ($id_korisnika, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->where('id_korisnika', $id_korisnika);
		if (!($limit == 0 && $offset == 0))$this->db->limit ($offset, $limit);
		$this->db->order_by ('post.vrijeme', $order);
		$upit = $this->db->get('post');
		return $upit->result_array ();
	}
	
	public function daj_najnovije ($limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->from ('post');
		if (!($limit == 0 && $offset == 0))$this->db->limit ($offset, $limit);
		$this->db->order_by ('post.vrijeme', $order);
		$upit = $this->db->get ();
		return $upit->result_array ();
	}
	
	public function daj_postove_za_forum ($id_foruma) {
		$this->db->where('id_foruma', $id_foruma);
		$upit = $this->db->get('post');
		return $upit->result_array ();
	}
	
	public function daj_postove_vece_od($id, $id_teme, $limit = 0, $offset = BROJ_STAVKI, $order = "ASC") {
		$this->db->from ('post');
		$this->db->where ('id_teme' , $id_teme);
		$this->db->where ('id >', $id);
		$this->db->limit ($offset, $limit);
		$this->db->order_by ('post.id', $order);
		$upit = $this->db->get ();
		return $upit->result_array ();	
	}
	
	public function daj_za_temu ($id) {
		$this->db->where ('id_teme', $id);
		$upit = $this->db->get('post');
		return $upit->result_array();	
	} 
	
	public function daj_odgovore ($id_teme, $id_posta) {
		$this->db->where ('id_teme', $id_teme);
		$this->db->where ('id_roditelja', $id_posta);
		$upit = $this->db->get ('post');	
		return $upit->result_array ();
	}
	
	public function pobrisi ($id) {
		$this->db->where('id', $id);
		$this->db->delete('post');
		return $this->db->affected_rows();
	}
	
	public function postoji ($id) {
		$this->db->where ('id', $id);
		$upit = $this->db->get ('post');
		return $upit->num_rows () > 0 ? TRUE : FALSE;	
	} 
	
	public function spasi ($id_korisnika, $id_teme, $id_foruma, $ime, $sadrzaj, $datum = "", $roditelj = 0) {
		if ($datum == "") $datum = date('Y-m-d G:i:s');
		$podaci = array (
			'id_korisnika' => $id_korisnika,
			'id_teme' => $id_teme,
			'id_foruma' => $id_foruma,
			'id_roditelja' => $roditelj,
			'ime' => $ime,
			'sadrzaj' => $sadrzaj,
			'vrijeme' => $datum
		);
		$this->db->insert('post', $podaci);
		return $this->db->insert_id();
	}
	
	public function azuriraj ($id, $id_korisnika, $id_teme, $id_foruma, $ime, $sadrzaj, $datum = "", $roditelj = 0) {
		if ($datum == "") $datum = date('Y-m-d G:i:s');
		$podaci = array (
			'id_korisnika' => $id_korisnika,
			'id_teme' => $id_teme,
			'id_foruma' => $id_foruma,
			'id_roditelja' => $roditelj,
			'ime' => $ime,
			'sadrzaj' => $sadrzaj,
			'vrijeme' => $datum
		);
		$this->db->where ('id', $id);
		$this->db->update('post', $podaci);
		return $this->db->affected_rows();
	}
	
	public function prebroji_za_korisnika ($id) {
		$this->db->where ('id_korisnika', $id);
		return $this->db->count_all_results('post');	
	}
	
	public function daj_za_korisnika_statistike ($id) {
		$upit = $this->db->query('
			SELECT COUNT(*) AS broj_postova, DATE(vrijeme) as datum, vrijeme AS dan 
			FROM post 
			WHERE id_korisnika=\''.intval($id).'\' 
			GROUP BY datum
			ORDER BY datum ASC
		');
		return $upit->result_array ();
	}

	public function daj_kategorije_statistika ($id) {
		$upit = $this->db->query('
			SELECT DISTINCT(DATE(vrijeme)) AS datum
			FROM post 
			WHERE id_korisnika=\''.intval($id).'\' 
			ORDER BY datum ASC
		');
		return $upit->result_array ();
	}
	
}

?>