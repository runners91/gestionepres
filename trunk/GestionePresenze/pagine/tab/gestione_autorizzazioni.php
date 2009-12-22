<?php
$id = stampaUtenti();
if($_POST["azione"]== "aggiungi"){
    Database::getInstance()->eseguiQuery("INSERT INTO dipendenti_gruppi values (".$_POST["utente"].",".$_POST["gruppo"].");");
}else if($_POST["azione"]== "elimina") {
    Database::getInstance()->eseguiQuery("DELETE FROM dipendenti_gruppi where fk_dipendente = ".$_POST["utente"]." AND fk_gruppo = ".$_POST["gruppo"].";");
}
if(isset($_POST["utente"]))
    $id = $_POST["utente"];

stampaGruppi($id);


?>

<?php

function stampaUtenti(){
    echo '<form action="?pagina=amministrazione&tab=gestione_autorizzazioni" method="POST">';
    $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente id,nome,cognome FROM dipendenti;");
    $firstId = $rs->fields['id'];
    echo "<select name='utente' onchange='this.form.submit();'>";
    while(!$rs->EOF){
        $selected="";
        if($rs->fields['id']==$_POST["utente"])
            $selected="selected";
        echo '<option value="'.$rs->fields['id'].'" '.$selected.'>'.$rs->fields['nome'].' '.$rs->fields['cognome'].'</option>';
        $rs->MoveNext();
    }
    echo "</select> </form>";
    return $firstId;
}

function stampaFormGruppi($azione,$utente,$idGruppo,$nomeGruppo,$img){
?>
    <form action="?pagina=amministrazione&tab=gestione_autorizzazioni" name="form_<?php echo $idGruppo?>" method="POST">
        <input type="hidden" name="azione" value="<?php echo $azione; ?>">
        <input type="hidden" name="utente" value="<?php echo $utente; ?>">
        <input type="hidden" name="gruppo" value="<?php echo $idGruppo; ?>">
        <?php echo $nomeGruppo; ?>
        <img onclick="form_<?php echo $idGruppo ?>.submit();" src="/GestionePresenze/img/<?php echo $img; ?>.png" alt="aggiungi" />
    </form>
<?php
}
?>
       
<?php


function stampaGruppi($utente){
    $rs = Database::getInstance()->eseguiQuery("SELECT g.id_gruppo id, g.nome ,CASE WHEN d.id_dipendente = dg.fk_dipendente AND dg.fk_gruppo = g.id_gruppo THEN 1 ELSE 0 END as appartiene FROM gruppi g,dipendenti d, dipendenti_gruppi dg where d.id_dipendente = dg.fk_dipendente AND d.id_dipendente = ".$utente." ORDER BY appartiene desc;");
    
    while(!$rs->EOF){
        if($rs->fields["appartiene"]){
            stampaFormGruppi("elimina",$utente,$rs->fields["id"],$rs->fields["nome"],"remove");
        }else {
            stampaFormGruppi("aggiungi",$utente,$rs->fields["id"],$rs->fields["nome"],"add");
        }
        $rs->MoveNext();
    }
}


?>

