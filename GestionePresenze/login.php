<?php

if($_POST){
    login(false);
}

function login($stampa = true){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $msg = "";
    $errori = array();
    if($_POST){
        if($username=="")
            $errori["username"] = " - Username non inserito !<br>";
        if($password=="")
            $errori["password"] = " - Password non inserita !";
            
        if(sizeof($errori)==0){
            $rs = Database::getInstance()->eseguiQuery("select id_dipendente,username from dipendenti where (BINARY username = ?) and password = md5(?)",array($username,$password));
            if($rs->RecordCount() == 1){
                $_SESSION['username'] = $username;
                $_SESSION['id_utente'] = $rs->fields['id_dipendente'];
                $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as inizio FROM dipendenti WHERE username = ? and password = md5('inizio');",array($username));
                if($rs->fields["inizio"] == 1)
                    header("Location:index.php?pagina=utente&tab=cambia_password&login=1");

            }
            else{
                $errori["username"] = "Username o password errati !";
                $errori["password"] = "";
            }
        }
    }
    if($stampa) stampaForm($msg,$errori);
}

function stampaForm($messaggio = "",$errori = array()){ ?>
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
                            <input type="text" name="username" id="username" class="standartTextfield<?php echo isset($errori["username"])?" errore":"";?>" value="<?php echo $_POST['username'] ?>" />
                        </td>
                        <td>
                            <input type="password" name="password" id="password" class="standartTextfield<?php echo isset($errori["password"])?" errore":"";?>" />
                        </td>
                        <td>
                            <input type="submit" value="Login" class="standartButton" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p class="messaggioErrore"> <?php echo $errori["username"].$errori["password"] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
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
<?php
}
?>
