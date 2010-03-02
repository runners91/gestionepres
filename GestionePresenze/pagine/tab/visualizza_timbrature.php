<?php

    if(isset($_GET['elimina'])){
        $t = new Timbratura();
        $t->getValoriDB($_GET['elimina']);
        $t->eliminaTimbratura($_GET['elimina']);
    }


    Calendario::stampaParametriCalendario($_POST['m'],false,null,"http://localhost/GestionePresenze/index.php?pagina=utente&tab=visualizza_timbrature");

    $data = Calendario::getCalData($_POST['m']);
    $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
    $giorni = array(1=>'LU','MA','ME','GI','VE','SA','DO');
    $causale = "";
?>
    <table class="timbrature">
        <tr>
            <th align="center">
                Giorno
            </th>
            <th align="center">
                Causale
            </th>
            <th align="center">
                Tot.
            </th>
            <th align="center">
                Monte
            </th>
            <td class="cellaSpazio">
            </td>
            <th colspan="4" align="center">
                Timbrature
            </th>
            <td class="cellaSpazio"></td>
            <td colspan="5">
                <?php
                    if($t->errori['eliminaNok']) echo '<div id="erroreTimbratura" class="erroreTimbratura">'.$t->errori['eliminaNok'].'</div>';
                    if($t->errori['eliminaOk']) echo '<div id="successoTimbratura" class="successoTimbratura">'.$t->errori['eliminaOk'].'</div>';
                ?>
                <input id="bottElimina" type="button" value="elimina" class="bottPaginazione" style="display:none" onclick="redirect('?pagina=utente&tab=visualizza_timbrature&data=<?php echo $data; ?>&elimina='+document.getElementById('elimina').value)" />
                <input id="elimina" type="hidden" name="elimina"  /> <!-- campo contenente l'id della timbratura da eliminare -->
            </td>
        </tr>
        <?php
            for($j=1;;$j++){
                $tot = 0;
                $d = date("N",mktime(0,0,0,date("n",$data),$j,date("Y",$data)));
                if($d==7 || $d==6) $class = " fest"; else $class = "";
                if(date("j.n.Y",mktime(0,0,0,date("n",$data),$j,date("Y",$data)))==date("j.n.Y",time())) $classTr .= " cellaDataOggi"; else $classTr = "";

                echo '<tr class="rigaGriglia'.$classTr.'">';
                    // cella del giorno
                    echo '<td class="giorno'.$class.'">';
                        echo $j." ".$giorni[$d];
                    echo '</td>';

                    if($d!=7 && $d!=6){
                        // cella causale
                        $sql = "select c.nome from eventi e,causali c where fk_dipendente = ? and c.id_motivo = e.fk_causale and e.data_da <= ? and e.data_a >= ? order by e.priorita desc limit 1";
                        $dataGiorno = mktime(0,0,0,date("n",$data),$j,date("Y",$data));
                        $rs  = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente'],$dataGiorno,$dataGiorno));
                        echo '<td class="griglia">';
                            echo $rs->fields['nome'];
                            $causale = $rs->fields['nome'];
                            if(strlen($causale)>0) $tot = 28800; /* se ce una causale 8 ore vengono messe auto */
                        echo '</td>';

                        // cella totale ore giornaliere
                        $sql = "select id_timbratura,data,stato from timbrature where fk_dipendente = ? and data > ? and data < ? order by data";
                        $rs = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente'],mktime(0,0,0,date("n",$data),$j,date("Y",$data)),mktime(23,59,59,date("n",$data),$j,date("Y",$data))));
                        $i = 0;
                        while(!$rs->EOF){
                            if($i==0) $inizio = (int)$rs->fields['data'];
                            if($rs->fields['stato']=="E" && $i%2==0)
                                $e = (int)$rs->fields['data'];
                            else if($rs->fields['stato']=="U" && $i%2==1){
                                $tot = $tot + (int)$rs->fields['data'] - $e;
                                $fine = (int)$rs->fields['data'];
                            }
                            $rs->MoveNext();
                            $i++;
                        }
                        if(($fine-$inizio)-$tot<3600 && $tot>0 && ($fine-$inizio)>0){ /* se non è stata fatta 1 ora di pausa viene tolta automaticamente */
                            $tot = ($fine-$inizio)-3600;
                            if(strlen($causale)>0) $tot += 28800; /* 8 ore causale */
                        }

                        if($tot>=28800){ /* 8 ore */
                            echo '<td class="griglia">';
                                echo '8.00';
                            echo '</td>';
                        }
                        else{
                            echo '<td class="griglia negativo">';
                                echo Utilita::oreMinDaSec($tot);
                            echo '</td>';
                        }


                        // cella monte
                        echo '<td class="griglia">';
                            echo Utilita::oreMinDaSec($tot-28800);
                        echo '</td>';

                        // cella di spaziatura
                        echo '<td class="cellaSpazio">';
                        echo '</td>';

                        // celle delle timbrature
                        $rs = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente'],mktime(0,0,0,date("n",$data),$j,date("Y",$data)),mktime(23,59,59,date("n",$data),$j,date("Y",$data))));
                        $i = 0;
                        while(!$rs->EOF){
                            if($rs->fields['stato']=="E" && $i%2==0){
                                echo '<td class="griglia entrata" onclick="eliminaTimbratura(this,'.$rs->fields['id_timbratura'].')">';
                                    echo date("H:i",$rs->fields['data'])." E";
                                echo '</td>';
                            }
                            else if($rs->fields['stato']=="U" && $i%2==1){
                                echo '<td class="griglia uscita" onclick="eliminaTimbratura(this,'.$rs->fields['id_timbratura'].')">';
                                    echo date("H:i",$rs->fields['data'])." U";
                                echo '</td>';
                            }
                            else{
                                echo '<td class="griglia errore" onclick="eliminaTimbratura(this,'.$rs->fields['id_timbratura'].')">';
                                    echo date("H:i",$rs->fields['data'])." ".$rs->fields['stato'];
                                echo '</td>';
                            }
                            $rs->MoveNext();
                            $i++;
                        }
                    }   

                echo '</tr>';
                if(date("n",mktime(0,0,0,date("n",$data),$j+1,date("Y",$data)))!=date("n",$data)) // tutto il mese
                    break;
                else if(mktime(0,0,0,date("n",$data),$j+1,date("Y",$data))>time()) // oppure fino ad oggi
                    break;                    
            }
        ?>
    </table>
    
