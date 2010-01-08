<?php
/**
 * Description of Filtro
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Filtro {
    private $nome;
    private $parametri;

    function  __construct($n){
        $this->nome = $n;
    }

    /**
     * Aggiungo un parametro all'array di parametri, specidicando come si chiama il suo valore e se e' nullo
     *
     * @param String $name
     * @param String $value
     * @param ? $nullValue
     */
    function addParam($nome,$val){
        if(!$val) $valore = 0; else $valore = $val;
        $this->parametri[] = array($nome,$valore);
    }

    /**
     * Ritorna il valore del parametro scelto
     * @param int $i indica il nome del parametro che si desidera
     */
     function getParamValue($name){
        foreach($this->parametri as $value){
            if($value[0]==$name){
                return $value[1];
            }
        }
        return 0;
     }
     
}
?>
