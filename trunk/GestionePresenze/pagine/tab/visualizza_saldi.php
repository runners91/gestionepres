<?php

    $d = new Dipendente();
    $d = $d->trovaUtenteDaUsername($_SESSION['username']);
    $saldo = $d->getSaldo();
        
    $sql = "update saldi set saldo = ? where fk_dipendente = ?";
    $rs = Database::getInstance()->eseguiQuery($sql,array($saldo,$_SESSION['id_utente']));

    $sql = "select CONCAT(d.nome,' ',d.cognome) as Nome,s.saldo as Saldo,s.saldo_strd as 'Saldo straordinario',s.vac_spt as 'Vacanze spettanti',s.vac_matr as 'Vacanze maturate',s.vac_rst as 'Vacanze restanti' from dipendenti d,saldi s where s.fk_dipendente = d.id_dipendente and s.fk_dipendente = ?";
    $rs = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente']));
?>

    <table>
        <tr>
            <td>
                <?php Utilita::stampaTabella($rs); ?>
            </td>
            <td>
                <div class="aggiungiEventoContainer">
                    <form action="#" method="POST">
                        <?php
                            if(isset($_POST['data'])){
                                $t = new Timbratura();
                                $t->setDipendente($_SESSION['id_utente']);
                                $t->setData($_POST['data']);
                                $t->inserisciTimbratura();
                            }
                        ?>
                        <table>
                            <tr>
                                <td colspan="2" class="cellaTitoloTask">
                                    Inserisci timbrature
                                </td>
                            </tr>
                            <tr>
                                <td class="label">
                                    Orario:
                                </td>
                                <td>
                                    <input id="sel1" <?php echo isset($t->errori["data"])?"class='errore'":""; ?> type="text" name="data" value="<?php if(isset($_POST['data'])) echo $_POST['data']; else echo date("d.m.y H:i",time()); ?>" />
                                </td>
                                <td>
                                    <input value="" type="reset" onclick="return showCalendar('sel1','%d.%m.%Y %H:%M','true');" class="imgCal" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span class="messaggioErrore"><?php echo $t->errori["data"]; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="messaggioTaskOk" colspan="3">
                                    <?php echo $t->errori['timbratura'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" class="bottCalendario" value="Inserisci timbratura" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </td>
        </tr>
    </table>





