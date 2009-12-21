<table>
    <tr>
        <td>
            <?php
                if($_POST)
                    Utilita::stampaCalendario($_POST['m']);
                else
                    Utilita::stampaCalendario($_GET['m']);
            ?>
        </td>
        <td>
            <?php if(isset($_GET['date'])) Utilita::stampaFormAggiungiTask(); ?>
        </td>
    </tr>
</table>
