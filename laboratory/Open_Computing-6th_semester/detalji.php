<?php
error_reporting(0);

$id_crkve = $_REQUEST['id'];
$id_crkve_eng = $_REQUEST['eng'];

sleep(2);
$dom = new DOMDocument();
$dom->load("podaci.xml");

$xpath = new DOMXPath($dom);
$upit = "//crkva[@id='" . $id_crkve . "']";
$crkva = $xpath->query($upit)->item(0);

$htmlTemplate = "";

$htmlTemplate .= '<div class="detalji_prikaz"><p class="naslovni_tekst">Detalji</p>';
$htmlTemplate .= "<div>Naziv: ";
$htmlTemplate .= $crkva->getElementsByTagName('naziv')->item(0)->nodeValue . "</div><hr/>";

$htmlTemplate .= '<p class="list">Mise:</p><ul>';
foreach ($crkva->getElementsByTagName('misa') as $misa) {
    $htmlTemplate .= "<li>" . $misa->getAttribute('dan_u_tjednu') . ", " . $misa->childNodes[1]->nodeValue . "h</li>";
}
$htmlTemplate .= "</ul><hr/>";

$htmlTemplate .= '<div class="naslov">Župnik';
$zupnik = $crkva->getElementsByTagName('župnik')->item(0);
$htmlTemplate .= '<div>' . $zupnik->getElementsByTagName('ime')->item(0)->nodeValue . ' ' . $zupnik->getElementsByTagName('prezime')->item(0)->nodeValue . "<br/>Kontakt: ";
foreach ($zupnik->getElementsByTagName('telefon') as $tel) {
    $htmlTemplate .= $tel->nodeValue . "<br/>";
}
$htmlTemplate .= "</div></div><hr/>";

$mjesto = $crkva->getElementsByTagName('mjesto')->item(0);
$htmlTemplate .= "<div>Mjesto: " . $mjesto->getAttribute('pos_broj') . ', ' . $mjesto->nodeValue . '</div><hr/>';
$htmlTemplate .= "<div>Kapacitet: ";
$kapacitet = $crkva->getElementsByTagName('max_kapacitet')->item(0);
if ($kapacitet) $kapacitet = $kapacitet->nodeValue;
if ($kapacitet) {
    if ($kapacitet < 100) $htmlTemplate .= "Do 100";
    else $htmlTemplate .= "Maksimalno " . $kapacitet;
} else $htmlTemplate .= "Nepoznato";
$htmlTemplate .= "</div><hr/>";

$htmlTemplate .= '<p class="list">Aktivnosti:</p><ul>';
$aktivnosti = $crkva->getElementsByTagName('aktivnost');
if (count($aktivnosti)) {
    foreach ($aktivnosti as $aktivnost) {
        $htmlTemplate .= "<li>" . $aktivnost->getAttribute('naziv') . "</li>";
    }
} else $htmlTemplate .= "Ne postoje aktivnosti";
$htmlTemplate .= "</ul>";

$htmlTemplate .= "</div>";

echo $htmlTemplate;
?>
