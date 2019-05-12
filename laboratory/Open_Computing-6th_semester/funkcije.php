<?php

function formiraj_query($zahtjev) {
    $query = array();

    $crkva_naziv = $zahtjev['crkva_naziv']; 
    if(!empty($crkva_naziv)) 
        $query[] = "./naziv[contains(translate(text(),
        'ABCDEFGHIJKLMNOPURSTUWXYZŠĐČĆŽ','abcdefghijklmnopurstuwxyzšđčćž'),'"
        .mb_strtolower($crkva_naziv)."')]";
    
    $dan_u_tjednu = $zahtjev['dan_u_tjednu'];
    $misa_vrijeme = $zahtjev['misa_vrijeme'];
    $ispovijed = $zahtjev['ispovijed'];
    if(!empty($dan_u_tjednu) || !empty($misa_vrijeme) || !empty($ispovijed)) {
        $misa_upit = array();
        if(!empty($dan_u_tjednu)) $misa_upit[] = "@dan_u_tjednu='".$dan_u_tjednu."'";
        if(!empty($misa_vrijeme)) $misa_upit[] ="./vrijeme/text()='".$misa_vrijeme."'"; 
        if(!empty($ispovijed)) $misa_upit[] = " @ispovijed='".$ispovijed."'";
        $query[] = "./misa[". implode(" and ", $misa_upit). "]";
    }

    $kapacitet = $zahtjev['kapacitet'];
    if(!empty($kapacitet)) $query[] = "./max_kapacitet/text()<=".$kapacitet;
    
    $mjesto = $zahtjev['mjesto'];
    $pos_broj = $zahtjev['poštanski_broj'];
    $ul_naziv = $zahtjev['ulica_naziv'];
    $ul_broj = $zahtjev['ulica_broj'];
    if(!empty($mjesto) || !empty($pos_broj) 
        || !empty($ul_naziv) || !empty($ul_broj)) {
            $adresa_upit = array();
            if(!empty($mjesto)) $adresa_upit[] = "./mjesto[contains(translate(text(),
            'ABCDEFGHIJKLMNOPURSTUWXYZŠĐČĆŽ','abcdefghijklmnopurstuwxyzšđčćž'),'"
            .mb_strtolower($mjesto)."')]";
            if(!empty($pos_broj)) $adresa_upit[] = "./mjesto[@pos_broj='".$pos_broj."']";
            if(!empty($ul_naziv)) $adresa_upit[] = "./ulica[contains(text(),'".mb_strtolower($ul_naziv)."')]";
            if(!empty($ul_broj)) $adresa_upit[] = "./ulica[@kuc_broj='".$ul_broj."']";
            $query[] = "./adresa[".implode(" and ",$adresa_upit)."]";
        }

    $ime_zu = $zahtjev['župnik_ime'];
    $prezime_zu = $zahtjev['župnik_prezime'];
    if(!empty($ime_zu) || !empty($prezime_zu)) {
        $zupnik_upit = array();
        if(!empty($ime_zu)) $zupnik_upit[] = "./ime[contains(translate(text(),
        'ABCDEFGHIJKLMNOPURSTUWXYZŠĐČĆŽ','abcdefghijklmnopurstuwxyzšđčćž'),'"
        .mb_strtolower($ime_zu)."')]";
        if(!empty($prezime_zu)) $zupnik_upit[] = "./prezime[contains(translate(text(),
        'ABCDEFGHIJKLMNOPURSTUWXYZŠĐČĆŽ','abcdefghijklmnopurstuwxyzšđčćž'),'"
        .mb_strtolower($prezime_zu)."')]";  
        $query[] = "./župnik[".implode(" and ",$zupnik_upit)."]";
    }

    $email_ured = $zahtjev['žu_email'];
    $tel_tip_ured = $zahtjev['žu_telefon_tip'];
    $poz_br_ured = $zahtjev['žu_pozivni_broj'];
    $broj_tel = $zahtjev['žu_tel_broj'];
    if(!empty($email_ured) || !empty($tel_tip_ured) || !empty($poz_br_ured)
        || !empty($broj_tel)) {
            $ured = array();
            if(!empty($email_ured)) $ured[] = "./mail[contains(translate(text(),
            'ABCDEFGHIJKLMNOPURSTUWXYZŠĐČĆŽ','abcdefghijklmnopurstuwxyzšđčćž'),'"
             .mb_strtolower($email_ured)."')]";
            if(!empty($tel_tip_ured)) $ured[] = "./zu_telefon[@tip='".$tel_tip_ured."']";
            if(!empty($poz_br_ured)) $ured[] = "./zu_telefon[@br_mreze='".$poz_br_ured."']";
            if(!empty($broj_tel)) $ured[] = "./zu_telefon[contains(text(),'".$broj_tel."')]";
            $query[] = "./župni_ured[".implode(" and ",$ured)."]";
        }
    
        $naziv_zbor = $zahtjev['zbor_naziv'];
        $broj_zbor = $zahtjev['zbor_broj'];
        if(!empty($naziv_zbor) || !empty($broj_zbor)) {
            $zbor = array();
            if(!empty($naziv_zbor)) $zbor[] = "./zbor_naziv[contains(translate(text(),
            'ABCDEFGHIJKLMNOPURSTUWXYZŠĐČĆŽ','abcdefghijklmnopurstuwxyzšđčćž'),'".mb_strtolower($naziv_zbor)."')]";
            if(!empty($broj_zbor)) $zbor[] = "./broj/text()<=".$broj_zbor;
            $query[] = "./zbor[".implode(" and ",$zbor)."]";
        }

    $god_osnutka = $zahtjev['godina_osnutka'];
    if(!empty($god_osnutka)) $query[] = "./god_osnutka/text()<=".$god_osnutka;

    $aktivnosti = $zahtjev['aktivnosti'];
    if(count($aktivnosti)) {
        $akt_upit = array();
        foreach($aktivnosti as $aktivnost) {
            $akt_upit[] = "@naziv='".$aktivnost."'";
        }
        $query[] = "./aktivnost[".implode(" or ",$akt_upit)."]";
    }
    if(count($query)) return "//crkva[".implode(" and ",$query)."]";
    else return "//crkva";
}
?>
