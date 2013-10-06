<?php
ob_start();
class Profil extends CI_Controller {
	var $logiraniKorisnik;

	public function __construct () {
		parent::__construct();
		$this->load->model ('korisnik');
		$this->load->model ('forum');
		$this->load->model ('grupa_korisnik');
		$this->load->model ('grupa_kolegij');
		$this->load->model ('grupa_forum');
		$this->load->model ('grupa');
		$this->load->model ('post');
		$this->load->model ('tema');
		$this->load->model ('kolegij');
		$this->load->model ('pokusaj');
		$this->load->model ('scormvarijable');
		$this->load->model ('sadrzaj');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		$this->load->helper (array ('menu_helper', 'greske_helper', 'forum_helper', 'aktivnosti_helper', 'statistike_helper'));
			
		if (!$this->korisnik->je_logiran()) { 
			redirect ('loginKorisnika/index');
		} else {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();	
		}
	}
	
	public function index ($id = 0) {
		$podaci['logiraniKorisnik']= $this->logiraniKorisnik;
		$podaci['korisnik'] = $this->logiraniKorisnik;
		$podaci['lekcije'] = $this->daj_lekcije($this->logiraniKorisnik['id']);

		$this->load->view('profilKorisnika', $podaci);
	}
	
	public function postovi ($id_korisnika) {
		header ("Content-type:application/json");
		echo json_encode ($this->daj_postove($id_korisnika));	
	}
	
	public function forumi ($id_korisnika) {
		header ("Content-type:application/json");
		echo json_encode ($this->daj_forume($id_korisnika));	
	}
	
	public function teme ($id_korisnika) {
		header ("Content-type:application/json");
		echo json_encode ($this->daj_teme($id_korisnika));	
	}
	
	public function lekcije ($id_korisnika) {
		header ("Content-type:application/json");
		echo json_encode ($this->daj_lekcije ($id_korisnika));	
	}
	
	private function daj_forume ($id_korisnika) {
		$forumi = $this->forum->daj_forume_za_korisnika ($id_korisnika, 0, BROJ_STAVKI, "DESC");
		
		$aktivnosti = array ();
		$korisnik = $this->korisnik->daj_korisnika ($id_korisnika);
		foreach ($forumi as $forum) {
			$aktivnost = array (
				'ime_korisnika' => $korisnik['ime'],
				'url_korisnika' => site_url('profil/pogledaj/'.$korisnik['id']),
				'ime_foruma' => $forum['ime'],
				'id_foruma' => $forum['id'],
				'url_foruma' => urlencode(site_url('forumi/index/#forum'.$forum['id'])),
				'datum' => $forum['datum']);
			array_push ($aktivnosti, $aktivnost);
		}
		
		return $aktivnosti;		
	}
	
	private function daj_teme ($id_korisnika) {
		$teme = $this->tema->daj_za_korisnika ($id_korisnika, 0, BROJ_STAVKI, "DESC");
		
		$aktivnosti = array ();
		$korisnik = $this->korisnik->daj_korisnika ($id_korisnika);
		foreach ($teme as $tema) {
			$aktivnost = array (
				'ime_korisnika' => $korisnik['ime'],
				'url_korisnika' => site_url('profil/pogledaj/'.$korisnik['id']),
				'ime_teme' => $tema['ime'],
				'id_teme' => $tema['id'],
				'url_teme' => urlencode(site_url('teme/index/'.$tema['id_foruma'])),
				'datum' => $tema['datum']);
			array_push ($aktivnosti, $aktivnost);
		}
		
		return $aktivnosti;
	}
	
	private function daj_lekcije ($id_korisnika) {
		$pokusaji = $this->pokusaj->daj_za_korisnika ($id_korisnika, 0, BROJ_STAVKI, "DESC");
		$aktivnosti = array ();

		foreach ($pokusaji as $pokusaj) {
			$rezultat = $this->scormvarijable->daj_za_element($pokusaj['id'], 'cmi.core.score.raw');
			$status = $this->scormvarijable->daj_za_element($pokusaj['id'], 'cmi.core.lesson_status');
			$sadrzaj = $this->sadrzaj->daj ($pokusaj['id_sadrzaja']);
			
			$aktivnost = array (
				'korisnik' => $this->korisnik->daj_korisnika ($pokusaj['id_korisnika']),
				'rezultat' => isset($rezultat['vrijednost']) ? $rezultat['vrijednost'] : "0",
				'status' => isset($status['vrijednost']) ? daj_status($status['vrijednost']) : "",
				'lekcija' => $sadrzaj,
				'kolegij' => $this->kolegij->daj ($sadrzaj['id_kolegija']),
				'datum' => $pokusaj['datum']);
			array_push ($aktivnosti, $aktivnost);
		}
		
		return $aktivnosti;
	}
	
