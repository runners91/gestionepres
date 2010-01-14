<?php
    if($_POST){
        $vecchiaPwd = trim($_POST["vecchiaPwd"]);
        $nuovaPwd = trim($_POST["nuovaPwd"]);
        if(strlen($vecchiaPwd)>0){
            $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as valida FROM dipendenti WHERE password = md5('".$vecchiaPwd."') AND username = '".$_SESSION['username']."';");
            if(!$rs->fields["valida"]==1)
                $errori["vecchiaPwd"] = " - la password non &egrave; corretta";
            else {
                if(strlen($nuovaPwd)>0){
                    if($nuovaPwd != trim($_POST["confermaPwd"]))
                        $errori["confermaPwd"] = " - le password non corrispondono";
                    else if($nuovaPwd == "inizio")
                        $errori["nuovaPwd"] =  "- la password deve essere diversa da \"inizio\"";
                    else{
                        Database::getInstance()->eseguiQuery("UPDATE dipendenti SET password = md5('".$nuovaPwd."') WHERE username = '".$_SESSION['username']."';");
                        echo "<b>la password &egrave stata modificata con successo</b>";
                    }
                }
                else
                    $errori["nuovaPwd"] = " - la password non è valida";
            }
        }
    }
    else
        if($_GET["login"]==1)
            echo "<b>Devi modificare la password </b>"
?>
<form action="#" method="POST">
    <table>
        <tr>
            <td class="label">Vecchia password:</td>
            <td><input type="password" name="vecchiaPwd" <?php echo isset($errori["vecchiaPwd"])?"class='errore'":""; ?> /></td>
            <td class="messaggioErrore"><?php echo $errori["vecchiaPwd"]; ?></td>
        </tr>
        <tr>
            <td class="label">Nuova password:</td>
            <td><input type="password" name="nuovaPwd" <?php echo isset($errori["nuovaPwd"]) || isset($errori["confermaPwd"])?"class='errore'":""; ?> /></td>
            <td class="messaggioErrore"><?php echo $errori["nuovaPwd"]; ?></td>
        </tr>
        <tr>
            <td class="label">Conferma Password:</td>
            <td><input type="password" name="confermaPwd" <?php echo isset($errori["confermaPwd"])?"class='errore'":""; ?> /></td>
            <td class="messaggioErrore"><?php echo $errori["confermaPwd"]; ?></td>
        </tr>
    </table><br />
    <input type="submit" value="Salva" class="bottCalendario"/>
</form>