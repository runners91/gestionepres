<table>
    <tr>
        <td rowspan="2">
            <?php Calendario::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php stampaEvento::stampaFormAggiungiEvento(); ?>
        </td>
    </tr>
    <tr>
        <td class="eventiOggi">
            <?php stampaEvento::stampaReportEventi(); ?>
        </td>
    </tr>
</table>
