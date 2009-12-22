<table>
    <tr>
        <td>
            <?php Utilita::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php if(isset($_GET['data']) && $_GET['event']=="Y") Utilita::stampaFormAggiungiTask(); ?>
        </td>
    </tr>
</table>
