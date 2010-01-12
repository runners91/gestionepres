<?php
/**
 * Description of Evento
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Evento {
    private $id_evento;
    private $data_da;
    private $data_a;
    private $priorita;
    private $commento;
    private $stato;
    private $commento_segn;
    private $fk_dipendente;
    private $fk_causale;



    function __construct($data_da,$data_a,$priorita,$commento,$stato,$commento_segn,$fk_dipendente,$fk_causale,$id_evento = null){
        $this->id_evento     = $id_evento;
        $this->data_da       = $data_da;
        $this->data_a        = $data_a;
        $this->priorita      = $priorita;
        $this->commento      = $commento;
        $this->stato         = $stato;
        $this->commento_segn = $commento_segn;
        $this->fk_dipendente = $fk_dipendente;
        $this->fk_causale    = $fk_causale;
    }

    /**
     * Aggiorna lo stato dell'evento
     * @param int $stato stato che si vuole mettere
     * @param String $commento commento della segnalazione nel caso lo stato è 3
     */
    function aggiornaStato($stato,$commento = ""){
        return Database::getInstance()->eseguiQuery("UPDATE eventi set commento_segnalazione = '".$commento."', stato = ".$stato." WHERE id_evento = ".$this->id_evento.";");
    }

     /**
     * Elimina l'evento
     */
    function eliminaEvento(){
        if($id_evento)
            return Database::getInstance()->eseguiQuery("DELETE FROM eventi where id_evento = ".$this->id_evento.";");
        return false;
    }

    function aggiornaEvento($dataDa,$dataA,$tipo,$commento,$prio,$stato){
        return Database::getInstance()->eseguiQuery("UPDATE eventi SET data_da = ".Calendario::getTimestamp($dataDa).",data_a = ".Calendario::getTimestamp($dataA).",fk_causale = ".$tipo.",commento = '".$commento."',priorita = ".$prio.",stato = ".$stato." WHERE id_evento = ".$this->id_evento.";");
    }
     /**
     * Inserisce i dati dell'evento nel DataBase
     */
    function inserisciDatiEvento(){
        if($this->id_evento){
            $sql =  "update eventi set data_da = ".Calendario::getTimestamp($_POST['dataDa']).",data_a = ".Calendario::getTimestamp($_POST['dataA']).",fk_dipendente = ".$_POST['utente'].",fk_causale = ".$_POST['tipo'].",commento = '".$_POST['commento']."',priorita = ".$_POST['etichetta'];
            $sql .= " where id_evento = ".$this->id_evento;

            if (Database::getInstance()->getConnection()->execute($sql) === false) {
                echo 'Errore nell`aggiornamento: '.$conn->ErrorMsg().'<BR>';
            }
            else{
                echo "Evento salvato con successo !";
            }
            $href = "index.php?pagina=home&data=".$_GET['data']."&event=Y&id_evento=".$_GET['id_evento'];
            ?>
            <script language="javascript" type="text/javascript">
                window.setTimeout("redirect('<?php echo $href ?>')",2000);
            </script>
            <?php
        }
        else{
            $sql =  "insert into eventi(data_da,data_a,fk_dipendente,fk_causale,commento,priorita,stato) ";
            $sql .= "values (".Calendario::getTimestamp($this->data_da).",".Calendario::getTimestamp($this->data_a).",".$this->fk_dipendente.",".$this->fk_causale.",'".$this->commento."',".$this->priorita.",2);";

            if (Database::getInstance()->getConnection()->execute($sql) === false) {
                echo 'Errore nell`inserimento: '.$conn->ErrorMsg().'<BR>';
            }
            else{
                echo "Evento creato con successo !";
            }
            $href = "index.php?pagina=home&data=".$_GET['data'];
            ?>
            <script language="javascript" type="text/javascript">
                window.setTimeout("redirect('<?php echo $href ?>')",2000);
            </script>
            <?php
        }
    }

    function getTitoloForm(){
        if($this->id_evento){
            return "Evento Nr. ".$this->id_evento;
        }
        else{
            return "Nuovo evento";
        }
    }

    function getNomeBottone(){
        if($this->id_evento){
            return "Salva";
        }
        else{
            return "Crea";
        }
    }

    function getID(){
        return $this->id_evento;
    }
    function setID($id){
        $this->id_evento = $id;
    }
    function getDataDa(){
        return $this->data_da;
    }
    function setDataDa($data){
        $this->data_da = $data;
    }
    function getDataA(){
        return $this->data_a;
    }
    function setDataA($data){
        $this->data_a = $data;
    }
    function getCommento(){
        return $this->commento;
    }
    function setCommento($c){
        $this->commento = $c;
    }
    function getPriorita(){
        return $this->priorita;
    }
    function setPriorita($p){
        $this->priorita = $p;
    }
    function getDipendente(){
        return $this->fk_dipendente;
    }
    function setDipendente($d){
        $this->fk_dipendente = $d;
    }
    function getCausale(){
        return $this->fk_causale;
    }
    function setCausale($c){
        $this->fk_causale = $c;
    }
    function getStato(){
        return $this->stato;
    }
    function setStato($s){
        $this->stato = $s;
    }
    function getCommentoSegn(){
        return $this->commento_segn;
    }
    function setCommentoSegn($c){
        $this->commento_segn = $c;
    }
}
?>
