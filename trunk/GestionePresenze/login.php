<?php

if($_POST){
    login(false);
}

function login($stampa = true){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $msg = "";

    if($_POST){
        if($username=="" || $password==""){
            $msg = "Username o password non inseriti !";
        }
        else{
            $rs = Database::getInstance()->eseguiQuery("select id_dipendente,username from dipendenti where (BINARY username = ?) and password = md5(?)",array($username,$password));
            if($rs->RecordCount() == 1){
                $_SESSION['username'] = $username;
                $_SESSION['id_utente'] = $rs->fields['id_dipendente'];
                $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as inizio FROM dipendenti WHERE username = ? and password = md5('inizio');",array($username));
                if($rs->fields["inizio"] == 1)
                    header("Location:index.php?pagina=utente&tab=cambia_password&login=1");

            }
            else{
                $msg = "Username o password errati !";
            }
        }
    }
    if($stampa) stampaForm($msg);
}

function stampaForm($messaggio = ""){ ?>
<body onLoad="document.loginForm.username.focus();">
<table width="100%">
    <tr>
        <td align="center">
            <div>
                <form name="loginForm" method="POST" action="<?php echo $_SERVER[PHP_SELF] ?>">
                <table class="loginForm">
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
                            <a class="linkAiuti" href="">Registrati</a>
                        </td>
                        <td colspan="2">
                            <a class="linkAiuti" href="">Hai dimenticato la password ?</a>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
    </tr>
</table>
</body>
<?php
}

function stampaMessaggio(){ ?>
    Buongiorno <a class="linkUser" href="?pagina=utente"><?php echo $_SESSION['username']; ?></a>
    (<a class="linkAiuti" href="?azione=logout">Logout</a>)
<?php } ?>
