<?php
include("classi/Database.php");
$utente = $_POST["utente"];
$rs = Database::getInstance()->eseguiQuery("SELECT * FROM dipendenti WHERE username like ?",array("%".$utente."%"));
?>
<style>
    table.listaUtenti{
        background-color:#D9DADE;
        border:1px solid black;
        border-collapse:collapse;
    }
    table.listaUtenti tr:hover {
        background-color:#C6DEFF;
        cursor:pointer;
    }
</style>
<table style="font-size:11px;width:146px;" class="listaUtenti">
    <?php
    $i=0;
    while(!$rs->EOF){
        echo '<tr class="'.($i%2?"odd":"even").'"" onclick="cercaUtente(\''.$rs->fields["username"].'\');">';
            echo '<td>'.str_ireplace($utente, "<b>".$utente."</b>", $rs->fields["username"]).'</td>';
        echo '</tr>';
        $i++;
        $rs->MoveNext();
    }
    ?>
</table>