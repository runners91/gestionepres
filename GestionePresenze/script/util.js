function redirect(href){
    window.location.href = href;
}

function eliminaTimbratura(td,id){
    td.style.border = "2px dotted black";
    document.getElementById("elimina").value = id;
    document.getElementById("bottElimina").style.display = 'block';
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
        data: "utente="+$("#username").val(),
        success: function(html){
            $("#listaUtenti").html(html);
        }
    });
}
function cercaUtente(utente){
    $("#username").val(utente);
    document.formCercaUtente.submit();
}
