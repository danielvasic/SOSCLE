<?php

class Dependency  {
	protected $type;
	protected $identifier;
	protected $base;
	protected $files = array ();
	
	public function __construct ($identifier, $type, $base = "") {
		$this->type = $type;
		$this->identifier = $identifier;
		$this->base = $base;
	}
	
	public function addFile ($file) { array_push ($this->files, $file); }
	public function hasFiles () { return count ($this->files) > 0 ? TRUE : FALSE; }
	
	public function getIdentifier () { return $this->identifier; }
	public function getType () { return $this->type; }
	public function getBase () { return $this->base; }
	
	public function setIdentifier ($identifier) { $this->identifier = $identifier; }	
	public function setType ($type) { $this->type = $type; }
	public function setBase ($base) { $this->base = $base; }
}