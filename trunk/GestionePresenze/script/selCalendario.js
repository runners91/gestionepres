var ok = false;
var dataSel = getUrlVars()['data'];
if(dataSel.indexOf("#") != -1)
    dataSel = dataSel.substring(0,10);

if(dataSel>0){
    $(document).keydown(function(event){
        if(event.keyCode == 17)
            ok = true;
        $(document).keyup(function(event){
            if(event.keyCode == 17)
                ok = false;
        });
    });
    $(document).ready(function() {
        $("td.cellaData").mouseover(function (){
            if(ok) {
                var data = new Date();
                var dataS = new Date();
                data.setTime( dataSel*1000 );
                var classi = $(this).attr("class").split(" ");
                var classe = classi[1].split("_");
                var giorno = data.getDate();
                var mese = data.getMonth()+1;
                var anno = data.getFullYear();
                var giornoS = classe[1];
                var meseS = classe[2];
                var annoS = classe[3];
                dataS.setDate(giornoS);
                dataS.setMonth(meseS-1);
                dataS.setFullYear(annoS);
                dataS.setHours(0, 0, 0, 0)

                if(data.valueOf() != dataS.valueOf()) {
                    var inizio;
                    var fine;
                    if(data < dataS) {
                        inizio = data;
                        fine = dataS;
                    }
                    else if (data > dataS) {
                        inizio = dataS;
                        fine = data;
                    }
                    $("#sel1").val(formattaData(inizio));
                    $("#sel2").val(formattaData(fine));
                    $(".cellaDataSelezionata").removeClass("cellaDataSelezionata");
                    $(".cellaDataIntermedia").removeClass("cellaDataIntermedia");
                    inizio.setDate(inizio.getDate()+1);
                    while (true) {
                        $(".data_"+inizio.getDate()+"_"+(inizio.getMonth()+1)+"_"+inizio.getFullYear()).addClass("cellaDataIntermedia");
                        if(inizio.valueOf() == fine.valueOf())
                            break;
                        inizio.setDate(inizio.getDate()+1);
                    }
                    $(".data_"+giorno+"_"+mese+"_"+anno).removeClass("cellaDataIntermedia");
                    $(".data_"+giornoS+"_"+meseS+"_"+annoS).removeClass("cellaDataIntermedia");
                    $(".data_"+giorno+"_"+mese+"_"+anno).addClass("cellaDataSelezionata");
                    $(".data_"+giornoS+"_"+meseS+"_"+annoS).addClass("cellaDataSelezionata");
                }
                else
                    $(".cellaDataSelezionata").removeClass("cellaDataSelezionata");
                    $(".data_"+giorno+"_"+mese+"_"+anno).addClass("cellaDataSelezionata");
            }
        });
    });
}

function formattaData(data) {
    var g = data.getDate()<10?'0'+data.getDate():data.getDate();
    var m = data.getMonth()<10?'0'+data.getMonth():data.getMonth();
    return g+"."+m+"."+data.getFullYear();
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
