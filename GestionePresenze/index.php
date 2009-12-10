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
    <body>
        <div id="pagina">
        <div id="header">
           <?php if(isset($_SESSION['username'])){ ?>
           <table class="menu">
               <tr>
                   <td class="menuItem">
                        <a class="menuLink" href="#S">Item 1</a>
                   </td>
                   <td class="menuItem">
                       <a class="menuLink" href="#">Item 2</a>
                   </td>
                   <td class="menuItem">
                       <a class="menuLink" href="#">Item 3</a>
                   </td>
                   <td class="menuItem">
                       <a class="menuLink" href="#">Item 4</a>
                   </td>
                   <td class="menuItem">
                       <a class="menuLink" href="#">Item 5</a>
                   </td>
                   <td>
                       <div class="mainTitle">Gestione Presenze</div>
                   </td>
                   <td class="messaggioUtente">
                       <?php stampaMessaggio(); ?>
                   </td>
               </tr>
           </table> <?php
           }
           else{ ?>
                <div class="mainTitle">Gestione Presenze</div> <?php
           } ?>
        </div>

        

        <div id="corpo">
           <?php
                if(!isset($_SESSION['username'])){
                    login();
                }
                else{
                    include("pagine/home.php");
                }
           ?>
        </div>
        </div>


        <div id="footer">
            Copyright &copy 2010 by Bryan & Ethan
        </div>
    </body>
</html>