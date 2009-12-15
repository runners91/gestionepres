<?php

function creaMenuItem($paginaAttuale,$pagina){
    $class = "";
    if($paginaAttuale==$pagina){
        $class = " menuItemSelected";
    } ?>
    <td class="menuItem<?php echo $class ?>">
        <a class="menuLink" href="?pagina=<?php echo $pagina; ?>"><?php echo ucfirst($pagina); ?></a>
    </td>
<?php }

function creaMenu($pagina){
    if($pagina=="") $pagina = "home"; ?>

<table class="menu">
    <tr>
        <?php
            creaMenuItem($pagina,"home");
            creaMenuItem($pagina,"assenze");
            creaMenuItem($pagina,"festivi");
            creaMenuItem($pagina,"straordinari");
         ?>

        <td></td>

        <td class="messaggioUtente">
            <?php stampaMessaggio(); ?>
        </td>
    </tr>
</table>

<?php
}
?>