	private function daj_postove ($id_korisnika) {
		$postovi = $this->post->daj_postove_za_korisnika ($id_korisnika, 0, BROJ_STAVKI, "DESC");
		$aktivnosti = array ();
		$korisnik = $this->korisnik->daj_korisnika ($id_korisnika);

		foreach ($postovi as $post) {
			$tema = $this->tema->daj ($post['id_teme']);
			$aktivnost = array (
				'ime_korisnika' => $korisnik['ime'],
				'url_korisnika' => site_url('profil/pogledaj/'.$korisnik['id']),
				'ime_posta' => $post['ime'],
				'url_posta' => urlencode(site_url('postovi/index/'.$post['id_teme']."#post_wrapper".$post['id'])),
				'id_posta' => $post['id'],
				'ime_teme' => $tema['ime'],
				'id_teme' => $tema['id'],
				'url_teme' => urlencode(site_url('tema/index/'.$post['id_foruma'])),
				'datum' => $post['vrijeme']);
			array_push ($aktivnosti, $aktivnost);
		}
		
		return $aktivnosti;
	}
	
	public function pogledaj ($id) {
		if ($this->korisnik->postoji_korisnik ($id)) {
			$podaci['logiraniKorisnik']= $this->logiraniKorisnik;
			$podaci['korisnik'] = $this->korisnik->daj_korisnika($id);
			$podaci['lekcije'] = $this->daj_lekcije($id);
			if ($podaci['korisnik']['uloga'] != "Administrator" ) {
				$grupe_baza = $this->grupa_korisnik->daj_grupe_za_korisnika($podaci['korisnik']['id']);
				$grupe = array ();
				$kolegiji = array ();
				foreach ($grupe_baza as $grupa_baza) {
					$grupa_zapis = $this->grupa->daj_grupu($grupa_baza['id_grupe']);
					$grupa = array (
						'ime' => $grupa_zapis['ime']
					);	
					
					array_push ($grupe, $grupa);
					$kolegiji_baza = $this->grupa_kolegij->daj_kolegije_za_grupu($grupa_baza['id_grupe']);
					
					foreach ($kolegiji_baza as $kolegij_baza) {
						$kolegij_zapis = $this->kolegij->daj ($kolegij_baza['id_kolegija']);
						$kolegij = array (
							'ime' => $kolegij_zapis['ime'],
							'url' => site_url ('kolegiji/index/'.$kolegij_zapis['id'])
						);
						array_push ($kolegiji, $kolegij);
					}
				}
				
				$podaci['grupe'] = $grupe;
				$podaci['kolegiji'] = $kolegiji;
			}
	
			$this->load->view('profilKorisnika', $podaci);
		} else {
			prikazi_gresku (
						$this->logiraniKorisnik, 
						"Došlo je do pogreške taj korisnik ne postoji.", 
						'profil/index');
		}
	}
	
	private function daj_statistike_postova ($id_korisnika) {
		$podaci = $this->post->daj_za_korisnika_statistike($id_korisnika);	
		$statistike = array ();
		
		foreach ($podaci as $podatak) {
					$statistike[] = intval($podatak['broj_postova']);			
		}
		
		

		return $statistike;
	}
	
	private function daj_statistike ($id_korisnika) {
		$statistike = array ();
		$pokusaji = $this->pokusaj->daj_za_korisnika_grupirano ($id_korisnika, 0, BROJ_STAVKI, "DESC");
			
		foreach ($pokusaji as $pokusaj) {

			$status = $this->scormvarijable->daj_za_element($pokusaj['id'], 'cmi.core.lesson_status');
			$vrijeme = $this->scormvarijable->daj_za_element($pokusaj['id'], 'cmi.core.total_time');
				
			$sadrzaj = $this->sadrzaj->daj ($pokusaj['id_sadrzaja']);	
			
			$statistika = array (
				"id" => $pokusaj['id'],
				"lekcija" => $sadrzaj,
				"kolegij" => $this->kolegij->daj($sadrzaj['id_kolegija']),
				"broj_pokusaja" => $pokusaj['broj_pokusaja'],
				"vrijeme" => $vrijeme,
				"status" => $status,
				"rezultat" => daj_bodove_za_lekciju($sadrzaj['id'], $id_korisnika)				
			);
			array_push ($statistike, $statistika);
		}
		return $statistike;	
	}
	
