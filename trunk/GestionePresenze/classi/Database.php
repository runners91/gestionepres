<?php
include("./adodb/adodb-exceptions.inc.php");
include("./adodb/adodb.inc.php");
/**
 * Esegue la connessione al database tramite ADODB
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */

class Database{
    private static $istanza = null;
    private $conn = null;
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "gestione_presenze";

    function __construct(){
        try{
            $this->conn = NewADOConnection('mysql');
            $this->conn->Connect($this->server, $this->username, $this->password, $this->db);
        }
        catch(Exception $e){
            print_r($e);
        }
    }

    static function getInstance(){
        if(!self::$istanza){
            self::$istanza = new Database();
        }
        return self::$istanza;
    }

    function getConnection(){
        return $this->conn;
    }

    /**
    * Esegue una query e ne ritorna il resultset
    * @param String $query Contiene la query di cui deve ritornare il ResultSet
    */
    function eseguiQuery($query,$ass = ADODB_FETCH_ASSOC){
        $rs = null;
        $this->conn->SetFetchMode($ass);
        try{
            $rs = $this->conn->Execute($query);
        }
        catch(Exception $e){
            print_r($e);
        }
        return $rs;
    }


}
?>