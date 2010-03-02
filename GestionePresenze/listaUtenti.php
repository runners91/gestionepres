<?php
include("classi/Database.php");
$utente = $_POST["utente"];
$rs = Database::getInstance()->eseguiQuery("SELECT * FROM dipendenti WHERE username like ?",array("%".$utente."%"));
$count = $rs->rowCount();
echo $rs->rowCount()."||".$rs->fields["username"]."||";
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
    if($count == 0) {
        echo '<tr>';
            echo '<td>Nessun utente trovato</td>';
        echo '</tr>';
    }
    else {
        while(!$rs->EOF){
            echo '<tr onclick="cercaUtente(\''.$rs->fields["username"].'\');">';
                echo '<td>'.str_ireplace($utente, "<b>".$utente."</b>", $rs->fields["username"]).'</td>';
            echo '</tr>';
            $rs->MoveNext();
        }
    }
    ?>
</table>