<?php
require ("depedency.php");
class Resource extends Dependency{
	private $href;
	private $scormType;
	private $depedencyRef = array ();
	
	public function __construct ($identifer, $type, $href, $scormType) {
		parent::__construct ($identifer, $type);
		$this->href = $href;
		$this->scormType = $scormType;	
	}
	
	public function addDepedencyRef ($depedencyRef) { array_push ($this->depedencyRef, $depedencyRef); }
	
	public function hasDepedencyRef () { return count ($this->depedencyRef) > 0 ? TRUE : FALSE; }
	
	public function getHref () { return $this->href; }
	public function getScormType () { return $this->scormType; }
	
	public function setHref ($href) { $this->href = $href; }
	public function setScormType ($scormType) { $this->scormType = $scormType; }
}
?>