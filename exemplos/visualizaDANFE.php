<?php
$nfe = $_GET['idNFe'];
$mod = $_GET['mod'];

if($mod === '55'){
  header('Location: viewMod55.php?idNfe='.$nfe);
}elseif($mod === '65') {
  header('Location: viewMod65.php?idNfe='.$nfe);
}
