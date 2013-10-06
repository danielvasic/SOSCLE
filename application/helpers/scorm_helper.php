<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists ("dajScoMeni")) {
	function dajScoMeni ($manifest, $path) {
		$organizacije = $manifest->dajOrganizacije ();
		foreach ($organizacije as $organizacija)
		echo sastaviMeni ($organizacija->dajPodStavke(), $manifest->dajIdGlavneOrganizacije(), $manifest, $path);	
	}
}

if (!function_exists ('sastaviMeni')) {
	function sastaviMeni ($stavke, $default, $manifest, $path) {
		foreach ($stavke as $stavka) {
			if ($stavka->dajIdResursa() == "") {
				echo "<li class=\"nav-header\">".$stavka->dajNaslov();
				sastaviMeni ($stavka->dajPodStavke(), $default, $manifest, $path);
			} else {
				$resource = $manifest->dajResurs ($stavka->dajIdResursa());
				
				echo "<li><a target=\"sco_window\" href=\"" . $path ."/". $resource->dajHref() . "". $stavka->dajParametre() . "\">" . $stavka->dajNaslov() . "</a></li></li>";
			}
		}
		return;
	}	
}
