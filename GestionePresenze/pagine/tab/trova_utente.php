<?php
    $utente = $_POST["username"];
    stampaFormRicerca($utente);
    if($utente) {
        $d = new Dipendente();
        $d->trovaUtenteDaUsername($utente);
        stampaInfoUtente($d);
    }
?>

<?php function stampaFormRicerca($utente){ ?>
    <!--form method="POST">
        <select name="utente" onchange="this.form.submit();">
            <option value="0">-</option>
            <?php
               /* $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente,username FROM dipendenti");
                while(!$rs->EOF){
                    $selected="";
                    $id = $rs->fields["id_dipendente"];
                    if($utente==$id)
                        $selected="selected";
                    echo '<option value="'.$id.'" '.$selected.'>'.$rs->fields["username"].'</option>';
                    $rs->MoveNext();
                }*/
            ?>
        </select-->
        <form method="POST" name="formCercaUtente">
            <input type="text" name="username" onkeyup="utenti()" id="username" />
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