<?php

/* -----------------------------------
	Script: 	Sucuri Scan
	Author: 	Jeffry Ghazally
	Usage:		sitecheck <host>
	Desc:		Uses the website sucuri.com
				to determine if a site is up. Returns status
				via Growl.
	Updated:	7/8/11
----------------------------------- */

//Pull hostname off of the command line
$q=$argv[1];

//Retrieve status from isup.me
$url 		= "http://sitecheck.sucuri.net/results/$q";
$crl 		= curl_init();
$timeout 	= 10;
curl_setopt ($crl, CURLOPT_URL, $url);
curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
$def 		= curl_exec($crl);
curl_close  ($crl);

//Search the result text to determine the status
$find = strpos($def, "Verified Clean");

//Return status
if ($find) { echo "The site ".ucfirst($q)." is clean."; }
else { echo "The site ".ucfirst($q)." is compromised"; }
