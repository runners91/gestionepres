<table>
    <tr>
        <td colspan="2">
            <?php Calendario::stampaParametriCalendario($_POST['m']); ?>
        </td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="cellaCalendario">
            <?php Calendario::stampaCalendario($_POST['m']); ?>
        </td>
        <td valign="top">
            <?php
                stampaEvento::stampaFormEvento();
            ?>
        </td>
    </tr>
    <tr>
        <td align="center">
            <?php stampaEvento::stampaReportEventi(); ?>
        </td>
    </tr>
</table>
