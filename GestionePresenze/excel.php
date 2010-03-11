<?php
    session_start();
    include("classi/Database.php");
    include("classi/Evento.php");
    $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
    $anno = $_GET["anno"];
    $filename = "eventi_".$anno.".xls";
    header ("Content-Type: application/vnd.ms-excel");
    header ("Content-Disposition: inline; filename=$filename");

?>

<?php
    $array = explode("_", $_GET["assenze"]);
    unset($array[sizeof($array)-1]);
    foreach ($array as $val) {
        stampaTabella($val,$anno);
    }

    function stampaTabella($causale,$anno){
        $ut_fi = $_SESSION['id_utente'];
        echo $ut_fi;
        $query = "= ? ";
        if($_GET["filiale"]) {
            $query = "in (SELECT id_dipendente FROM dipendenti WHERE fk_filiale = ? ) ";
            $ut_fi = $_GET["filiale"];
        }
        $rs = Database::getInstance()->eseguiQuery("SELECT e.id_evento, e.data_da, e.data_a, e.durata,c.nome, CONCAT(d.nome,' ',d.cognome) as nomeD
                                                    FROM eventi e, dipendenti d, causali c
                                                    WHERE fk_causale = ? AND
                                                    (FROM_UNIXTIME(e.data_da,'%Y') = ? OR
                                                    FROM_UNIXTIME(e.data_a,'%Y') = ?) AND
                                                    e.fk_dipendente = d.id_dipendente AND
                                                    e.fk_causale = c.id_motivo AND
                                                    d.id_dipendente ".$query."
                                                    ORDER BY d.id_dipendente",array($causale,$anno,$anno,$ut_fi));
        if($rs->rowCount()>0){
?>
            <h3><?php echo $rs->fields["nome"]; ?></h3>
            <table border="1">
                <tr>
                    <th>data inizio</th>
                    <th>data fine</th>
                    <th>dipendente</th>
                    <th>Periodo</th>
                    <th>giorni lavorativi</th>
                </tr>
<?php
                while (!$rs->EOF) {
                    $e = new Evento();
                    $e->getValoriDB($rs->fields["id_evento"]);
                    echo "<tr>";
                        echo "<td>";
                            echo date("d.m.Y",$rs->fields["data_da"]);
                        echo "</td>";
                        echo "<td>";
                           echo date("d.m.Y",$rs->fields["data_a"]);
                        echo "</td>";
                        echo "<td>";
                            echo $rs->fields["nomeD"];
                        echo "</td>";
                        echo "<td>";
                            if($rs->fields["durata"]=="G")
                                echo "Giorno";
                            else if($rs->fields["durata"]=="M")
                                echo "Mattino";
                            else
                                echo "Pomeriggio";
                        echo "</td>";
                        echo "<td>";
                            echo $e->contaGiorni(2010);
                        echo "</td>";
                    echo "</tr>";
                    $rs->MoveNext();
                }
            echo "</table>";
        }
    }
?>