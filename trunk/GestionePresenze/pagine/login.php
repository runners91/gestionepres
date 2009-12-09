<?php

if($_POST){
    login(false);
}


function login($stampa = true){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!$_POST){
        stampaForm();
    }
    else if($username=="" || $password==""){
        if($stampa) stampaForm("Username o password non inseriti !");
    }
    else{
        $rs = Database::getInstance()->eseguiQuery("select username as c from dipendenti
                                                    where (BINARY username = '".$username."') and password = md5('".$password."')");
        if($rs->RecordCount() == 1){
            $_SESSION['username'] = $username;
        }
        else{
            if($stampa) stampaForm("Username o password errati !");
        }
    }
}

function stampaForm($messaggio = ""){ ?>
<div class="loginForm">
    <form name="loginForm" method="POST" action="<?php echo $_SERVER[PHP_SELF] ?>">
    <table class="oggettoContenente">
        <tr>
            <td class="cellaTesto">
                Username:
            </td>
            <td class="cellaTesto">
                Password:
            </td>
        </tr>
        <tr>
            <td>
                <input type="textfield" name="username" id="username" class="standartTextfield" value="<?php echo $_POST['username'] ?>" />
            </td>
            <td>
                <input type="password" name="password" id="password" class="standartTextfield" />
            </td>
            <td>
                <input type="submit" value="Login" class="standartButton" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="messaggioErrore"> <?php echo $messaggio ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <a class="linkAiuti" href="www.google.com">Registrati</a>
            </td>
            <td colspan="2">
                <a class="linkAiuti" href="www.google.com">Hai dimenticato la password ?</a>
            </td>
        </tr>
    </table>
    </form>
</div>
<?php
}

function stampaMessaggio(){?>
<div class="messaggioUtente">
    <table class="oggettoContenente">
        <tr>
            <td>
                <nobr class="messaggioAreaLogin"> Buongiorno <?php echo $_SESSION['username']; ?> </nobr>
            </td>
            <td>
                 (<a class="linkAiuti" href="<?php session_destroy(); ?>">Logout</a>)
            </td>
        </tr>
    </table>
</div>
<?php } ?>
