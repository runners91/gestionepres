<?php
    session_start();
    if($_GET['azione'] == "logout"){
        session_destroy();
        header( 'Location: index.php' ) ;
    }
    include("classi/Assenze.php");
    include("classi/Database.php");
    include("login.php");
    include("pagine/navigazione/menu.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Gestione Presenze</title>
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div id="pagina">
            <div id="header">
                <div class="mainTitle">Gestione Presenze</div>
               <?php
               if(isset($_SESSION['username'])){
                   creaMenu($_GET['pagina']);
               }?>
            </div>
            <div id="corpo">
               <?php
                    if(!isset($_SESSION['username'])){
                        login();
                    }
                    else if(!$_GET){
                        include("pagine/home.php");
                    }
                    else{
                        include("pagine/".$_GET['pagina'].".php");
                    }
               ?>
            </div>
        </div>
        <div id="footer">
            Copyright &copy 2010 by Bryan & Ethan
        </div>
    </body>
</html>
