<script type="text/javascript" src="script/selCalendario.js"></script>
<?php
    $utenti = Utilita::getListaUtentiPerGruppo();
    $visualizza = true;
    $filtriGiorno = null;
    if($_GET["data"]){
        $rs = Calendario::getFestiviGiorno($_SESSION["username"], $_GET["data"]);
        if($rs->fields["durata"] == 'G' && $rs->RowCount()>0)//&& !Autorizzazione::gruppoAmministrazione($_SESSION["username"])
            $visualizza = false;
        $filtriGiorno = $rs->fields["durata"];
    }
?>

<table>
    <tr>
        <td colspan="2">
            <?php Calendario::stampaParametriCalendario($_POST['m'],true,$utenti); ?>
        </td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="cellaCalendario">
            <?php Calendario::stampaCalendario($_POST['m'],$utenti); ?>
        </td>
        <td valign="top">
            <?php
                if($visualizza) stampaEvento::stampaFormEvento($utenti,$filtriGiorno);
            ?>

        </td>
    </tr>
    <tr>
        <td align="center">
            <?php if($visualizza) stampaEvento::stampaReportEventi($utenti); ?>
        </td>
    </tr>
</table>
