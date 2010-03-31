<?php

function creaMenuItem($paginaAttuale,$pagina){
    if(Autorizzazione::verificaAccesso($pagina)){
        $class = "";
        
        if($paginaAttuale==$pagina){
            $class = " menuItemSelected";
        } ?>
        <td class="menuItem<?php echo $class ?>">
            <?php echo '<a class="menuLink" href="?pagina='.$pagina.'">'.ucfirst($pagina).'</a>'; ?>
        </td>
<?php 
    }
}

function creaMenu($pagina){
    if($pagina=="") $pagina = "home"; ?>

<div class="messaggioUtente">
    <?php
        $d = new Dipendente();
        $d->trovaUtenteDaId($_SESSION["id_utente"]);
        stampaMessaggio($d);
    ?>
</div>
<div id="stato">
    <table>
        <tr>
            <td onclick="cambiaStato(1,'');">
                <img src="./img/stato1.png" alt="disp."/> Disponibile
            </td>
        </tr>
        <tr>
            <td onclick="cambiaStato(2,'Occupato');">
                <img src="./img/stato2.png" alt="occ."/> Occupato
            </td>
        </tr>
        <tr>
            <td onclick="cambiaStato(3,'Non al computer');">
                <img src="./img/stato3.png" alt="non al pc."/> Non al computer
            </td>
        </tr>
    </table>
    <font id="testoStato" onclick="nascondiStati(<?php echo strlen(trim($d->commento_stato))>0;?>);">[chiudi]</font>
</div>
<div id="modificaCommento">
        <form method="POST" name="cambiaSts">
            <input type="hidden" name="stato" id="nuovoStato" />
            <input type="hidden" name="azione" value="cambiaStato" />
            <input type="text" id="commento_stato" maxlength="35" name="commento_stato" />
            <font id="testoStato"><span onclick="cambiaSts.submit();">Salva</span></font>
        </form>
</div>

<div id="vediCommento" style="<?php echo strlen(trim($d->commento_stato))==0?"display:none;":""; ?>">
    <?php if(strlen($d->commento_stato)>0){ ?>
    <span class="puntatore" onclick="cambiaCommento(<?php echo $d->stato.",'".$d->commento_stato."'"; ?>)">
        <font style="font-size:11px">
            <b><?php echo $d->commento_stato; ?></b>
        </font>
    </span>
    <?php }?>
</div>

<table class="menu">
    <tr>
        <?php
            creaMenuItem($pagina,"home");
            creaMenuItem($pagina,"amministrazione");
            creaMenuItem($pagina,"utente");
            creaMenuItem($pagina,"statistiche");
            
         ?>
        <td></td>
    </tr>
</table>

<?php
}
?>
