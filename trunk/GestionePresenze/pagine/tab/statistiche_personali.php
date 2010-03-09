<?php
    $str ='var datasets = {';
    for($i=1;$i<=6;$i++) {
        $rs = Database::getInstance()->eseguiQuery("SELECT nome FROM causali WHERE id_motivo = ?",array($i));
        $nome = $rs->fields["nome"];
        $str .= '"'.strtolower($nome).'": { label: "'.$nome.'", data: [';
        for($m=1;$m<=12;$m++) {
            $data = mktime(0, 0, 0,$m,1,2010);
            $giorni = Evento::contaGiorniMese($data, $i, $_SESSION["id_utente"]);
            $str .= '['.$m.', '.$giorni.']';
            if($m!=12)
                $str .= ',';
        }
        $str .= ']}';
        if($i!=6){
            $str .= ',';
        }
    }
    $str .= '};';

?>
<script>
    $(function () {
        <?php echo $str;?>
        var i = 0;
        $.each(datasets, function(key, val) {
            val.color = i;
            ++i;
        });

        // insert checkboxes
        var choiceContainer = $("#choices");
        $.each(datasets, function(key, val) {
            choiceContainer.append('<br/><input type="checkbox" name="' + key +
                                   '" checked="checked" id="id' + key + '">' +
                                   '<label for="id' + key + '">'
                                    + val.label + '</label>');
        });
        choiceContainer.find("input").click(plotAccordingToChoices);


        function plotAccordingToChoices() {
            var data = [];

            choiceContainer.find("input:checked").each(function () {
                var key = $(this).attr("name");
                if (key && datasets[key])
                    data.push(datasets[key]);
            });
            if (data.length > 0)
            $.plot($("#placeholder"), data, {
                        series: {
                            lines: { show: true },
                            points: { show: true },
                            bars: { barWidth: 0.9,show: true }
                        },
                        xaxis: {
                            ticks:[[1,"Gen"], [2,"Feb"], [3,"Mar"], [4,"Apr"], [5,"Mag"], [6,"Giu"], [7,"Lug"], [8,"Ago"], [9,"Sett"], [10,"Ott"], [11,"Nov"], [12,"Dic"]]
                        },
                        yaxis: {
                            ticks: 10,
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
<p id="choices">Show:</p>