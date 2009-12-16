<?php
function creaSubmenuItem($paginaAttuale,$tabAttuale,$tab,$testo){

    if($tabAttuale==$tab)
        echo '<li id="selectedTab">';
    else
        echo '<li>';

    echo '<a href="?pagina='.$paginaAttuale.'&tab='.$tab.'">'.$testo.'</a>';
    
    echo '</li>';
}