<?php
#error_reporting( E_ALL ); // development purposes
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING);
#error_reporting(0); // Disable all errors.
include_once('funkcije.php');

$dom = new DOMDocument();
$dom->load("podaci.xml");

$xpath = new DOMXPath($dom);
$upit = formiraj_query($_REQUEST);
#print $upit; // development purposes
$rezultat = $xpath->query($upit);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Hrvatske Crkve</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="./dizajn.css">
    <link rel="stylesheet" type="text/css" href="./dizajn/pretvorba.css" />
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
    </nav>

    <main>
        <section>
            <table>
                <thead>
                    <th>Naziv</th>
                    <th>Raspored Misa</th>
                    <th>Župnik</th>
                    <th>Mjesto</th>
                    <th>Kapacitet</th>
                    <th>Aktivnosti</th>
                </thead>
                <tbody>
                    <?php
                    foreach($rezultat as $crkva) {
                        echo "<tr><td>";
                        echo $crkva->getElementsByTagName('naziv')->item(0)->nodeValue."</td>";
                        
                        echo "<td>";
                        foreach($crkva->getElementsByTagName('misa') as $misa){
                            echo $misa->getAttribute('dan_u_tjednu').", ".$misa->childNodes[1]->nodeValue."h<br/>";
                        }
                        echo "</td>";

                        echo "<td>";
                        $zupnik = $crkva->getElementsByTagName('župnik')->item(0);
                        echo $zupnik->getElementsByTagName('ime')->item(0)->nodeValue.' '.$zupnik->getElementsByTagName('prezime')->item(0)->nodeValue."<br/>Kontakt: ";
                        foreach($zupnik->getElementsByTagName('telefon') as $tel){
                            echo $tel->nodeValue."<br/>";
                        }
                        
                        echo "</td>";

                        $mjesto = $crkva->getElementsByTagName('mjesto')->item(0);
                        echo "<td>".$mjesto->getAttribute('pos_broj').', '.$mjesto->nodeValue.'</td>';

                        echo "<td>";
                        $kapacitet = $crkva->getElementsByTagName('max_kapacitet')->item(0);
                        if($kapacitet) $kapacitet = $kapacitet->nodeValue;
                        if( $kapacitet ) {
                            if( $kapacitet < 100 ) echo "Do 100";
                            else echo "Maksimalno ".$kapacitet;
                        } else echo "Nepoznato";
                        echo "</td>";

                        echo "<td>";
                        $aktivnosti = $crkva->getElementsByTagName('aktivnost');
                        if(count($aktivnosti)) {
                            foreach($aktivnosti as $aktivnost) {
                                echo $aktivnost->getAttribute('naziv')."<br/>";
                            }
                        } else echo "Ne postoje aktivnosti";
                        echo "</td>";
                        
                        echo "</tr>";
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