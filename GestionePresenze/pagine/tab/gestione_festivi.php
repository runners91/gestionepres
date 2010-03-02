<table>
    <tr>
        <td style="float:left" valign="top">
<?php
    if($_POST){
        $data = $_POST["data"];
        $f = new Festivo();
        $f->setNome($_POST["nome"]);
        $f->setRicorsivo(isset($_POST["ricorsivo"]));
        $f->setData($data);
        $f->setFiliale($_POST["filiale"]);
        $f->setDurata($_POST["durata"]);
        
        if(!$f->insersciFestivo() && sizeof($f->getErrori())>0)
            stampaFormAggiungiFestivo($f, $f->getErrori());
        else  {
            stampaFormAggiungiFestivo(new Festivo());
            $successo = 'Il festivo &egrave; stato aggiunto con successo';
        }
        
    }
    else {
        stampaFormAggiungiFestivo(new Festivo());
    }
?>
        </td>
        <td style="width:30px;"></td>
        <td valign="top" ><div class="messaggioTaskOk"><?php echo $successo; ?> </div></td>
    </tr>
</table>
<?php
    function stampaFormAggiungiFestivo($f,$e=null){
?>
        <form method="POST">
            <table>
                <tr>
                    <td>
                        Nome:
                    </td>
                    <td>
                        <input type="text" name="nome" <?php echo isset($e["nome"])?"class='errore'":""; ?> value="<?php echo $f->getNome(); ?>"/>
                    </td>
                    <td class="messaggioErrore">
                        <?php
                            echo $e["nome"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Data:
                    </td>
                    <td>
                        <input id="sel1" type="text" name="data" <?php echo isset($e["data"])?"class='errore'":""; ?> value="<?php echo date("d.m.Y");?>" maxlength="10"/>
                    </td>
                    <td>
                        <input value="" type="reset" onclick="return showCalendar('sel1', '%d.%m.%Y');" class="imgCal" />
                        <span class="messaggioErrore">
                            <?php
                                echo $e["data"];
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Durata:
                    </td>
                    <td>
                        <select name="durata">
                            <option value="G">Giornata</option>
                            <option value="M">Mattina</option>
                            <option value="P">Pomeriggio</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Filiale:
                    </td>
                    <td>
                        <select name="filiale">
                            <?php
                                $rs = Database::getInstance()->eseguiQuery("SELECT id_filiale,nome FROM filiali");
                                while(!$rs->EOF){
                                    echo '<option value="'.$rs->fields["id_filiale"].'">'.$rs->fields["nome"].'</option>';
                                    $rs->MoveNext();
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Ricorsivo:
                    </td>
                    <td>
                        <input type="checkbox" name="ricorsivo" <?php echo $f->isRicorsivo()?"checked":""; ?>/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="bottCalendario" type="submit" value="Aggiungi">
                    </td>
                </tr>
            </table>
        </form>
<?php
    }
?>