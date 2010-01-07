<table>
    <tr>
        <td rowspan="2">
            <?php Calendario::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") Evento::stampaFormAggiungiEvento(); ?>
        </td>
    </tr>
    <tr>
        <td class="eventiOggi">
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") Evento::stampaReportEventi(); ?>
        </td>
    </tr>
</table>
