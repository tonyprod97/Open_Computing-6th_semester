<?php

function formiraj_query($zahtjev) {
    $query = array();

    if(!empty($zahtjev['crkva_naziv'])) 
        $query[] = "./naziv[contains(text(),'".strtolower($zahtjev['crkva_naziv'])."')]";
    
    if(!empty($zahtjev['dan_u_tjednu'])) {
        $misa_upit = "./misa[@dan_u_tjednu='".$zahtjev['dan_u_tjednu']."'";
        if(!empty($zahtjev['misa_vrijeme'])) $misa_upit.=" and ./vrijeme/text()='".$zahtjev['misa_vrijeme']."'"; 
        if(!empty($zahtjev['ispovijed'])) $misa_upit .= " and @ispovijed='".$zahtjev['ispovijed']."'";
        $misa_upit .= "]";
        $query[] = $misa_upit;
    }

    if(!empty($zahtjev['kapacitet'])) $query[] = "./max_kapacitet/text()<=".$zahtjev['kapacitet'];
    
    return "//crkva[".implode(" and ",$query)."]";
}

?>
