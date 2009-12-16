<?php
function creaSubmenuItem($paginaAttuale,$tabAttuale,$tab,$testo){

    if($tabAttuale==$tab){
        echo '<li id="selectedTab">';
        $preText = "";
        $postText = "";
    }
    else{
        echo '<li>';
        $preText = '<a href="?pagina='.$paginaAttuale.'&tab='.$tab.'">';
        $postText = '</a>';
    }

            echo $preText.$testo.$postText;
        echo '</li>';
}