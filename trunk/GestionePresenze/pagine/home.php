<?php
?>
<table class="contenuto">
    <tr>
        <td colspan="2" align="center">
            <?php
            login();
            ?>
        </td>
    </tr>
    <tr>
        <td align="center"> <?php
            if(isset($_SESSION['username'])) {
                Assenze::stampaReportAssenze();
            }?>
        </td>
        <td align="center"> <?php
            if(isset($_SESSION['username'])) {
                Assenze::inserisciReportInserimento();
            }?>
        </td>
    </tr>
</table>
