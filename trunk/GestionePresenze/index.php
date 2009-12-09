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
           <div class="mainTitle"><img class="titleImg" src="img/titolo.png" alt="titolo" /></div>
           <?php if(isset($_SESSION['username'])){ ?>
           <table class="menu">
               <tr>
                   <td class="menuItem">
                        <nobr><a class="menuLink" href="?page=item1">Item 1</a></nobr>
                   </td>
                   <td class="menuItem">
                   <nobr><a class="menuLink" href="#">Item 2</a></nobr>
                   </td>
                   <td class="menuItem">
                       <nobr><a class="menuLink" href="#">Item 3</a></nobr>
                   </td>
                   <td class="menuItem">
                       <nobr><a class="menuLink" href="#">Item 4</a></nobr>
                   </td>
                   <td class="menuItem">
                       <nobr><a class="menuLink" href="#">Item 5</a></nobr>
                   </td>
                   <td class="menuSpace">
                       
                   </td>
                   <td class="messaggioUtente">
                       <?php stampaMessaggio(); ?>
                   </td>
               </tr>
           </table>
           <?php } ?>
        </div>
        <div id="corpo">
            <table class="contenuto">
                <tr>
                    <td colspan="2" align="center">
                        <?php
                            login();
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="center"> <?php
                        if(isset($_SESSION['username'])){
                            Assenze::stampaReportAssenze();
                        }?>
                    </td>
                    <td align="center"> <?php
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