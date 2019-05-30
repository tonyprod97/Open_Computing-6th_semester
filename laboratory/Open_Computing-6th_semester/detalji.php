<?php
$id_crkve = $_REQUEST['id'];
if(isset($id_crkve)){
    $dom = new DOMDocument();
    $dom->load("podaci.xml");

    $xpath = new DOMXPath($dom);
    $upit = "//crkva[@id='".$id_crkve."']";
    echo $upit;
    $crkva = $xpath->query($upit)->item(0);
    echo '<div>'.$crkva->getElementsByTagName('naziv')->item(0)->nodeValue.'</div>';
    }

?>
