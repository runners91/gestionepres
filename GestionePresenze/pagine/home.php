<table>
    <tr>
        <td>
            <?php Calendario::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") Calendario::stampaFormAggiungiTask(); ?>
        </td>
    </tr>
</table>
