<?php
    if($_POST){
        if($_POST["azione"] == "formModificaUtente"){
            if($_POST["utente"] > 0){
                $d = new Dipendente();
                $d->trovaUtenteDaId($_POST["utente"]);
                creaFormUtente($d,$d->errori,"modificaUtente","Modifica Utente");
            }
            else
                creaFormUtente();
        }
        else if($_POST["azione"] == "creaUtente"){
            $d = new Dipendente();
            $d->setNome($_POST["nome"]);
            $d->setCognome($_POST["cognome"]);
            $d->setUsername($_POST["username"]);
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
            $d->setFiliale($_POST["filiale"]);
            if($d->aggiornaDipendente()){
                creaFormUtente();
                echo 'l\'utente <b>'.$d->nome.' '.$d->cognome.'</b> '.'&egrave; stato modificato con successo. Per modificare i diritti cliccare <a href="?pagina=amministrazione&tab=gestione_autorizzazioni&utente='.$d->username.'">qui</a>';
            }
            else {
                unset($_POST["azione"]);
                creaFormUtente($d,$d->errori,"modificaUtente","Modifica Utente");
            }
        }
    }
    else 
        creaFormUtente();
    
    
    function creaFormUtente($d = null,$errori = array(),$azione = "creaUtente",$bottone = "Crea utente"){
        creaListaUtenti();
?>
    <form action="#" method="POST">
        <input type="hidden" name="azione" value="<?php echo $azione?>">
        <input type="hidden" name="utente" value="<?php echo $d->id; ?>">
        <pre>
            Nome:       <input type="text" name="nome" value="<?php echo $d->nome; ?>" <?php echo $errori["nome"]==1?"class='errore'":""; ?>/><br />
            Cognome:    <input type="text" name="cognome" value="<?php echo $d->cognome; ?>" <?php echo $errori["cognome"]==1?"class='errore'":""; ?>/><br />
            Username:   <input type="text" name="username" value="<?php echo $d->username;?>" <?php echo $errori["username"]==1?"class='errore'":""; ?>/><br />
            Filiale:    <select name="filiale">
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
        </pre>
        <input type="submit" value="<?php echo $bottone; ?>" class="bottCalendario">
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
