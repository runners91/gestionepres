<?php
include("classi/Utilita.php");
/**
 * Gestione delle assenze.
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 * @package classi
 */

class Assenze {
    private $data;
    private $quantita;
    private $dipendente;
    private $motivo;
    private $errori = array();

    function  __construct() {

    }

    function setData($d){
        $this->data = $d;
    }
    function getData(){
        return $this->data;
    }
    function setQuantita($q){
        $this->quantita = $q;
    }
    function getQuantita(){
        return $this->quantita;
    }
    function setMotivo($m){
        $this->motivo = $m;
    }
    function getMotivo(){
        return $this->motivo;
    }
    function setDipendente($d){
        $this->dipendente = $d;
    }
    function getDipendente(){
        return $this->dipendente;
    }

    /**
     * Aggiunge un errore all'array nella posizione d, ovvero dove e' avvenuto l'errore
     * @param String $errore Descrizione dell'errore
     * @param String $d Indica dove e' successo l'errore
     */
    function aggiungiErrore($errore, $d){
        $this->errori[$d]  = $errore;
    }
    function getErrori(){
        return $this->errori;
    }

     /**
     * Nel caso in cui non ci sono errori tenta l'inserimento dell'assenza nel DB
     * ritorna l'array di errori se ci sono errori e un messaggio in caso di fallimento del comando insert
     */
    function inserisciAssenza(){
        if(count($this->errori)==0){
            try{
                Database::getInstance()->insert('assenze',array($data,$quantita,$dipendente,$motivo));
            }
            catch(Exception $e){
                return array("Errore di inserimento");
            }
        }
        else{
            return $this->errori;
        }
    }

    /**
     * Stampa un report contenente tutte le assenze
     */
    static function stampaReportAssenze(){ ?>
        <div class="standartFormContainer">
        <?php
        $rs = Database::getInstance()->eseguiQuery("select id_assenza as ID,data as Data,quantita as Quantita from assenze");
        Utilita::stampaTabella($rs);
        ?>
        </div> <?php
    }

    static function inserisciReportInserimento(){ ?>
        <div class="standartFormContainer">
            <form name="loginForm" method="POST" action="<?php echo $_SERVER[PHP_SELF] ?>">
                <table class="oggettoContenente">
                    <tr>
                        <td align="right">
                            Data:
                        </td>
                        <td>
                            <input type="textfield" class="standartTextfield" name="data" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            Quantit&agrave:
                        </td>
                        <td>
                            <input type="textfield" class="standartTextfield" name="quantita" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            Dipendente:
                        </td>
                        <td>
                            <input type="textfield" class="standartTextfield" name="dipendente" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            Motivo:
                        </td>
                        <td>
                            <input type="textfield" class="standartTextfield" name="motivo" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="button" value="INSERISCI" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <?php
    }
}

?>
