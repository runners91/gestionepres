<?php
    if($_POST["azione"] != "nuovo"){
       
        if($_POST["azione"] == "aggiungi"){
            Database::getInstance()->eseguiQuery("INSERT INTO gruppi_pagine values (".$_POST["gruppo"].",".$_POST["pagina"].");");
        }
        else if($_POST["azione"] == "elimina"){
            Database::getInstance()->eseguiQuery("DELETE FROM gruppi_pagine where fk_gruppo = ".$_POST["gruppo"]." AND fk_pagina = ".$_POST["pagina"].";");
        }
        else if($_POST["azione"] == "crea"){
            Database::getInstance()->eseguiQuery("INSERT INTO gruppi (nome) values ('".$_POST["nome"]."');");
        }
        else if($_POST["azione"] == "eliminaGruppo"){
            Database::getInstance()->eseguiQuery("DELETE FROM dipendenti_gruppi where fk_gruppo = ".$_POST["gruppo"].";");
            Database::getInstance()->eseguiQuery("DELETE FROM gruppi_pagine where fk_gruppo = ".$_POST["gruppo"].";");
            Database::getInstance()->eseguiQuery("DELETE FROM gruppi where id_gruppo = ".$_POST["gruppo"].";");
        }
        $id = stampaGruppi();

        if(isset($_POST["gruppo"]))
            $id = $_POST["gruppo"];

        stampaPagine($id);
    }
    else {
?>

<form action="#" method="POST">
    <input type="hidden" name="azione" value="crea">
    Nome Gruppo: <input type="text" name="nome"/>
    <input type="submit" class="bottCalendario" value="Crea gruppo">
</form>


<?php
    }
    function stampaGruppi(){
        $rs = Database::getInstance()->eseguiQuery("SELECT * from gruppi;");
        $id = $rs->fields["id_gruppo"];
        ?>
        <form action="?pagina=amministrazione&tab=gestione_gruppi" method="POST">
            <select name="gruppo" onchange="this.form.submit();">
                <?php
                while(!$rs->EOF){
                    $selected="";
                    if($rs->fields['id_gruppo']==$_POST["gruppo"] || $_POST["azione"]=="crea")
                        $selected="selected";
                    echo '<option value="'.$rs->fields["id_gruppo"].'" '.$selected.'>'.$rs->fields["nome"].'</option>';
                    $rs->MoveNext();
                }
        echo '</select> </form>';

        if($_POST["azione"]=="crea"){
            $rs->MoveLast();
            return $rs->fields['id_gruppo'];
        }
        else
            return $id;
    }
    function stampaPagine($gruppo){
        ?>
        <fieldset style="width:150px;float:left;height:200px;margin-left:150px;">
        <legend>Altre Pagine:</legend>
        <?php
            $rs = Database::getInstance()->eseguiQuery("SELECT * from pagine p where p.id_pagina not in (SELECT p.id_pagina FROM gruppi_pagine gp,pagine p where p.id_pagina = gp.fk_pagina AND fk_gruppo = ".$gruppo.");");
            while(!$rs->EOF){
                stampaFormPagine("aggiungi",$gruppo,$rs->fields["id_pagina"],$rs->fields["url"],"add");
                $rs->MoveNext();
            }
        ?>
         </fieldset>
        <fieldset style="width:150px;height:200px;margin-left:150px;">
        <legend>Pagine del Gruppo:</legend>
        <?php
            $rs = Database::getInstance()->eseguiQuery("SELECT p.* FROM gruppi_pagine gp,pagine p where p.id_pagina = gp.fk_pagina AND fk_gruppo = ".$gruppo.";");
            while(!$rs->EOF){
                stampaFormPagine("elimina",$gruppo,$rs->fields["id_pagina"],$rs->fields["url"],"remove");
                $rs->MoveNext();
            }
        ?>
         </fieldset>
        <form action="#" method="POST">
            <input type="hidden" name="azione" value="nuovo">
            <input class="bottCalendario" type="submit" value="Aggiungi Gruppo"/>
        </form>
        <form action="#" method="POST">
            <input type="hidden" name="azione" value="eliminaGruppo">
            <input type="hidden" name="gruppo" value="<?php echo $gruppo; ?>">
            <input class="bottCalendario" type="submit" value="Elimina Gruppo"/>
        </form>
<?php
     }
    
function stampaFormPagine($azione,$gruppo,$idPagina,$nomePagina,$img){
?>
    <form action="?pagina=amministrazione&tab=gestione_gruppi" name="form_<?php echo $idPagina?>" method="POST">
        <input type="hidden" name="azione" value="<?php echo $azione; ?>">
        <input type="hidden" name="gruppo" value="<?php echo $gruppo; ?>">
        <input type="hidden" name="pagina" value="<?php echo $idPagina; ?>">
        <?php echo $nomePagina; ?>
        <img style="float:right;" onclick="form_<?php echo $idPagina; ?>.submit();" src="/GestionePresenze/img/<?php echo $img; ?>.png" alt="aggiungi" />
    </form>
<?php
}
?>
