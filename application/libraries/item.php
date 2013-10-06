<?php
class Item {
	/*Private atributes*/
	private $identifier;
	private $identifierref;
	private $isvisible;
	private $title;
	private $masteryScore;
	private $maxtimeallowed;
	private $timelimitaction;
	private $prerequisites;
	private $datafromlms;
	private $subitems = array ();
	/*Private atributes*/
	
	/*Simple constructor*/
	public function __construct ($identifier, $identifierref, $title, $isvisible = 'true', $masteryScore = "", $maxtimeallowed = "", $timelimitaction = "", $prerequisites = "", $datafromlms = "") {
		$this->identifier = $identifier;
		$this->identifierref = $identifierref;
		$this->title = $title;
		$this->isvisible = $isvisible;
		$this->masteryScore = $masteryScore;
		$this->maxtimeallowed = $maxtimeallowed;
		$this->timelimitaction = $timelimitaction;
		$this->prerequisites = $prerequisites;
		$this->datafromlms = $datafromlms;
	}
	/*Simple constructor*/
	
	
	public function addSubitems ($item) {
		array_push ($this->subitems, $item);	
	}
	
	
	public function hasSubitems () {
		return count ($this->subitems) > 0 ? TRUE : FALSE;	
	}
	
	/*Getters*/
	public function getSubitems () { return $this->subitems; }
	public function getIdentifier () { return $this->identifier; }
	public function getIdentifierref () { return $this->identifierref; }
	public function getTitle () { return $this->title; }
	public function getMasteryScore () { return $this->masteryScore; }
	public function getPrerequisites () { return $this->prerequisites; }
	public function getTimeLimitAction () { return $this->timelimitaction; }
	public function getDataFromLms () { return $this->datafromlms; }
	public function getMaxTimeAllowed () { return $this->maxtimeallowed; }
	public function getIsvisible () { return $this->isvisible; }
	/*Getters*/
	
	/*Setters*/
	public function setIdentifier ($identifier) { $this->identifier = identifier; }
	public function setIdentifierref ($identifierref) { $this->identifierref = $identifierref; }
	public function setTitle ($title) { $this->title = $title; }
	public function setMasteryScore ($masteryScore) { $this->masteryScore = $masteryScore; }
	public function setIsvisible ($isvisible) { $this->isvisible = $isvisible; }
	public function setPrerequisites ($prerequisites) { $this->prerequisites = $prerequisites; }
	public function setTimeLimitAction ($timelimitaction) { $this->timelimitaction = $timelimitaction; }
	public function setDataFromLms ($datafromlms) { return $this->datafromlms = $datafromlms; }
	public function setMaxTimeAllowed ($maxtimeallowed) { $this->maxtimeallowed = $maxtimeallowed; }
	/*Setters*/
}
?>