<?php

function creaMenuItem($paginaAttuale,$pagina){
    $class = "";
    $preText = '<a class="menuLink" href="?pagina='.$pagina.'">';
    $postText = '</a>';

    if($paginaAttuale==$pagina){
        $class = " menuItemSelected";
        $preText = "";
        $postText = "";
    } ?>
    <td class="menuItem<?php echo $class ?>">
        <?php echo $preText.ucfirst($pagina).$postText; ?>
    </td>
<?php }

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
