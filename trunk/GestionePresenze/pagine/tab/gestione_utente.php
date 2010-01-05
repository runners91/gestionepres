<?php
    if($_POST){
        $errori = array();
        if(strlen(trim($_POST["nome"]))==0){
            $errori["nome"] = 1;
        }
        if(strlen(trim($_POST["cognome"]))==0){
            $errori["cognome"] = 1;
        }
        if(isset($_POST["username"])){
            $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as utente from dipendenti where username = '".$_POST["username"]."';");
            if(strlen(trim($_POST["username"]))==0 || $rs->fields["utente"]==1)
                $errori["username"] = 1;
        }

        if(sizeof($errori)==0){
            Database::getInstance()->eseguiQuery("INSERT INTO dipendenti (nome,cognome,username,password,fk_filiale) values ('".$_POST["nome"]."','".$_POST["cognome"]."','".$_POST["username"]."',md5('inizio'),".$_POST["filiale"].");");
            echo 'l\'utente <b>'.$_POST["nome"].' '.$_POST["cognome"].'</b> '.'&egrave; stato creato con successo. Per aggiungere i diritti cliccare <a href="?pagina=amministrazione&tab=gestione_autorizzazioni&utente='.$_POST["username"].'">qui</a>';
        }
        else 
            creaFormUtente($errori);
        
    }
    else 
        creaFormUtente();
    
    
    function creaFormUtente($errori = array()){
?>

    <form action="#" method="POST">
        <pre>
            Nome:       <input type="text" name="nome" value="<?php echo $_POST["nome"]?>" <?php echo $errori["nome"]==1?"class='errore'":""; ?>/><br />
            Cognome:    <input type="text" name="cognome" value="<?php echo $_POST["cognome"]?>" <?php echo $errori["cognome"]==1?"class='errore'":""; ?>/><br />
            Username:   <input type="text" name="username" value="<?php echo $_POST["username"]?>" <?php echo $errori["username"]==1?"class='errore'":""; ?>/><br />
            Filiale:    <select name="filiale">
                        <?php
                            $rs = Database::getInstance()->eseguiQuery("SELECT id_filiale,nome FROM filiali;");
                            while(!$rs->EOF){
                                echo '<option value="'.$rs->fields["id_filiale"].'">'.$rs->fields["nome"].'</option>';

                            $rs->MoveNext();
                            }
                          ?>
                        </select>
        </pre>
        <input type="submit" value="Crea utente" class="bottCalendario">
    </form>
<?php
        }

?>
