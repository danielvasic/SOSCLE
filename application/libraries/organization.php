<?php
class Organization {
	private $identifier;
	private $hierarhical;
	private $title;
	private $items = array ();
	
	public function __construct ($identifier, $title, $hierarhical) {
		$this->identifier = $identifier;
		$this->hierarhical = $hierarhical;
		$this->title = $title;
	}
	
	public function addItem ($item) { array_push ($this->items, $item); }
	public function hasItems () { count($this->items) > 0 ? TRUE : FALSE; }
	
	public function getIdentifier () { return $this->identifier; }
	public function getHierarhical () { return $this->hierarhical; }
	public function getTitle() { return $this->title; }
	public function getItems () { return $this->items; }
	
	public function setIdentifier ($identifier) { $this->identifier = $identifier; }
	public function setHierarhical ($hierarhical) { $this->hierarhical = $hierarhical; }
	public function setTitle ($title) { $this->title = $title; }
	
	public function __toString () { return $this->identifier; }
}
?>