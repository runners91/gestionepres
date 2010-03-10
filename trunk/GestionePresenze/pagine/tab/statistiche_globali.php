<?php
    $anno = $_POST["anno"]?$_POST["anno"]:date("Y");
    $filiale = $_POST["filiale"]?$_POST["filiale"]:1;
?>
<form action="#" method="POST" name="formCambiaAnno" style="position:absolute;left:45px;">
    Filiale: <select name="filiale" id="selectFiliale" onchange="cambiaAnno($('#selectAnno').val());">
        <?php
            foreach (Filiale::getListaFiliali() as $id => $nome) {
                    $selected = "";
                if($id == $_POST["filiale"])
                    $selected = "selected";
                echo '<option value='.$id.' '.$selected.'>'.$nome.'</option>';
            }
        ?>
    </select> &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp;
    <input type="hidden" id="anno" name="anno">
    <input type="button" class="bottCalendario" value="<" onclick="cambiaAnno(<?php echo $anno-1;?>)"> &nbsp;
    <select id="selectAnno" onchange="cambiaAnno(this.value);">
        <?php
            for($i=(date("Y")-20);$i<=date("Y");$i++) {
                $selected = "";
                if($i == $anno)
                    $selected = "selected";
                echo '<option value='.$i.' '.$selected.'>'.$i.'</option>';
            }
        ?>
    </select> &nbsp;
    <?php
    if($anno < date("Y"))
        echo '<input type="button" class="bottCalendario" value=">" onclick="cambiaAnno('.($anno+1).')">';
    if($anno < date("Y")-1)
        echo ' <input type="button" class="bottCalendario" value=">>" onclick="cambiaAnno('.date("Y").')">';
    ?>
    <input style="position:absolute;left:480px;" type="button" value="Export xls" class="bottCalendario" onclick="exportXls();">
</form><br>

<script>
    function exportXls(){
        var assenze = "";
        $("#choices").find(".causali:checked").each(function () {
            assenze += $(this).attr("name")+"_";
        });
        location.href="excel.php?filiale="+$("#selectFiliale").val()+"&anno="+$("#selectAnno").val()+"&assenze="+assenze;
    }
    function cambiaAnno(anno){
        $("#anno").val(anno);
        formCambiaAnno.submit();
    }
    $(function () {
        <?php echo Utilita::creaDatasets($anno,$filiale,false);?>
        var i = 0;
        $.each(datasets, function(key, val) {
            val.color = i;
            ++i;
        });

        // insert checkboxes
        var choiceContainer = $("#choices");
        $.each(datasets, function(key, val) {
            checked = "";
            if(key==1)
                checked="checked='checked'"
            choiceContainer.append('<input class="causali" type="checkbox" name="' + key +
                                   '" '+checked+' id="id' + key + '">' +
                                   '<label for="id' + key + '">'
                                    + val.label + '</label><br />');
        });
        choiceContainer.find("input").click(plotAccordingToChoices);
        $("#tipoGrafico").find("input").click(plotAccordingToChoices);

        function plotAccordingToChoices() {
            var data = [];
            lines = $("#linea").attr('checked');
            bars = $("#barre").attr('checked');
            if(!lines) {
                bars = true;
                $("#barre").attr('checked',"checked")
            }

            choiceContainer.find(".causali:checked").each(function () {
                var key = $(this).attr("name");
                if (key && datasets[key])
                    data.push(datasets[key]);
            });
            if (data.length > 0)
            $.plot($("#placeholder"), data, {
                        series: {
                            lines: { show: lines },
                            bars: { show: bars,barWidth: 0.9}
                        },
                        xaxis: {
                            ticks:[[1,"Gen"], [2,"Feb"], [3,"Mar"], [4,"Apr"], [5,"Mag"], [6,"Giu"], [7,"Lug"], [8,"Ago"], [9,"Sett"], [10,"Ott"], [11,"Nov"], [12,"Dic"]]
                        },
                        yaxis: {
                            ticks: 20,
                            min:0,
                            max:31

                        },
                        grid: {
                            backgroundColor: { colors: ["#fff", "#eee"] }
                        }
                    });
        }

        plotAccordingToChoices();
    });
</script>

<div id="placeholder" style="width:600px;height:300px"></div>
    <fieldset style="position:absolute;left:650px;top:160px;width:150px;border:1px solid black;">
        <legend>Tipo Grafico</legend>
        <div id="tipoGrafico">
            <input type="checkbox" checked="checked" id="barre"><label>Barre</label><br>
            <input type="checkbox" id="linea"><label>Linee</label>
        </div>
    </fieldset>
    <fieldset style="position:absolute;left:650px;top:287px;width:150px;border:1px solid black;">
        <legend>Assenze</legend>
        <div id="choices"></div>
    </fieldset>

