<?php
function load()
{

    $dom = new DOMDocument();
    $dom->load('podaci.xml');

    $xp = new DOMXPath( $dom);
    $rezultat = $xp->query( "crkva/adresa/mjesto/contains(text(),'0')" );
    foreach($rezultat as $cvor) {
            print_r($cvor);
           
    }

    
}
