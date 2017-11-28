<?php
date_default_timezone_set('America/Cuiaba');

class VisualizaDanfe {

    public function getXml($api, $param) {
        $ref = $this->idNfe ? '/getXmlNfe/' : '/xml/';
        return $this->request($api.$ref.$param);
    }

    public function request($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $url);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function validate($params, $err = null) {
        if (!isset($params['idNFe']) && !isset($params['chvNFe']))
            $err = 'Invalid request params. idNFe or chvNFe is required';

        if (isset($params['idNFe']) && !isset($params['mod']))
            $err = 'Mod is required';

        if ($err) throw new \Exception($err);

        return true;
    }

    public function hasAttr($attr) {
        return isset($_GET[$attr]) ? $_GET[$attr] : null;
    }

    public function setID($idNfe) {
        $this->idNfe = (int) rtrim($idNfe);
    }

    public function setChave($chvNfe) {
        $this->chvNfe = rtrim($chvNfe);
    }

    public function setMod($mod) {
        $this->mod = $mod;
    }

    public function danfeNfce($doc) {
        require_once(dirname(__FILE__).'/../libs/NFe/DanfeNFCeNFePHP.class.php');
        $danfe  = new DanfeNFCeNFePHP($doc, dirname(__FILE__).'/../images/logo.jpg', 0, '', '');
        $danfe->montaDANFE();
        $danfe->printDANFE('pdf', 'nfce.pdf', 'I');
    }

    public function convert($doc, $id) {
        return $this->mod == 65 ? $this->danfeNfce($doc) : $this->danfeNfe($doc, $id);
    }

    public function danfeNfe($doc, $id) {
        require_once(dirname(__FILE__).'/../libs/NFe/DanfeNFePHP.class.php');
        $danfe  = new DanfeNFePHP($doc, 'P', 'A4', dirname(__FILE__).'/../../images/logo.jpg', 'I', '');
        $danfe->montaDANFE();
        $danfe->printDANFE($id.'.pdf', 'I');
    }

}

try {
    $visualiza = new VisualizaDanfe;
    if ($visualiza->validate($_GET)) {
        $visualiza->setId(
            $visualiza->hasAttr('idNFe')
        );

        $visualiza->setChave(
            $visualiza->hasAttr('chvNFe')
        );

        $visualiza->setMod(
            $visualiza->hasAttr('mod') ?? substr($visualiza->chvNfe, 20, 2)
        );

        $doc = $visualiza->getXml(
            'http://172.16.10.103:8080/CoreQB/sped',
            $visualiza->hasAttr('idNFe') ?? $visualiza->chvNfe
        );

        $visualiza->convert(
            $doc,
            $visualiza->hasAttr('idNFe') ?? $visualiza->chvNfe
        );
    }
} catch (\Exception $err) {
    throw $err;
}

