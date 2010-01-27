<?php
/**
 * Description of Autorizzazione
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */

class Autorizzazione {

     /**
     *  Controlla se l'utente ha accesso alla pagina, ritorna true se è si, altrimenti ritorna false
     * @param String $pagina Contiente la pagina da contrlollare
     * @return boolean
     */
    static function verificaAccesso($pagina){
       if($pagina=="") $pagina = "home";
       $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as accesso FROM dipendenti d,gruppi g, dipendenti_gruppi dg, gruppi_pagine gp, pagine p WHERE d.id_dipendente = dg.fk_dipendente AND dg.fk_gruppo = g.id_gruppo AND g.id_gruppo = gp.fk_gruppo AND gp.fk_pagina = p.id_pagina AND d.username = ? AND p.url = ?",array($_SESSION['username'],$pagina));
       return $rs->fields['accesso'] >= 1;
    }

     /**
     *  Controlla se l'utente fa parte di un gruppo amministrativo
     * @param String $username username dell'utente di cui si controlla l'autorizzazione
     * @return boolean
     */
    static function gruppoAmministrazione($username){
        $sql = "select count(*) as count from gruppi g, dipendenti_gruppi dg, dipendenti d where dg.fk_gruppo = g.id_gruppo and dg.fk_dipendente = d.id_dipendente and g.amministra = 'Y' and d.username = ?";
        $rs = Database::getInstance()->eseguiQuery($sql,array($username));
        return $rs->fields['count']>=1;    
    }
}
?>
