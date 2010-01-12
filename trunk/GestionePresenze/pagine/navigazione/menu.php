<?php

function creaMenuItem($paginaAttuale,$pagina){
    if(Utilita::verificaAccesso($pagina)){
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
    <?php stampaMessaggio(); ?>
</div>

<table class="menu">
    <tr>
        <?php
            creaMenuItem($pagina,"home");
            creaMenuItem($pagina,"amministrazione");
            creaMenuItem($pagina,"statistiche");
            creaMenuItem($pagina,"utente")
         ?>
        <td></td>
    </tr>
</table>

<?php
}
?>
