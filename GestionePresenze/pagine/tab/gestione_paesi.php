<?php
    if($_POST){
        if($_POST["azione"] == "formModificaPaese"){
            if($_POST["paese"] > 0){
                $p = new Paese();
                $p->trovaPaeseDaId($_POST["paese"]);
                creaFormPaese($p,$p->errori,"modificaPaese","Salva");
            }
            else
                creaFormPaese();
        }
        else if($_POST["azione"] == "creaPaese"){
            $p = new Paese();
            $p->setNome($_POST["nome"]);
            $p->setCap($_POST["cap"]);
            $p->setNazione($_POST["nazione"]);
            if($p->aggiungiPaese()) {
                creaFormPaese();
                echo "Paese creato con successo";
            }
            else 
                creaFormPaese($p,$p->errori);
        }
        else if($_POST["azione"] == "modificaPaese"){
            $p = new Paese();
            $p->setId($_POST["paese"]);
            $p->setNome($_POST["nome"]);
            $p->setCap($_POST["cap"]);
            $p->setNazione($_POST["nazione"]);
            if($p->aggiornaPaese()) {
                creaFormPaese();
                echo "Paese aggiornato con successo";
            }
            else {
                unset($_POST["azione"]);
                creaFormPaese($p,$p->errori,"modificaPaese","Salva");
            }
        }
        else if($_POST["azione"] == "elimina"){
            $p = new Paese();
            $p->setId($_POST["paese"]);
            if($p->eliminaPaese()) {
                creaFormPaese();
                echo "Paese eliminato con successo";
            }
        }
        else
            creaFormPaese();
    }
    else
        creaFormPaese();
?>

<?php
function creaFormPaese($p = null,$errori = array(),$azione = "creaPaese",$bottone = "Crea Paese") {
    creaListaPaesi();
?>
    <form method="POST">
        <input type="hidden" id="azione" name="azione" value="<?php echo $azione?>">
        <input type="hidden" name="paese" value="<?php echo $_POST["paese"]; ?>">
        <table>
            <tr>
                <td>Nome: </td>
                <td>
                    <input type="text" name="nome" value="<?php echo $p->nome;?>" <?php echo isset($errori["nome"])?"class='errore'":""; ?>>
                </td>
                <td class="messaggioErrore"><?php echo $errori["nome"]; ?></td>
            </tr>
            <tr>
                <td>CAP: </td>
                <td>
                    <input type="text" name="cap" value="<?php echo $p->cap;?>" <?php echo isset($errori["cap"])?"class='errore'":""; ?>>
                </td>
                <td class="messaggioErrore"><?php echo $errori["cap"]; ?></td>
            </tr>
            <tr>
                <td>Nazione: </td>
                <td>
                    <select name="nazione">
                    <?php
                        $rs = Database::getInstance()->eseguiQuery("SELECT * FROM nazioni;");
                        while (!$rs->EOF) {
                            $selected="";
                            if($rs->fields["id_nazione"] == $p->fk_nazione)
                                $selected="selected";
                            echo "<option value=".$rs->fields["id_nazione"]." ".$selected.">".$rs->fields["nome"]."</option>";
                            $rs->MoveNext();
                        }
                    ?>
                    </select>
                </td>
            </tr>
        </table><br />
        <input type="button" value="Annulla" onclick="location.href='index.php?pagina=amministrazione&tab=gestione_paesi'" class="bottCalendario"> &nbsp;<?php if($azione=="modificaPaese") {?><input type="submit" value="Elimina" onclick="document.getElementById('azione').value='elimina'" class="bottCalendario"> &nbsp;<?php  }?><input type="submit" value="<?php echo $bottone; ?>" class="bottCalendario">
    </form>
<?php }
function creaListaPaesi(){
        echo "<form method='POST' action='#'>";
            echo '<input type="hidden" name="azione" value="formModificaPaese">';
            echo "<select name='paese' onchange='this.form.submit();'>";
                echo "<option value='0' selected>Nuovo Paese</option>";
                foreach (Paese::getListaPaesi() as $id => $nome) {
                    $selected="";
                    if($id == $_POST["paese"] && $_POST["azione"] != "modificaPaese")
                        $selected="selected";

                    echo "<option value='".$id."' ".$selected.">".$nome."</option>";
                }
            echo "</select>";
        echo "</form>";
    }

?>