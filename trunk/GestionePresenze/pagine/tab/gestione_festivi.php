<table>
    <tr>
        <td style="float:left" valign="top">
<?php
    if($_POST){
        if($_POST["azione"] == "formModificaFestivo"){
            if($_POST["festivo"] > 0){
                $f = new Festivo();
                $f->trovaFestivoDaId($_POST["festivo"]);
                creaFormFestivo($f,$f->getErrori(),"modificaFestivo","Salva");
            }
            else
                creaFormFestivo(new Festivo());
        }
        else if($_POST["azione"] == "creaFestivo") {
            $f = new Festivo();
            $f->setNome($_POST["nome"]);
            $f->setRicorsivo(isset($_POST["ricorsivo"]));
            $f->setData($_POST["data"]);
            $f->setFiliale($_POST["filiale"]);
            $f->setDurata($_POST["durata"]);
            if($f->insersciFestivo()){
                creaFormFestivo(new Festivo());
                $successo = 'Il festivo &egrave; stato aggiunto con successo';
            }
            else 
                creaFormFestivo($f,$f->getErrori());
        }
        else if($_POST["azione"] == "modificaFestivo") {
            $f = new Festivo();
            $f->setId($_POST["festivo"]);
            $f->setNome($_POST["nome"]);
            $f->setRicorsivo(isset($_POST["ricorsivo"]));
            $f->setData($_POST["data"]);
            $f->setFiliale($_POST["filiale"]);
            $f->setDurata($_POST["durata"]);
            if($f->aggiornaFestivo()){
                creaFormFestivo(new Festivo());
                $successo = 'Il festivo &egrave; stato aggiornato con successo';
            }
            else
                creaFormFestivo($f,$f->getErrori(),"modificaFestivo","Salva");
        }
        else if($_POST["azione"] == "elimina"){
            $f = new Festivo();
            $f->setId($_POST["festivo"]);
            if($f->eliminaFestivo()) {
                creaFormFestivo(new Festivo());
                $successo = "Festivo eliminato con successo";
            }
        }
        else
            creaFormFestivo(new Festivo());
    }
    else 
        creaFormFestivo(new Festivo());
    
?>
        </td>
        <td style="width:30px;"></td>
        <td valign="top" ><div class="messaggioTaskOk"><?php echo $successo; ?> </div></td>
    </tr>
</table>
<?php
    function creaFormFestivo($f,$e = array(),$azione = "creaFestivo",$bottone = "Crea Festivo"){
        creaListaFestivi();
?>
        <form method="POST">
            <input type="hidden" id="azione" name="azione" value="<?php echo $azione;?>">
            <input type="hidden" name="festivo" value="<?php echo $_POST["festivo"]; ?>">
            <table>
                <tr>
                    <td>
                        Nome:
                    </td>
                    <td>
                        <input type="text" name="nome" <?php echo isset($e["nome"])?"class='errore'":""; ?> value="<?php echo $f->getNome(); ?>"/>
                    </td>
                    <td class="messaggioErrore">
                        <?php echo $e["nome"]; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Data:
                    </td>
                    <td>
                        <input id="sel1" type="text" name="data" <?php echo isset($e["data"])?"class='errore'":""; ?> value="<?php echo $f->getData()?date("d.m.Y",$f->getData()):date("d.m.Y");?>" maxlength="10"/>
                    </td>
                    <td>
                        <input value="" type="reset" onclick="return showCalendar('sel1', '%d.%m.%Y');" class="imgCal" />
                        <span class="messaggioErrore">
                            <?php echo $e["data"]; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Durata:
                    </td>
                    <td>
                        <select name="durata">
                            <option value="G">Giornata</option>
                            <option value="M" <?php echo $f->getDurata() == "M"?"selected":""; ?>>Mattina</option>
                            <option value="P" <?php echo $f->getDurata() == "P"?"selected":""; ?>>Pomeriggio</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Filiale:
                    </td>
                    <td>
                        <select name="filiale">
                            <?php
                                $rs = Database::getInstance()->eseguiQuery("SELECT id_filiale,nome FROM filiali");
                                while(!$rs->EOF){
                                    $selected = "";
                                    
                                    if($rs->fields["id_filiale"] == $f->getFiliale())
                                        $selected = "selected";
                                    echo '<option value="'.$rs->fields["id_filiale"].'" '.$selected.'>'.$rs->fields["nome"].'</option>';
                                    $rs->MoveNext();
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Ricorsivo:
                    </td>
                    <td>
                        <input type="checkbox" name="ricorsivo" <?php echo $f->isRicorsivo()?"checked":""; ?>/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="Annulla" onclick="location.href='index.php?pagina=amministrazione&tab=gestione_festivi'" class="bottCalendario"> &nbsp;<?php if($azione=="modificaFestivo") {?><input type="submit" value="Elimina" onclick="document.getElementById('azione').value='elimina'" class="bottCalendario"> &nbsp;<?php  }?><input type="submit" value="<?php echo $bottone; ?>" class="bottCalendario">
                    </td>
                </tr>
            </table>
        </form>
<?php
    }

    function creaListaFestivi(){
        echo "<form method='POST' action='#'>";
            echo '<input type="hidden" name="azione" value="formModificaFestivo">';
            echo "<select name='festivo' onchange='this.form.submit();'>";
                echo "<option value='0' selected>Nuovo Festivo</option>";
                $rs = Database::getInstance()->eseguiQuery("SELECT id_festivo,nome FROM festivi");
                while (!$rs->EOF) {
                    $selected="";
                    if($rs->fields["id_festivo"] == $_POST["festivo"] && $_POST["azione"] != "modificaFestivo")
                        $selected="selected";
                    echo "<option value='".$rs->fields["id_festivo"]."' ".$selected.">".$rs->fields["nome"]."</option>";
                    $rs->MoveNext();
                }
            echo "</select>";
        echo "</form>";
    }
?>