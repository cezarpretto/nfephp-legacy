<?php
date_default_timezone_set('America/Cuiaba');

require_once(dirname(__FILE__).'/../libs/NFe/DanfeNFCeNFePHP.class.php');

$nfe = $_GET['idNfe'];

function get_data($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$docxml = get_data('http://172.16.10.103:8080/CoreQB/sped/getXmlNfe/' . $nfe);
$danfe  = new DanfeNFCeNFePHP($docxml, dirname(__FILE__).'/../images/logo.jpg', 0, '', '');
$id     = $danfe->montaDANFE();
$teste  = $danfe->printDANFE('pdf', 'nfce.pdf', 'I');