	public function postovi_statistike ($id) {
		if ($this->korisnik->postoji_korisnik($id)) {
			$dani = array ();
			$kategorije = $this->post->daj_kategorije_statistika($id);
			foreach ($kategorije as $kategorija) {
				array_push ($dani, $kategorija['datum']);
			}
			$korisnik = $this->korisnik->daj_korisnika ($id);
			echo json_encode (
				array (
					'podaci' => array ('name' => $korisnik['puno_ime'] ,'data' => $this->daj_statistike_postova($id), 'categories' => $dani))
			);	
		}	
	}
	
	public function statistike ($id = 0) {
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if ($id == 0) {
			$podaci['korisnik'] = $this->logiraniKorisnik;
			$podaci['statistike'] = $this->daj_statistike($this->logiraniKorisnik['id']);
			$podaci['broj_postova'] = $this->post->prebroji_za_korisnika($this->logiraniKorisnik['id']);
			$this->load->view("statistikeKorisnka", $podaci);
		} else {
			if ($this->korisnik->postoji_korisnik($id)) {
				$podaci['korisnik'] = $this->korisnik->daj_korisnika ($id);
				$podaci['statistike'] = $this->daj_statistike($id);
				$podaci['broj_postova'] = $this->post->prebroji_za_korisnika($id);
				$this->load->view("statistikeKorisnka", $podaci);
			} else {
				prikazi_gresku (
						$this->logiraniKorisnik, 
						"Došlo je do pogreške taj korisnik ne postoji.", 
						'profil/index');
			}	
		}
	}
	
	public function statistike_pokusaja() {
		$id_sadrzaja = $this->input->get ('id_sadrzaja');
		$id_korisnika = $this->input->get ('id_korisnika');
		if ($this->sadrzaj->postoji ($id_sadrzaja) && $this->korisnik->postoji_korisnik ($id_korisnika)) {
			$sadrzaj = $this->sadrzaj->daj ($id_sadrzaja);
			$pokusaji = $this->pokusaj->daj_za($id_korisnika, $id_sadrzaja, 0, 0, "DESC");		
			$statistika_pokusaja = array ();
			foreach ($pokusaji as $pokusaj) {
				$statistika_pokusaj = array (
					'id' => $pokusaj['id'],
					'sadrzaj_ime' => $sadrzaj['ime'],
					'sadrzaj_id' => $sadrzaj['id'], 
					'vrijednosti' => $this->scormvarijable->daj_za_pokusaj($pokusaj['id'])
				);
				array_push ($statistika_pokusaja, $statistika_pokusaj);
			}
			echo json_encode ($statistika_pokusaja);
		} else {
			echo json_encode (array ("greska" => "Podatci koje ste prosljedili ne postoje u bazi podataka."));	
		}
	}
	
	public function graf_pokusaja () {
		$id_sadrzaja = $this->input->get ('id_sadrzaja');
		$id_korisnika = $this->input->get ('id_korisnika');
		if ($this->sadrzaj->postoji ($id_sadrzaja) && $this->korisnik->postoji_korisnik ($id_korisnika)) {
			$sadrzaj = $this->sadrzaj->daj ($id_sadrzaja);
			$korisnik = $this->korisnik->daj_korisnika ($id_korisnika);
			$pokusaji = $this->pokusaj->daj_za($id_korisnika, $id_sadrzaja, 0, 0, "ASC");
			$kategorije = array ();
			$ocjene = array ();
			
			foreach ($pokusaji as $pokusaj) {
				$ocjena = $this->scormvarijable->daj_za_element($pokusaj['id'], 'cmi.core.score.raw');
				array_push($kategorije, "Pokusaj ".$pokusaj['id']);
				array_push($ocjene, round($ocjena['vrijednost'], 1));
			}
			
			echo json_encode (
				array (	'podaci' => array ('name' => $sadrzaj['ime'] ,'data' => $ocjene), 
						'categories' => $kategorije, 
						'korisnik' => $korisnik['puno_ime'])
			);	
		} else {
			echo json_encode (array ("greska" => "Podatci koje ste prosljedili ne postoje u bazi podataka."));	
		}
	}
}
?>