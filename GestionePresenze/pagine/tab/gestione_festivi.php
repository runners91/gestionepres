<?php
    if($_POST){
        $filiale = $_POST["filiale"];
        $rs = Database::getInstance()->eseguiQuery("SELECT f.* FROM festivi as f,festivi_effettuati as fe WHERE fe.fk_filiale = f.id_festivo AND fe.fk_filiale =  ?",array($filiale));
        if($rs)
            Utilita::stampaTabella($rs);
    }
?>
<form method="POST">
    <select name="filiale" onchange="this.form.submit();">
        <option value="0">-</option>
        <?php
            $rs = Database::getInstance()->eseguiQuery("SELECT id_filiale,nome FROM filiali");
            while(!$rs->EOF){
                $selected="";
                $id = $rs->fields["id_filiale"];
                if($filiale==$id)
                    $selected="selected";
                echo '<option value="'.$id.'" '.$selected.'>'.$rs->fields["nome"].'</option>';
                $rs->MoveNext();
            }
        ?>
    </select>
</form>