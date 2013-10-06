<?php

class File {
	private $href;
	
	public function __construct ($href) {
		$this->href = $href;
	}
	
	public function getHref () { return $this->href; }
	public function setHref ($href) { $this->href = $href; }
}

?>