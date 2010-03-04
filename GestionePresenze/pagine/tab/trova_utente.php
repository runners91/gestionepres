<?php
    $utente = trim($_POST["username"]);
    stampaFormRicerca($utente);
    if($utente) {
        $d = new Dipendente();
        $d->trovaUtenteDaUsername($utente);
        if($d->username)
            stampaInfoUtente($d);
        else {
            $rs = Database::getInstance()->eseguiQuery("SELECT username FROM dipendenti WHERE username like ?",array("%".$utente."%"));
            if($rs->rowCount()>0){
                echo "Utenti trovati:";
                while(!$rs->EOF) {
                    $username = $rs->fields["username"];
                    echo '<br /><a href="javascript: cercaUtente(\''.$username.'\')">'.$username.'</a>';
                    $rs->MoveNext();
                }
            }
            else
                echo "Nessun utente trovato";
        }
    }
?>

<?php function stampaFormRicerca($utente){ ?>
        <form method="POST" name="formCercaUtente">
            <input type="text" name="username" onkeyup="utenti()" id="username" autocomplete="off"/>
            <input type="submit" value="cerca">
            <div id="listaUtenti"></div>
        </form>
    </form>
<?php } 

function stampaInfoUtente($d){
    $stato = $d->getStatoOggi(Autorizzazione::gruppoAmministrazione($_SESSION["username"]));
?>
    <table>
        <tr>
            <td> <img src="./img/stato<?php echo $stato['stato'];?>.png"> </td>
            <td> <?php echo $stato['commento'];?></td>
        </tr>
        <tr>
            <td class="label"> Nome: </td>
            <td> <?php echo $d->nome;?> </td>
        </tr>
        <tr>
            <td class="label"> Cognome: </td>
            <td> <?php echo $d->cognome;?> </td>
        </tr>
        <tr>
            <td class="label"> Username: </td>
            <td> <?php echo $d->username;?> </td>
        </tr>
        <tr>
            <td class="label"> Email: </td>
            <td> <?php echo $d->email;?> </td>
        </tr>
        <tr>
            <td class="label"> Telefono: </td>
            <td> <?php echo $d->telefono;?> </td>
        </tr>
        <tr>
            <td class="label"> Natel: </td>
            <td> <?php echo $d->natel;?> </td>
        </tr>
    </table>

<?php } ?>