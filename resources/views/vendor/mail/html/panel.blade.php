<table class="panel" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="alert-{{ $alertColor or 'green' }}"> </td>
        <td class="panel-content">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td><h1>{{ $title or 'Nombre Estación' }}</h1></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr class="alert-value">
                                <td class="title-alert-metadata">Valor Alerta: </td>
                                <td>{{ $valueAlert or '-' }}</td>
                            </tr>
                            <tr  class="alert-diff">
                                <td class="title-alert-metadata">Diferencia Anterior: </td>
                                <td>{{ $previousDeference or '-' }}</td>
                            </tr>
                            <tr class="alert-dates">
                                <td class="title-alert-metadata">Fecha Final: </td>
                                <td>{{ $initialDate or '-' }}</td>
                            </tr>
                            <tr class="alert-dates">
                                <td class="title-alert-metadata">Fecha Inicial: </td>
                                <td>{{ $finalDate or '-' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="img-alert-panel">
                        <img src="{{ asset($alertStatus) }}" />
                    </td>
                </tr>
            </table>
        </td>
        <td class="panel-item">
            <!--{{ Illuminate\Mail\Markdown::parse($slot) }} -->

        </td>
    </tr>

</table>
