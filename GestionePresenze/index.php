<?php
    session_start();
    include("classi/Assenze.php");
    include("classi/Database.php");
    include("pagine/login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Gestione Presenze</title>
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body OnLoad="document.loginForm.username.focus();">
        <div id="header">
            <table class="header">
                <tr>
                    <td class="header" align="center">
                        <div class="mainTitle">Gestione Presenze</div>
                    </td>
                    <td class="header" align="right">
                        <div><?php if(isset($_SESSION['username'])) stampaMessaggio(); ?></div>
                    </td>
                </tr>
            </table>
            
        </div>
        <div id="corpo">
            <table class="contenuto"> <?php
                if(!isset($_SESSION['username'])){ ?>
                    <tr>
                        <td colspan="2" align="center">
                             <?php
                                login();
                             ?>
                        </td>
                    </tr> <?php
                } ?>
                <tr>
                    <td> <?php
                        if(isset($_SESSION['username'])){
                            Assenze::stampaReportAssenze();
                        }?>
                    </td>
                    <td> <?php
                        if(isset($_SESSION['username'])){
                            Assenze::inserisciReportInserimento();
                        }?>
                    </td>
                </tr>
            </table>
        </div>
        <div id="footer">
            Copyright &copy 2010 by Bryan & Ethan
        </div>
    </body>
</html>