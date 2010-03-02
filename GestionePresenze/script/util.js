var slTd = null;
var className = null;

function redirect(href){
    window.location.href = href;
}

/**** Prende l'elemento <td> della timbratura e l id della timbratura ***/
/**** Inserisce nel textfield elimina l'id della timbratura da eliminare e mostra il bottone elimina ***/
/**** Tiene in slTd un riferimento a qual'è l'ultima cella modificata, per farla tornare non selezionata in visualizzazione ***/
function eliminaTimbratura(td,id){
    if(slTd) slTd.className = className;
    if(slTd!=td){
        slTd = td;
        className = slTd.className;
        td.className = "griglia selezionato";
        document.getElementById("elimina").value = id;
        document.getElementById("bottElimina").style.display = 'block';
    }
    else{
        document.getElementById("elimina").value = "";
        document.getElementById("bottElimina").style.display = 'none';
        slTd = null;
    }
}

function cambiaStato(stato,commento){
   $('#nuovoStato').val(stato);
   $('#commento_stato').val(commento);
   document.cambiaSts.submit();
}

function cambiaCommento(stato,commento){
    $("#vediCommento").slideUp('fast');
    $("#nuovoStato").val(stato);
    $("#commento_stato").val(commento);
    $("#modificaCommento").slideDown('fast');
    $("#commento_stato").focus();
}
function vediStati(commento){
    $("#stato").slideDown('fast');
    $("#vediCommento").slideUp('fast');
    $("#modificaCommento").slideUp('fast');
    window.setTimeout('nascondiStati('+commento+')', 2500)
}
function nascondiStati(commento){
    $('#stato').slideUp('fast');
    if(commento==1)
        $("#vediCommento").slideDown('fast');

}

function utenti(){
    $.ajax({
        type: "POST",
        url: "listaUtenti.php",
        data: "tipo=ricerca&utente="+$("#username").val(),
        success: function(html){
            var tmp = html.split("||");
            if(tmp[0]==1) {
               cercaUtente(tmp[1])
            }
            else
                $("#listaUtenti").html(tmp[2]);
        }
    });
}
function cercaUtente(utente){
    $("#username").val(utente);
    document.formCercaUtente.submit();
}
