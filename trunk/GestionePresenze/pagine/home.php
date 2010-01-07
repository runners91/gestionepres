<table>
    <tr>
        <td rowspan="2">
            <?php Calendario::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") Calendario::stampaFormAggiungiTask(); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") Calendario::stampaReportEventi(); ?>
        </td>
    </tr>
</table>
