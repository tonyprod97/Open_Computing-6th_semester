<?php
#error_reporting( E_ALL ); // development purposes
#ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING);
error_reporting(0); // Disable all errors.
include_once('funkcije.php');

$dom = new DOMDocument();
$dom->load("podaci.xml");

$xpath = new DOMXPath($dom);
$upit = formiraj_query($_REQUEST);
$rezultat = $xpath->query($upit);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Hrvatske Crkve</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="./dizajn.css">
    <link rel="stylesheet" type="text/css" href="./dizajn/pretvorba.css" />
    <script src="./skripte/detalji.js"></script>
</head>

<body>
    <header>
        <figure>
            <a href="./index.html" class="naslovna">
                <img src="./slike/cross.png" alt="Naslovna slika." />
            </a>
        </figure>
        <h4>Crkve Hrvatske</h4>
    </header>

    <nav>
        <ul>
            <li>
                <a href="./index.html">Početna</a>
            </li>
            <li>
                <a href="./obrazac.html">Pretraživanje</a>
            </li>
            <li>
                <a href="./podaci.xml">Popis Crkvi</a>
            </li>
            <li>
                <a href="http://www.fer.unizg.hr/predmet/or">Otvoreno Računarstvo</a>
            </li>
            <li>
                <a href="http://www.fer.unizg.hr/" target="_blank">FER</a>
            </li>
            <li>
                <a href="mailto:antonio.kamber@fer.hr">Email</a>
            </li>
        </ul>
        <div id="detalji"></div>
    </nav>

    <main>
        <section>
            <table>
                <thead>
                    <th></th>
                    <th>Naziv</th>
                    <th>Raspored Misa</th>
                    <th>Adresa</th>
                    <th>Aktivnosti</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php
                    foreach($rezultat as $crkva) {
                        $naziv = $crkva->getAttribute('id');
                        list($slika,$sažetak,$koordinate) = dohvat_wikimedia($naziv,$crkva->getElementsByTagName('naziv')->item(0)->getAttribute('eng'));
                        echo '<tr><td class="slika">';
                        echo $slika.'</td>';

                        echo "<td><p>".$crkva->getElementsByTagName('naziv')->item(0)->nodeValue."</br>".$koordinate."</p></td>";
                        echo "<td>";
                        foreach($crkva->getElementsByTagName('misa') as $misa){
                            echo $misa->getAttribute('dan_u_tjednu').", ".$misa->childNodes[1]->nodeValue."h<br/>";
                        }
                        echo "</td>";

                        $adresa = dohvati_wikiaction($naziv);
                        echo "<td>".dohvati_nom($adresa).'</td>';

                        echo "<td>";
                        $aktivnosti = $crkva->getElementsByTagName('aktivnost');
                        if(count($aktivnosti)) {
                            foreach($aktivnosti as $aktivnost) {
                                echo $aktivnost->getAttribute('naziv')."<br/>";
                            }
                        } else echo "Ne postoje aktivnosti";
                        echo "</td>";
                        echo '<td><a id="'.$naziv.'" onclick="handleDetails(\''.$naziv.'\')" class="detalji">Detalji</a></td>';
                    }
                    ?>
                </tbody>
            </table>

        </section>
    </main>
    <footer>
        Autor: Antonio Kamber
    </footer>
</body>

</html>