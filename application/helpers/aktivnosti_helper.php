<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists ('daj_status')) {
	function daj_status($status_eng) {
		if ($status_eng == "") return "Nema statusa."; 
		if ($status_eng != "completed" && $status_eng != "passed" && $status_eng != "not attempted" && $status_eng != "incomplete" && $status_eng != "failed") return "Nema statusa.";
		$status = array (
			"completed" => " završio/la ",
			"passed" => " prošao/la ",
			"not attempted" => " nije pokušao/la ",
			"incomplete" => " nije dovršio/la ",
			"failed" => " pao/la "
		);
		return $status[$status_eng];
	} 
}
if (!function_exists ('usporedi_datum')) {
	function usporedi_datum ($a, $b) {
    	$t1 = strtotime($a['datum']);
    	$t2 = strtotime($b['datum']);
    	return $t1 - $t2;
	}    
}
?>