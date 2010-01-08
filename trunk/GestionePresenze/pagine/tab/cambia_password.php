<?php
    if($_POST){
        $vecchiaPwd = trim($_POST["vecchiaPwd"]);
        $nuovaPwd = trim($_POST["nuovaPwd"]);
        if(strlen($vecchiaPwd)>0){
            $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as valida FROM dipendenti WHERE password = md5('".$vecchiaPwd."') AND username = '".$_SESSION['username']."';");
            if(!$rs->fields["valida"]==1)
                $errori["vecchiaPwd"] = 1;

        }
        if(strlen($nuovaPwd)>0){
            if($nuovaPwd != trim($_POST["confermaPwd"]))
                $errori["confermaPwd"] = 1;
            else if($nuovaPwd == "inizio")
                $errori["nuovaPwd"] = 2;
            else{
                Database::getInstance()->eseguiQuery("UPDATE dipendenti SET password = md5('".$nuovaPwd."') WHERE username = '".$_SESSION['username']."';");
                echo "<b>la password &egrave stata modificata con successo</b>";
            }
        }
        else
            $errori["nuovaPwd"] = 1;
    }
    if($_GET["login"]==1)
        echo "<b>Devi modificare la password </b>"
?>
<form action="#" method="POST">
    <pre>
    Vecchia password:   <input type="password" name="vecchiaPwd" <?php echo $errori["vecchiaPwd"]==1?"class='errore'":""; ?> /><span class="messaggioErrore"><?php echo $errori["vecchiaPwd"]==1?" - la password non &egrave; corretta":""; ?></span><br />
    Nuova password:     <input type="password" name="nuovaPwd" <?php echo $errori["nuovaPwd"]==1 || $errori["confermaPwd"]==1?"class='errore'":""; ?> /><span class="messaggioErrore"><?php if($errori["nuovaPwd"]==1) echo " - la password non è valida"; else if($errori["nuovaPwd"]==2) echo "-la password deve essere diversa da \"inizio\"";?></span><br />
    Conferma Password:  <input type="password" name="confermaPwd" <?php echo $errori["confermaPwd"]==1?"class='errore'":""; ?> /><span class="messaggioErrore"><?php echo $errori["confermaPwd"]==1?" - le password non corrispondono":""; ?></span><br />
    <input type="submit" value="Modifica Password" class="bottCalendario"/>
    </pre>
</form>