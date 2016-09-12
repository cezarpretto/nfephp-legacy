<?php
date_default_timezone_set('America/Cuiaba');

require_once(dirname(__FILE__).'/../libs/NFe/DanfeNFePHP.class.php');

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
$danfe  = new DanfeNFePHP($docxml, 'P', 'A4', dirname(__FILE__).'/../../images/logo.jpg', 'I', '');
$id     = $danfe->montaDANFE();
$teste  = $danfe->printDANFE($id.'.pdf', 'I');
