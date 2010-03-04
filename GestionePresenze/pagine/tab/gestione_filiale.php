<?php
    if($_POST){
        if($_POST["azione"] == "formModificaFiliale"){
            if($_POST["filiale"] > 0){
                $f = new Filiale();
                $f->trovaFilialeDaId($_POST["filiale"]);
                creaFormFiliale($f,$f->errori,"modificaFiliale","Salva");
            }
            else
                creaFormFiliale();
        }
        else if($_POST["azione"] == "creaFiliale"){
            $f = new Filiale();
            $f->setNome($_POST["nome"]);
            $f->setIndirizzo($_POST["indirizzo"]);
            $f->setTelefono($_POST["telefono"]);
            $f->setPaese($_POST["paese"]);
            if($f->aggiungiFiliale()) {
                creaFormFiliale();
                echo "Filiale creata con successo";
            }
            else
                creaFormFiliale($f,$f->errori);
        }
        else if($_POST["azione"] == "modificaFiliale"){
            $f = new Filiale();
            $f->setId($_POST["filiale"]);
            $f->setNome($_POST["nome"]);
            $f->setIndirizzo($_POST["indirizzo"]);
            $f->setTelefono($_POST["telefono"]);
            $f->setPaese($_POST["paese"]);
            if($f->aggiornaFiliale()) {
                creaFormFiliale();
                echo "Filiale aggiornata con successo";
            }
            else {
                unset($_POST["azione"]);
                creaFormFiliale($f,$f->errori,"modificaFiliale","Salva");
            }
        }
        else if($_POST["azione"] == "elimina"){
            $f = new Filiale();
            $f->setId($_POST["filiale"]);
            if($f->eliminaFiliale()){
                creaFormFiliale();
                echo "Filiale eliminata con successo";
            }
        }

    }
    else
        creaFormFiliale();
?>

<?php
function creaFormFiliale($f = null,$errori = array(),$azione = "creaFiliale",$bottone = "Crea Filiale") {
    creaListaFiliali();
?>
    <form method="POST">
        <input type="hidden" id="azione" name="azione" value="<?php echo $azione?>">
        <input type="hidden" name="filiale" value="<?php echo $_POST["filiale"]; ?>">
        <table>
            <tr>
                <td>Nome: </td>
                <td>
                    <input type="text" name="nome" value="<?php echo $f->nome;?>" <?php echo isset($errori["nome"])?"class='errore'":""; ?>>
                </td>
                <td class="messaggioErrore"><?php echo $errori["nome"]; ?></td>
            </tr>
            <tr>
                <td>Indirizzo: </td>
                <td>
                    <input type="text" name="indirizzo" value="<?php echo $f->indirizzo;?>" <?php echo isset($errori["indirizzo"])?"class='errore'":""; ?>>
                </td>
                <td class="messaggioErrore"><?php echo $errori["indirizzo"]; ?></td>
            </tr>
            <tr>
                <td>Telefono: </td>
                <td>
                    <input type="text" name="telefono" value="<?php echo $f->telefono;?>" <?php echo isset($errori["telefono"])?"class='errore'":""; ?>>
                </td>
                <td class="messaggioErrore"><?php echo $errori["telefono"]; ?></td>
            </tr>
            <tr>
                <td>Paese: </td>
                <td>
                    <select name="paese">
                    <?php
                        foreach (Paese::getListaPaesi() as $id => $nome) {
                            $selected="";
                            if($id == $f->fk_paese)
                                $selected="selected";
                            echo "<option value=".$id." ".$selected.">".$nome."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
        </table><br />
        <input type="button" value="Annulla" onclick="location.href='index.php?pagina=amministrazione&tab=gestione_filiali'" class="bottCalendario"> &nbsp;<?php if($azione=="modificaFiliale") {?><input type="submit" value="Elimina" onclick="document.getElementById('azione').value='elimina'" class="bottCalendario"> &nbsp;<?php  }?><input type="submit" value="<?php echo $bottone; ?>" class="bottCalendario">
    </form>
<?php }
function creaListaFiliali(){
        echo "<form method='POST' action='#'>";
            echo '<input type="hidden" name="azione" value="formModificaFiliale">';
            echo "<select name='filiale' onchange='this.form.submit();'>";
                echo "<option value='0' selected>Nuova Filiale</option>";
                foreach (Filiale::getListaFiliali() as $id => $nome) {
                    $selected="";
                    if($id == $_POST["filiale"] && $_POST["azione"] != "modificaFiliale")
                        $selected="selected";

                    echo "<option value='".$id."' ".$selected.">".$nome."</option>";
                }
            echo "</select>";
        echo "</form>";
    }

?>