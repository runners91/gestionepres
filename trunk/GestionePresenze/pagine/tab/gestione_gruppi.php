<?php
    if($_POST["azione"] != "nuovo"){
        $stampa = true;
        if($_POST["azione"] == "aggiungi"){
            Database::getInstance()->eseguiQuery("INSERT INTO gruppi_pagine values (".$_POST["gruppo"].",".$_POST["pagina"].");");
        }
        else if($_POST["azione"] == "elimina"){
            Database::getInstance()->eseguiQuery("DELETE FROM gruppi_pagine where fk_gruppo = ".$_POST["gruppo"]." AND fk_pagina = ".$_POST["pagina"].";");
        }
        else if($_POST["azione"] == "crea"){
            $nome = trim($_POST["nome"]);
            if(strlen($nome)==0){
                $errori["nome"] = "Il nome non pu&ograve; avere valore nullo";
                stampaFormAggiungi($errori);
                $stampa = false;
            }
            else{
                $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as tot FROM gruppi WHERE nome = '".$nome."';");
                if($rs->fields["tot"]>0){
                    $errori["nome"] = "Il gruppo &egrave; gi&agrave; esistente";
                    stampaFormAggiungi($errori,$nome);
                    $stampa = false;
                }
                else
                    Database::getInstance()->eseguiQuery("INSERT INTO gruppi (nome) values ('".$nome."');");
            }
        }
        else if($_POST["azione"] == "eliminaGruppo"){
            Database::getInstance()->eseguiQuery("DELETE FROM dipendenti_gruppi where fk_gruppo = ".$_POST["gruppo"].";");
            Database::getInstance()->eseguiQuery("DELETE FROM gruppi_pagine where fk_gruppo = ".$_POST["gruppo"].";");
            Database::getInstance()->eseguiQuery("DELETE FROM gruppi where id_gruppo = ".$_POST["gruppo"].";");
            unset($_POST["gruppo"]);
        }
        if($stampa){
            $id = stampaGruppi();

            if(isset($_POST["gruppo"]))
                $id = $_POST["gruppo"];

            stampaPagine($id);
        }
    }
    else {
        stampaFormAggiungi();
    }
?>

<?php
    function stampaGruppi(){
        $rs = Database::getInstance()->eseguiQuery("SELECT * from gruppi;");
        $id = $rs->fields["id_gruppo"];
        echo '<form action="?pagina=amministrazione&tab=gestione_gruppi" method="POST">';
            echo '<select name="gruppo" onchange="this.form.submit();">';
                while(!$rs->EOF){
                    $selected="";
                    if($rs->fields['id_gruppo']==$_POST["gruppo"] || $_POST["azione"]=="crea")
                        $selected="selected";
                    echo '<option value="'.$rs->fields["id_gruppo"].'" '.$selected.'>'.$rs->fields["nome"].'</option>';
                    $rs->MoveNext();
                }
            echo '</select>';
        echo '</form>';
        if($_POST["azione"]=="crea"){
            $rs->MoveLast();
            return $rs->fields['id_gruppo'];
        }
        else
            return $id;
    }
    function stampaPagine($gruppo){
        echo '<fieldset style="width:150px;float:left;height:200px;margin-left:150px;">';
            echo '<legend>Altre Pagine:</legend>';
            $rs = Database::getInstance()->eseguiQuery("SELECT * from pagine p where p.id_pagina not in (SELECT p.id_pagina FROM gruppi_pagine gp,pagine p where p.id_pagina = gp.fk_pagina AND fk_gruppo = ".$gruppo.");");
            $count = $rs->recordCount();
            if($count>6)
                echo '<div style="overflow-y:scroll;height:190px;">';
                while(!$rs->EOF){
                    stampaFormPagine("aggiungi",$gruppo,$rs->fields["id_pagina"],$rs->fields["url"]);
                    $rs->MoveNext();
                }
            if($count>6)
                echo "</div>";
        echo '</fieldset>';
        echo '<fieldset style="width:150px;height:200px;margin-left:150px;">';
            echo '<legend>Pagine del Gruppo:</legend>';
            $rs = Database::getInstance()->eseguiQuery("SELECT p.* FROM gruppi_pagine gp,pagine p where p.id_pagina = gp.fk_pagina AND fk_gruppo = ".$gruppo.";");
            $count = $rs->recordCount();
            if($count>6)
                echo '<div style="overflow-y:scroll;height:190px;">';
            while(!$rs->EOF){
                stampaFormPagine("elimina",$gruppo,$rs->fields["id_pagina"],$rs->fields["url"]);
                $rs->MoveNext();
            }
            if($count>6)
                echo "</div>";
        echo '</fieldset>';
        ?>
        <br>
        <form action="#" method="POST">
            <input type="hidden" id="azione" name="azione" value="eliminaGruppo">
            <input type="hidden" name="gruppo" value="<?php echo $gruppo; ?>">
            <input class="bottCalendario" type="submit" value="Aggiungi Gruppo" onclick="document.getElementById('azione').value='nuovo'"/> &nbsp;<input class="bottCalendario" type="submit" value="Elimina Gruppo"/>
        </form>
<?php
    }
    function stampaFormPagine($azione,$gruppo,$idPagina,$nomePagina){
    ?>
        <form action="?pagina=amministrazione&tab=gestione_gruppi" name="form_<?php echo $idPagina?>" method="POST">
            <input type="hidden" name="azione" value="<?php echo $azione; ?>">
            <input type="hidden" name="gruppo" value="<?php echo $gruppo; ?>">
            <input type="hidden" name="pagina" value="<?php echo $idPagina; ?>">
            <?php echo $nomePagina; ?>
            <img class="puntatore" style="float:right;" onclick="form_<?php echo $idPagina; ?>.submit();" src="/GestionePresenze/img/<?php echo $azione; ?>.png" alt="aggiungi" />
        </form>
<?php
}
    function stampaFormAggiungi($errori= null,$nome="") {
?>
        <form action="#" method="POST">
            <input type="hidden" name="azione" value="crea">
            <div class="messaggioErrore"><?php echo $errori["nome"]; ?></div>
            <font class="label">Nome Gruppo:</font> <input type="text" name="nome" value="<?php echo $nome; ?>" <?php echo isset($errori["nome"])?"class='errore'":""; ?>/>
            <input type="submit" class="bottCalendario" value="Crea gruppo">
            
        </form>
<?php
    }
?>
