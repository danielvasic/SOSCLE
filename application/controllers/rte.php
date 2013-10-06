<?php
class Rte extends CI_Controller {
	var $logiraniKorisnik;
	public function __construct () {
		parent::__construct();
		
		$this->load->helper(array ('paginacija_helper', 'menu_helper', 'greske_helper', 'forum_helper', 'grupe_helper', 'sco_helper', 'array_helper'));
		$this->load->library ('imsmanifest.php');
		$this->load->model('sadrzaj');
		$this->load->model('kolegij');
		$this->load->model('korisnik');
		$this->load->model('scormvarijable');
		$this->load->model('pokusaj');
		$this->load->model('grupa_kolegij');
		$this->load->model('grupa_korisnik');
		
		$this->config->load('instalacija');
		if ($this->config->item('instalirano') == "FALSE") {
			redirect ('instalacija/index');
		}
		
		if ($this->korisnik->je_logiran()) {
			$this->logiraniKorisnik = $this->korisnik->daj_logiranog_korisnika();
		} else {
			redirect ('loginKorisnika/index');	
		}
	}
	
	
	
	public function index ($id) {
		$podaci['logiraniKorisnik'] = $this->logiraniKorisnik;
		if ($this->sadrzaj->postoji ($id)) {
			$podaci['sadrzaj'] = $this->sadrzaj->daj($id);
			if ($this->provjeri_grupe($podaci['sadrzaj']['id_kolegija'])) {
			$podaci['kolegij'] = $this->kolegij->daj($podaci['sadrzaj']['id_kolegija']);
			$novi_pokusaj = $this->input->get ('novi_pokusaj');
			
			if (isset($novi_pokusaj) && $novi_pokusaj == true) {
				$podaci['id_pokusaja'] = $this->pokusaj->spasi ($this->logiraniKorisnik['id'], $id);
			} else {
				$zadnji_pokusaj = $this->pokusaj->daj_za ($this->logiraniKorisnik['id'], $id, 0, 1, "DESC");
				if (count($zadnji_pokusaj) > 0) {
					$podaci['id_pokusaja'] = $zadnji_pokusaj[0]['id'];
				} else {
					$podaci['id_pokusaja'] = $this->pokusaj->spasi ($this->logiraniKorisnik['id'], $id);
				}	
			}
			
			$manifest = new Imsmanifest();
			$manifest->init ('scorms/paketi/'.$id.'/imsmanifest.xml');
			$pageURL = 'http';
			if (isset($_SERVER["HTTPS"])) {$pageURL .= "s";}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"];
			}
			$podaci ['path'] = $pageURL.'/soscle/scorms/paketi/'.$id;
			$podaci ['resources'] = $manifest->getResource();
			$org = $manifest->getOrganization();
			$default = $manifest->getDefaultOrganization();
			
			array_push ($org, $default);
			$podaci ['organizations'] = array_unique($org);
			$podaci ['iframe'] = $podaci ['path']."/".getDefault ($default->getItems(), $podaci ['resources']);
			
			/**
			*
			* SCO Object { title : '', masteryscore : '', identifier : '', href : '' }
			*
			*/
			
			$scos = array ();
			$i = 0;
		
			foreach ($manifest->getOrganization() as $organization) {
				if ($organization->getIdentifier() == $manifest->getDefaultOrganizationIdentifier()) {
					$default = $i;	
				}
				$items = array_flatten($organization->getItems());

				foreach ($items as $item) {
					$resource = $manifest->getresource ($item->getIdentifierref ());
					array_push ($scos, array (
							'title' => $item->getTitle (),
							'masteryscore' => $item->getMasteryscore(),
							'identifier' => $item->getIdentifier(),
							'href' => $podaci ['path'] . "/" . $resource->getHref()
						)
					);
				}
				$i++;
			}
			
			$podaci['scos'] = json_encode ($scos);
			$podaci['defaultScoIndex'] = $default;
			
			
			$this->load->view("rte", $podaci);	
			
			} else {
				prikazi_gresku (
					$this->logiraniKorisnik, 
					"Nemate pristup sadržaju pokušajte sa drugim sadrzajem.", 
					'profil/index');	
			}
		} else {
			prikazi_gresku (
				$this->logiraniKorisnik, 
				"Došlo je do pogreške taj sadržaj ne postoji.", 
				'profil/index');	
		}	
	}
	
	private function provjeri_grupe ($id_kolegija) {
		if($this->logiraniKorisnik['uloga'] == "Administrator") return TRUE;
		$grupe_kolegiji = $this->grupa_kolegij->daj_grupe_za_kolegij($id_kolegija);
		$grupe_korisnici = $this->grupa_korisnik->daj_grupe_za_korisnika ($this->logiraniKorisnik['id']);
		foreach ($grupe_kolegiji as $grupa_kolegij) {
			foreach ($grupe_korisnici as $grupa_korisnik) {
				if ($grupa_korisnik['id_grupe'] === $grupa_kolegij['id_grupe']) return TRUE;	
			}
		}
		return FALSE;
	}
	
	public function inicializiraj_sesiju ($id) {
		if ($this->pokusaj->postoji ($id)) {
			$sco_id = urldecode($this->input->get ('sco_id'));
			$standard_datamodel = $this->daj_model_podataka($id, $sco_id);
			header ("Content-Type: application/json;charset=ISO-8859-1");
			echo json_encode ($standard_datamodel);
		} else {
			header ("Content-Type: application/json;charset=ISO-8859-1");
			echo json_encode (array ("greska" => 1, "tekst" => "Pokusaj ne postoji!"));
		}
	}
	
	public function daj_model_podataka ($id, $sco_id) {
			$standard_datamodel = array (
					array ('element' => 'cmi.core._children', 'value' => 'student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,exit,session_time'),
					array ('element' => 'cmi.core.student_name', 'value' => $this->logiraniKorisnik['prezime'] . ", " . $this->logiraniKorisnik['ime']),
					array ('element' => 'cmi.core.student_id', 'value' => $this->logiraniKorisnik['id']),
					array ('element' => 'cmi.core.lesson_status', 'value' => 'not attempted'),
					array ('element' => 'cmi.core.lesson_location', 'value' => ''),
					array ('element' => 'cmi.core.credit', 'value' => ''),
					array ('element' => 'cmi.core.entry', 'value' => ''),
					array ('element' => 'cmi.core.score', 'value' => '0'),
					array ('element' => 'cmi.core.score.raw', 'value' => '0'),
					array ('element' => 'cmi.core.score.min', 'value' => '0'),
					array ('element' => 'cmi.core.score.max', 'value' => '0'),
					array ('element' => 'cmi.core.total_time', 'value' => '00:00:0000'),
					array ('element' => 'cmi.core.session_time', 'value' => ''),
					array ('element' => 'cmi.core.exit', 'value' => '')
			);
			
			$scormvarijable = $this->scormvarijable->daj ($sco_id, $id);
	
			if (count($scormvarijable) > 0) {
				
				foreach ($scormvarijable as $scormvarijabla) {
						$in_array = false;
						for ($i=0; $i<count($standard_datamodel);$i++) {
							if ($scormvarijabla['element'] == $standard_datamodel[$i]['element']) {
								$standard_datamodel[$i]['value'] = $scormvarijabla['vrijednost'];	
								$in_array = true;
								break;
							}
						}
						if (!$in_array) {
							$datamodel =  array ('element' => $scormvarijabla['element'], 'value' => $scormvarijabla['vrijednost']);
							array_push ($standard_datamodel, $datamodel);	
						}
				}
			}
			return $standard_datamodel;	
	}
	
	public function spasi_sesiju ($id) {
		if ($this->pokusaj->postoji ($id)) {
			$sco_id = urldecode($this->input->get('sco_id'));
			$sco_title = urldecode($this->input->get('sco_title'));
			
			$data = $this->input->get('data');
			foreach ($data as $element => $value) {
				$id_varijable = $this->scormvarijable->postoji_element($id, $sco_id, $element);
				if ($id_varijable > 0) {
					$this->scormvarijable->azuriraj ($id_varijable, $id, $sco_id, $sco_title, urldecode($element), urldecode($value));
				} else {
					$this->scormvarijable->spasi ($id, $sco_id, $sco_title, urldecode($element), urldecode($value));
				}
			}
			header ("Content-Type: application/json;charset=ISO-8859-1");
			echo json_encode(array ("result" => "true"));
		} else {
			header ("Content-Type: application/json;charset=ISO-8859-1");
			echo json_encode (array ("greska" => 1, "tekst" => "Pokusaj ne postoji!"));
		}
	}
	
	public function zavrsi_sesiju ($id) {
		if ($this->pokusaj->postoji ($id)) {
			$sco_id = urldecode($this->input->get ('sco_id'));
			$mastery_score = urldecode($this->input->get ('masteryscore'))*1;
			
			if (isset ($mastery_score)) {
				$rezultat = $this->scormvarijable->daj_za_sco_element($id, $sco_id, 'cmi.core.score.raw');
				$rezultat = $rezultat['vrijednost']*1;	
				if ($rezultat >= $mastery_score) {
					$this->scormvarijable->azuriraj_element ($id, $sco_id, 'cmi.core.lesson_status', 'passed');	
				} else {
					$this->scormvarijable->azuriraj_element ($id, $sco_id, 'cmi.core.lesson_status', 'failed');	
				}
			}
			
			$exit = $this->scormvarijable->daj_za_sco_element ($id, $sco_id, 'cmi.core.exit');
			$this->scormvarijable->azuriraj_element($id, $sco_id, 'cmi.core.entry', '');
			
			if ($exit['vrijednost'] == "suspend") {
				$this->scormvarijable->azuriraj_element($id, $sco_id, 'cmi.core.entry', 'resume');
			} else {
				$this->scormvarijable->azuriraj_element($id, $sco_id, 'cmi.core.entry', '');
			}
			
			$ukupno_vrijeme = $this->scormvarijable->daj_za_sco_element ($id, $sco_id, 'cmi.core.total_time');
			$vrijeme = explode (":", $ukupno_vrijeme['vrijednost']);

			
			$ukupno_sekundi = $vrijeme[0]*60*60 + $vrijeme[1]*60 + $vrijeme[2];
			
			$vrijeme_sesije = $this->scormvarijable->daj_za_sco_element ($id, $sco_id, 'cmi.core.session_time');
			
			if ($vrijeme_sesije['vrijednost'] == "") {
				$vrijeme_sesije['vrijednost'] = "00:00:00";	
			}
			

			$vrijeme = explode (":", $vrijeme_sesije['vrijednost']);
			$sekunda_sesije = $vrijeme[0]*60*60 + $vrijeme[1]*60 + $vrijeme[2]; 
			
			
			$ukupno_sekundi += $sekunda_sesije;
			
			$sati = intval ($ukupno_sekundi/3600);
			$ukupno_sekundi -= $sati*3600;
			$minuta = intval($ukupno_sekundi/60);
			$ukupno_sekundi -= $minuta * 60;
			
			$vrijeme = sprintf("%04d:%02d:%02d", $sati, $minuta, $ukupno_sekundi);
			$this->scormvarijable->azuriraj_element ($id, $sco_id, 'cmi.core.total_time', $vrijeme);
			$this->scormvarijable->azuriraj_element ($id, $sco_id,'cmi.core.session_time', '');
		}
		header ("Content-Type: application/json;charset=ISO-8859-1");
		echo json_encode(array ("result" => "true", 'time' => $vrijeme));
	}
}
?>