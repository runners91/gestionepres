function redirect(href){
    window.location.href = href;
}

function eliminaTimbratura(td,id){
    td.style.border = "2px dotted black";
    document.getElementById("elimina").value = id;
    document.getElementById("bottElimina").style.display = 'block';
}