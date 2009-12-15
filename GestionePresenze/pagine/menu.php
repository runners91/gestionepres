<?php

function creaMenu($pagina){
    if($pagina=="") $pagina = "home.php"; ?>

<table class="menu">
    <tr>
        <?php if($pagina=="home.php"){ ?>
            <td class="menuItem menuItemSelected">
        <?php } else{ ?>
            <td class="menuItem"> <?php } ?>
                <a class="menuLink" href="?pagina=home">Home</a>
            </td>

        <?php if($pagina=="assenze.php"){ ?>
            <td class="menuItem menuItemSelected">
        <?php } else{ ?>
            <td class="menuItem"> <?php } ?>
                <a class="menuLink" href="?pagina=assenze">Assenze</a>
                </td>

        <?php if($pagina=="festivi.php"){ ?>
            <td class="menuItem menuItemSelected">
        <?php } else{ ?>
            <td class="menuItem"> <?php } ?>
                <a class="menuLink" href="?pagina=festivi">Festivi</a>
            </td>

        <?php if($pagina=="straordinari.php"){ ?>
            <td class="menuItem menuItemSelected">
        <?php } else{ ?>
            <td class="menuItem"> <?php } ?>
                <a class="menuLink" href="?pagina=straordinari">Straordinari</a>
            </td>

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
