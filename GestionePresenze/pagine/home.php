<table>
    <tr>
        <td rowspan="2">
            <?php Calendario::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") stampaEvento::stampaFormAggiungiEvento(); ?>
        </td>
    </tr>
    <tr>
        <td class="eventiOggi">
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") stampaEvento::stampaReportEventi(); ?>
        </td>
    </tr>
</table>
