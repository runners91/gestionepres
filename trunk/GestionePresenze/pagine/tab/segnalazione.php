<?php

        $prio = 0;
        $tipo = 0;
        $d = Dipendente::trovaUtenteDaUsername($_SESSION["username"]);
        $utente = $d->id;
        $segnTxt = '<a href="index.php?pagina=home&&id_evento='; $segnTxt2 = ' ">segnala</a>';
        $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';
        $sql = "SELECT CONCAT('".$prioTxt."',e.priorita,'".$prioTxt2."') as ' ', c.nome as Nome,d.username as Utente,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.commento as Commento, CONCAT('".$segnTxt."',e.id_evento,'".$segnTxt2."') as 'Segnala' FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale and d.id_dipendente = e.fk_dipendente and (e.fk_causale = ".$tipo." or ".$tipo." = 0 ) and (e.priorita = ".$prio." or ".$prio." = 0 ) and (e.fk_dipendente = ".$utente." or ".$utente." = 0 ) ORDER BY DATA_DA";
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields){
            Utilita::stampaTabella($rs);
        }
?>
