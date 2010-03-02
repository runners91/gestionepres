<?php
    $utente = $_POST["username"];
    stampaFormRicerca($utente);
    if($utente) {
        $d = new Dipendente();
        $d->trovaUtenteDaUsername($utente);
        if($d->username)
            stampaInfoUtente($d);
        else
            echo "Nessun utente trovato";
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
    </table>

<?php } ?>