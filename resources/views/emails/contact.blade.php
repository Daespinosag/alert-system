<DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Mensaje Sistema Alertas</title>
    </head>
    <body>
        <table class="table" border="1">
            <tr>
                <td>Estacion</td>
                <td>Valor a25</td>
                <td>Alerta</td>
                <td>% recuperacion</td>
                <td>Diferencia anterior</td>
                <td>Num no cambio</td>
                <td>Cambio en alerta</td>
                <td>Disminucion alerta</td>
                <td>Aumenta alerta</td>
                <td>Creado</td>
            </tr>
            @foreach ($a25ForStations as $a25ForStation)
                <tr>
                    <td>{{ $a25ForStation->station }}</td>
                    <td>{{ $a25ForStation->a25_value }}</td>
                    <td>{{ $a25ForStation->alert }}</td>
                    <td>{{ $a25ForStation->avg_recovered }}</td>
                    <td>{{ $a25ForStation->dif_previous_a25 }}</td>
                    <td>{{ $a25ForStation->num_not_change_alert }}</td>
                    <td>{{ $a25ForStation->change_alert }}</td>
                    <td>{{ $a25ForStation->alert_decrease }}</td>
                    <td>{{ $a25ForStation->alert_increase }}</td>
                    <td>{{ $a25ForStation->created_at }}</td>
                </tr>
            @endforeach

        </table>
    </body>
</html>