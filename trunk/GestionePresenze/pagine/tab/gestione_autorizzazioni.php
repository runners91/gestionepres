<?php
    $id = stampaUtenti();

    if($_POST["azione"]== "aggiungi"){
        Database::getInstance()->eseguiQuery("INSERT INTO dipendenti_gruppi values (".$_POST["utente"].",".$_POST["gruppo"].");");
    }
    else if($_POST["azione"]== "elimina"){
        Database::getInstance()->eseguiQuery("DELETE FROM dipendenti_gruppi where fk_dipendente = ".$_POST["utente"]." AND fk_gruppo = ".$_POST["gruppo"].";");
    }
    if(isset($_POST["utente"]))
        $id = $_POST["utente"];

    stampaGruppi($id);


?>

<?php

function stampaUtenti(){
    echo '<form action="?pagina=amministrazione&tab=gestione_autorizzazioni" method="POST">';
        $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente id,nome,cognome,username FROM dipendenti;");
        $id = $rs->fields['id'];
        echo "<select name='utente' style='float:left;' onchange='this.form.submit();'>";
            while(!$rs->EOF){
                $selected="";
                if($rs->fields['id'] == $_POST["utente"] || $rs->fields['username'] == $_GET["utente"]){
                    $selected="selected";
                    $id = $rs->fields['id'];
                }

                echo '<option value="'.$rs->fields['id'].'" '.$selected.'>'.$rs->fields['nome'].' '.$rs->fields['cognome'].'</option>';
                $rs->MoveNext();
            }
        echo "</select>";
    echo "</form>";
    return $id;
}

function stampaGruppi($utente){
    echo '<fieldset style="width:150px;float:left;height:200px;margin-left:100px;">';
        echo '<legend>Altri Gruppi:</legend>';
        $rs = Database::getInstance()->eseguiQuery("Select * from gruppi where id_gruppo not in(SELECT g.id_gruppo FROM gruppi g, dipendenti_gruppi dg, dipendenti d where g.id_gruppo = dg.fk_gruppo AND d.id_dipendente = dg.fk_dipendente AND d.id_dipendente = ".$utente.");");
        while(!$rs->EOF){
            stampaFormGruppi("aggiungi",$utente,$rs->fields["id_gruppo"],$rs->fields["nome"]);
            $rs->MoveNext();
        }
    echo '</fieldset>';
    echo '<fieldset style="width:150px;height:200px;margin-left:100px;">';
        echo '<legend>Gruppi dell\'utente:</legend>';
        $rs = Database::getInstance()->eseguiQuery("SELECT g.* from gruppi g, dipendenti_gruppi dg, dipendenti d where g.id_gruppo = dg.fk_gruppo AND d.id_dipendente = dg.fk_dipendente AND d.id_dipendente = ".$utente.";");
        while(!$rs->EOF){
            stampaFormGruppi("elimina",$utente,$rs->fields["id_gruppo"],$rs->fields["nome"]);
            $rs->MoveNext();
        }
    echo '</fieldset>';
}
    function stampaFormGruppi($azione,$utente,$idGruppo,$nomeGruppo){
?>
        <form action="?pagina=amministrazione&tab=gestione_autorizzazioni" name="form_<?php echo $idGruppo?>" method="POST">
            <input type="hidden" name="azione" value="<?php echo $azione; ?>">
            <input type="hidden" name="utente" value="<?php echo $utente; ?>">
            <input type="hidden" name="gruppo" value="<?php echo $idGruppo; ?>">
            <?php echo $nomeGruppo; ?>
            <img style="float:right;" onclick="formModifica_<?php echo $idGruppo; ?>.submit();" src="/GestionePresenze/img/modifica.png" alt="modifica" />
            <img style="float:right;" onclick="form_<?php echo $idGruppo; ?>.submit();" src="/GestionePresenze/img/<?php echo $azione; ?>.png" alt="aggiungi" />
        </form>
        <form action="?pagina=amministrazione&tab=gestione_gruppi" name="formModifica_<?php echo $idGruppo?>" method="POST">
            <input type="hidden" name="gruppo" value="<?php echo $idGruppo; ?>">
        </form>
<?php
    }
?>
<form action="?pagina=amministrazione&tab=gestione_gruppi" method="POST">
    <input type="hidden" name="azione" value="nuovo">
    <input class="bottCalendario" type="submit" value="Aggiungi Gruppo"/>
</form>
    