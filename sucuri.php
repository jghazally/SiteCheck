<?php

/* -----------------------------------
	Script: 	Sucuri Scan
	Author: 	Jeffry Ghazally
	Usage:		Sucuri <host>
	Desc:		Uses the website sucuri.com
				to determine if a site is compromised or clean and returns status
				via Growl.
	Updated:	7/8/11
----------------------------------- */

//Pull hostname off of the command line
$q=$argv[1];

//Retrieve status from isup.me
$url 		= "http://sitecheck.sucuri.net/scanner/?scan={$q}&serialized&alfred";
$timeout 	= 10;

//Request Curl
$crl 		= curl_init();
curl_setopt ($crl, CURLOPT_URL, $url);
curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
$def 		= curl_exec($crl);
curl_close  ($crl);

$def = unserialize($def);
$output   = "Site: {$q} \r\n";;
$warnings = '';
$status   = '';

// Loop through the array and find all warn
foreach ( (array)$def as $node ) {
	if ( isset($node['WARN']) ) {
		foreach ((array)$node['WARN'] as $warn) {
			// loop through each warn array
			foreach ((array)$warn as $w) {
				$warnings[] = $w;
			}
		}
	}
}

if ( !empty($warnings) ) {
	$status = 'Sucuri Found Errors > ';
	$warnings = implode(' | ', $warnings);
} else {
	$status = 'Your interwebs is secure';
}


echo $output . $status . $warnings;
