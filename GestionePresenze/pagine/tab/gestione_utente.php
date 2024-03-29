<?php
    if($_POST){
        if($_POST["azione"] == "formModificaUtente"){
            if($_POST["utente"] > 0){
                $d = new Dipendente();
                $d->trovaUtenteDaId($_POST["utente"]);
                creaFormUtente($d,$d->errori,"modificaUtente","Salva");
            }
            else
                creaFormUtente();
        }
        else if($_POST["azione"] == "creaUtente"){
            $d = new Dipendente();
            $d->setNome($_POST["nome"]);
            $d->setCognome($_POST["cognome"]);
            $d->setUsername($_POST["username"]);
            $d->setEmail($_POST["email"]);
            $d->setVacanze($_POST["vacanze"]);
            $d->setTelefono($_POST["telefono"]);
            $d->setNatel($_POST["natel"]);
            $d->setMinPausa($_POST["minPausa"]);
            $d->setPercLavorativa($_POST["percLavorativa"]);
            $d->setFiliale($_POST["filiale"]);
            if($d->aggiungiDipendente()){
                creaFormUtente();
                echo 'l\'utente <b>'.$d->nome.' '.$d->cognome.'</b> '.'&egrave; stato creato con successo. Per aggiungere i diritti cliccare <a href="?pagina=amministrazione&tab=gestione_autorizzazioni&utente='.$d->username.'">qui</a>';
            }
            else
                creaFormUtente($d,$d->errori);
        }
        else if($_POST["azione"] == "modificaUtente"){
            $d = new Dipendente();
            $d->setId($_POST["utente"]);
            $d->setNome($_POST["nome"]);
            $d->setCognome($_POST["cognome"]);
            $d->setUsername($_POST["username"]);
            $d->setEmail($_POST["email"]);
            $d->setVacanze($_POST["vacanze"]);
            $d->setTelefono($_POST["telefono"]);
            $d->setNatel($_POST["natel"]);
            $d->setPercLavorativa($_POST["percLavorativa"]);
            $d->setMinPausa($_POST["minPausa"]);
            $d->setFiliale($_POST["filiale"]);
            if($d->aggiornaDipendente()){
                creaFormUtente();
                echo 'l\'utente <b>'.$d->nome.' '.$d->cognome.'</b> '.'&egrave; stato modificato con successo. Per modificare i diritti cliccare <a href="?pagina=amministrazione&tab=gestione_autorizzazioni&utente='.$d->username.'">qui</a>';
            }
            else {
                unset($_POST["azione"]);
                creaFormUtente($d,$d->errori,"modificaUtente","Salva");
            }
        }
        else
            creaFormUtente();
    }
    else 
        creaFormUtente();
    
    function creaFormUtente($d = null,$errori = array(),$azione = "creaUtente",$bottone = "Crea utente"){
        creaListaUtenti();
?>
    <form action="#" method="POST">
        <input type="hidden" name="azione" value="<?php echo $azione?>">
        <input type="hidden" name="utente" value="<?php echo $d->id; ?>">
        <fieldset style="width:500px;border:1px solid black;">
            <legend>Dati Personali:</legend>
            <table>
                <tr>
                    <td class="label">Nome:</td>
                    <td><input type="text" name="nome" value="<?php echo $d->nome; ?>" <?php echo isset($errori["nome"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["nome"]; ?></td>
                </tr>
                <tr>
                    <td class="label">Cognome:</td>
                    <td><input type="text" name="cognome" value="<?php echo $d->cognome; ?>" <?php echo isset($errori["cognome"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["cognome"]; ?></td>
                </tr>
                <tr>
                    <?php if($d->username == $_SESSION["username"] && !isset($errori["username"])) { ?>
                        <input type="hidden" name="username" value="<?php echo $d->username; ?>">
                    <?php } else {?>
                        <td class="label">Username:</td>
                        <td><input type="text" name="username" value="<?php echo $d->username;?>" <?php echo isset($errori["username"])?"class='errore'":""; ?> /></td>
                        <td class="messaggioErrore"><?php echo $errori["username"]; ?></td>
                    <?php }?>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td><input type="text" name="email" value="<?php echo $d->email; ?>" <?php echo isset($errori["email"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["email"]; ?></td>
                </tr>
                <tr>
                    <td class="label">Telefono:</td>
                    <td><input type="text" name="telefono" maxlength="10" value="<?php echo $d->telefono ?>" <?php echo isset($errori["telefono"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["telefono"]; ?></td>
                </tr>
                <tr>
                    <td class="label">Natel:</td>
                    <td><input type="text" name="natel" maxlength="10" value="<?php echo $d->natel; ?>" <?php echo isset($errori["natel"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["natel"]; ?></td>
                </tr>
                <tr>
                    <td class="label">Filiale:</td>
                    <td>
                        <select name="filiale">
                            <?php
                                $rs = Database::getInstance()->eseguiQuery("SELECT id_filiale,nome FROM filiali;");
                                $idFiliale = $d->filiale;
                                while(!$rs->EOF){
                                    $selected = "";
                                    if($idFiliale == $rs->fields["id_filiale"])
                                        $selected = "selected";
                                    echo '<option value="'.$rs->fields["id_filiale"].'" '.$selected.'>'.$rs->fields["nome"].'</option>';
                                    $rs->MoveNext();
                                }
                              ?>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width:500px;border:1px solid black;">
            <legend>Dati Aziendali:</legend>
            <table>
                <tr>
                    <td class="label">% Lavorativa:</td>
                    <td><input type="text" name="percLavorativa" size="1" maxlength="3" value="<?php echo $d->percLavorativa?$d->percLavorativa:100; ?>" <?php echo isset($errori["percLavorativa"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["percLavorativa"]; ?></td>
                </tr>
                <tr>
                    <td class="label">Vacanze:</td>
                    <td><input type="text" name="vacanze" size="1" maxlength="3" value="<?php echo $d->vacanze?$d->vacanze:25; ?>" <?php echo isset($errori["vacanze"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["vacanze"]; ?></td>
                </tr>
                <tr>
                    <td class="label">Min Pausa obbl:</td>
                    <td><input type="text" name="minPausa" size="1" maxlength="3" value="<?php echo $d->minPausa?$d->minPausa:30; ?>" <?php echo isset($errori["minPausa"])?"class='errore'":""; ?>/></td>
                    <td class="messaggioErrore"><?php echo $errori["minPausa"]; ?></td>
                </tr>
            </table>
        </fieldset>
        <br>
        <input type="button" value="Annulla" onclick="location.href='index.php?pagina=amministrazione&tab=gestione_utente'" class="bottCalendario"> &nbsp;<input type="submit" value="<?php echo $bottone; ?>" class="bottCalendario">
    </form>
<?php
    }
    function creaListaUtenti(){
        $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente id,nome,cognome FROM dipendenti;");
        echo "<form method='POST' action='#'>";
            echo '<input type="hidden" name="azione" value="formModificaUtente">';
            echo "<select name='utente' onchange='this.form.submit();'>";
                echo "<option value='0' selected>Nuovo Utente</option>";
                while(!$rs->EOF){
                    $selected="";
                    if($rs->fields['id'] == $_POST["utente"] && $_POST["azione"] != "modificaUtente"){
                        $selected="selected";
                        $id = $rs->fields['id'];
                    }

                    echo '<option value="'.$rs->fields['id'].'" '.$selected.'>'.$rs->fields['nome'].' '.$rs->fields['cognome'].'</option>';
                    $rs->MoveNext();
                }
            echo "</select>";
        echo "</form>";
    }
?>
