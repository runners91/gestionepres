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
        <?php creaMenuItem($pagina,"home")?>
        <?php creaMenuItem($pagina,"assenze")?>
        <?php creaMenuItem($pagina,"festivi")?>
        <?php creaMenuItem($pagina,"straordinari")?>

        <td>
            <div class="mainTitle">Gestione Presenze</div>
        </td>

        <td class="messaggioUtente">
            <?php stampaMessaggio(); ?>
        </td>
    </tr>
</table>

<?php
}
?>
