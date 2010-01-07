<?php
    session_start();
    if($_GET['azione'] == "logout"){
        session_destroy();
        header('Location:index.php');
    }
    include("classi/Assenze.php");
    include("classi/Database.php");
    include("classi/Dipendente.php");
    include("classi/Calendario.php");
    include("login.php");
    include("pagine/navigazione/menu.php");

    if(isset($_SESSION['username']) && isset($_GET['pagina']))
        if(!Utilita::verificaAccesso($_GET['pagina']))
            header('Location:index.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Gestione Presenze</title>
        <link rel="stylesheet" href="style.css" type="text/css" />
        <link rel="stylesheet" type="text/css" media="all" href="script/mini_calendario/skins/aqua/theme.css" title="Aqua" />
        <script type="text/javascript" src="script/util.js"></script>
        <script type="text/javascript" src="script/mini_calendario/calendar.js"></script>
        <script type="text/javascript" src="script/mini_calendario/calendar-it.js"></script>
        <script type="text/javascript" src="script/mini_calendario/script.js"></script>
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
