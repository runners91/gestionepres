<?php

    Calendario::stampaParametriCalendario($_POST['m'],false);

    $data = Calendario::getCalData($_POST['m']);
    $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
    $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');

?>

<div id="calendario">
    <table>
        <?php
            for($j=1;;$j++){
                $d = date("N",mktime(0,0,0,date("n",$data),$j,date("Y",$data)));
                if($d==7) $class = " fest"; else $class = "";

                echo '<tr>';
                    echo '<td class="giorno">';
                        echo $j;
                    echo '</td>';
                    echo '<td class="giorno'.$class.'">';
                        echo $giorni[$d];
                    echo '</td>';
                echo '</tr>';
                if(date("n",mktime(0,0,0,date("n",$data),$j+1,date("Y",$data)))!=date("n",$data))
                    break;
            }
        ?>
    </table>
    
