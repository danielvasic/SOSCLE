<?php
require("item.php");
require("resource.php");
require("organization.php");
require("metadata.php");
require("file.php");

class Imsmanifest {
	private $xml;
	private $path;
	
	private $organizationDefault;
	private $organizations = array ();
	private $resources = array ();
	
	private $errorLog = array ();
	private $log = array ();
	private $debug = 1;
	
	public function init ($path) {
		$this->path = $path;
		if (file_exists ($path)) {
			$xmlString = file_get_contents ($path);
			$xmlString = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xmlString);
			$this->xml = simplexml_load_string ($xmlString);
			unset ($xmlString);
			$this->organizationDefault = (string)$this->xml->organizations->attributes()->default;
			
			foreach ($this->xml->organizations->organization as $organization) {
				$organizationAttributes = $organization->attributes();
				$orgObj = new Organization((string)$organizationAttributes->identifier, (string)$organization->title, (string)$organizationAttributes->structure);
				foreach ($organization->item as $item) {
					$orgObj->addItem($this->parseItems($item));
				}
				array_push ($this->organizations, $orgObj);	
			}
			
			foreach ($this->xml->resources as $resources) {
				foreach ($resources->resource as $resource) {
					$resourceAttr = $resource->attributes();

					$resObj = new Resource(
						(string)$resourceAttr->identifier, 
						(string)$resourceAttr->type, 
						(string)$resourceAttr->href, 
						(string)$resource->attributes('adlcp')->scormtype);
							
					foreach ($resource->dependency as $dependency) {
						$resObj->addDepedencyRef((string)$dependency->attributes()->identifierref);	
					}
					
					foreach ($resource->file as $file) {
						$fileAttr = $file->attributes();
						$resObj->addFile(new File((string)$fileAttr->href));	
					}
					
					array_push ($this->resources, $resObj);
				}	
			}
			
		} else {
			if ($this->debug > 0) { 
				array_push ($this->errorLog, "Error No.1: Manifest file path is invalid please supli valid path, check if manifest is there.");
			}	
		}
	}
	
	private function parseItems ($items) {
		$adlcpNs = $items->children('http://www.adlnet.org/xsd/adlcp_rootv1p2');
		$masteryScore = (string)$adlcpNs->masteryscore;
		$prerequisites = (string)$adlcpNs->prerequisites;
		$maxTimeAllowed = (string)$adlcpNs->maxtimeallowed;
		$timeLimitAction = (string)$adlcpNs->timelimitaction;
		$dataFromLms = (string)$adlcpNs->datafromlms;
		
		$itemAttributes = $items->attributes();

		$item = new Item((string)$itemAttributes->identifier, (string)$itemAttributes->identifierref, (string)$items->title, (string)$itemAttributes->isvisible, $masteryScore, $maxTimeAllowed, $timeLimitAction, $prerequisites, $dataFromLms);
		if (isset($items->item)) { 
			foreach ($items->item as $aitem) {
				$pitem = $this->parseItems($aitem);
				$item->addSubitems($pitem);
			}
			return $item;
		} else {
			return $item;
		}
	}

	public function getResource ($id = "") {
		if ($id == "") {
			return $this->resources;	
		} else {
			foreach ($this->resources as $resource) {
				if ($resource->getIdentifier() == $id) {
					return $resource;	
				}	
			}	
		}	
	}
	public function getOrganization ($id = "") {
		if ($id == "") {
			return $this->organizations;	
		} else {
			if ($organization->getIdentifier == $id) {
				return $organization;	
			}
		}	
	}
	public function getDefaultOrganization () {
		foreach ($this->organizations as $organization) {
			if ($organization->getIdentifier() == $this->organizationDefault) {
				return $organization;	
			}	
		}	
	}
	
	public function getDefaultOrganizationIdentifier () {
		return $this->organizationDefault;
	}
	
	public function getXml () { return $this->xml; }
	public function getDebug () { return $this->debug; }
	public function getErrorLog () { return $this->errorLog; }
	public function getLog () { return $this->log; }
	
	public function setXml ($xml) { $this->xml = $xml; }
	public function setDebug ($debug) { $this->debug = $debug; }
	public function setErrorLog ($errorLog) { $this->errorLog = $errorLog; }

}

?>