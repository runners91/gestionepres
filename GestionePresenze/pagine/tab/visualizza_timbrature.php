<?php

    Calendario::stampaParametriCalendario($_POST['m'],false);

    $data = Calendario::getCalData($_POST['m']);
    $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
    $giorni = array(1=>'LU','MA','ME','GI','VE','SA','DO');

?>

    <table>
        <?php
            for($j=1;;$j++){
                $d = date("N",mktime(0,0,0,date("n",$data),$j,date("Y",$data)));
                if($d==7) $class = " fest"; else $class = "";

                echo '<tr>';
                    echo '<td class="giorno'.$class.'">';
                        echo $j." ".$giorni[$d];
                    echo '</td>';

                    $sql = "select data,stato from timbrature where fk_dipendente = ? and data > ? and data < ? order by data";
                    $rs = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente'],mktime(0,0,0,date("n",$data),$j,date("Y",$data)),mktime(23,59,59,date("n",$data),$j,date("Y",$data))));
                    $i = 0;
                    while(!$rs->EOF){
                        echo '<td class="giorno">';
                            if($rs->fields['stato']=="E" && $i%2==0)
                                echo date("H:i",$rs->fields['data']);
                            else if($rs->fields['stato']=="U" && $i%2==1)
                                echo date("H:i",$rs->fields['data']);
                        echo '</td>';
                        $rs->MoveNext();
                        $i++;
                    }





                echo '</tr>';
                if(date("n",mktime(0,0,0,date("n",$data),$j+1,date("Y",$data)))!=date("n",$data))
                    break;
            }
        ?>
    </table>
    
