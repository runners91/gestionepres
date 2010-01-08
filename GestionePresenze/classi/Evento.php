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
    private $fk_dipendente;
    private $fk_causale;



    function __construct($id_evento = null){
        $this->id_evento = $id_evento;
        if($this->id_evento){
            $sql = "SELECT * FROM eventi WHERE id_evento = ".$this->id_evento;
            $rs = Database::getInstance()->eseguiQuery($sql);
            while(!$rs->EOF){
                $this->data_da       = $rs->fields['data_da'];
                $this->data_a        = $rs->fields['data_a'];
                $this->priorita      = $rs->fields['priorita'];
                $this->commento      = $rs->fields['commento'];
                $this->fk_dipendente = $rs->fields['fk_dipendente'];
                $this->fk_causale    = $rs->fields['fk_causale'];
                $rs->MoveNext();
            }
        }
        else{
            $this->data_da       = $_POST['dataDa'];
            $this->data_a        = $_POST['dataA'];
            $this->priorita      = $_POST['etichetta'];
            $this->commento      = $_POST['commento'];
            $this->fk_dipendente = $_POST['utente'];
            $this->fk_causale    = $_POST['tipo'];
        }
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
        }
        else{
            $sql =  "insert into eventi(data_da,data_a,fk_dipendente,fk_causale,commento,priorita) ";
            $sql .= "values (".Calendario::getTimestamp($this->data_da).",".Calendario::getTimestamp($this->data_a).",".$this->fk_dipendente.",".$this->fk_causale.",'".$this->commento."',".$this->priorita.");";

            if (Database::getInstance()->getConnection()->execute($sql) === false) {
                echo 'Errore nell`inserimento: '.$conn->ErrorMsg().'<BR>';
            }
            else{
                echo "Evento creato con successo !";
            }
            $href = "index.php?pagina=home&data=".$_GET['data']; ?>

            <script language="javascript" type="text/javascript">
                window.setTimeout("redirect('<?php echo $href ?>')",1000);
            </script><?php
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
}
?>
