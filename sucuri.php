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
if (isset($argv[2])) {
	$site = explode('=', $argv[1]);
	echo "http://sitecheck.sucuri.net/results/{$site[1]}";
	die;
}
//Retrieve status from isup.me
$url 		= "http://sitecheck.sucuri.net/scanner/?scan={$q}&serialized&alfred";
$crl 		= curl_init();
$timeout 	= 10;
curl_setopt ($crl, CURLOPT_URL, $url);
curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
$def 		= curl_exec($crl);
curl_close  ($crl);

$def = unserialize($def);

$output   = 'Site: ' . $q . "\r\n";
$warnings = array();
$status   = '';

if ( isset($def['WEBAPP']['WARN'])) {
	$status = 'Sucuri Found Errors >';
	foreach ($def['WEBAPP']['WARN'] as $warn) {
		$warnings[] = $warn;
	}
} else {
	$status = 'Your site is clean';
}

$warnings = implode(' | ', $warnings);
echo $output . $status . $warnings;