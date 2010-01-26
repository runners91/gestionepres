<?php

    $sql = "select CONCAT(d.nome,' ',d.cognome) as Nome,s.saldo as Saldo,s.saldo_strd as 'Saldo straordinario',s.vac_spt as 'Vacanze spettanti',s.vac_matr as 'Vacanze maturate',s.vac_rst as 'Vacanze restanti' from dipendenti d,saldi s where s.fk_dipendente = d.id_dipendente and s.fk_dipendente = ?";
    $rs = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente']));

    Utilita::stampaTabella($rs);
    
?>
