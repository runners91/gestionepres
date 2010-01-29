<?php $utenti = Utilita::getListaUtentiPerGruppo(); ?>

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
            <?php stampaEvento::stampaFormEvento(); ?>
        </td>
    </tr>
    <tr>
        <td align="center">
            <?php stampaEvento::stampaReportEventi($utenti); ?>
        </td>
    </tr>
</table>
