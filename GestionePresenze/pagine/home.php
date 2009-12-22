<table>
    <tr>
        <td>
            <?php Utilita::stampaCalendario($_POST['m']); ?>
        </td>
        <td>
            <?php if(isset($_GET['data'])) Utilita::stampaFormAggiungiTask(); ?>
        </td>
    </tr>
</table>
