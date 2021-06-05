@if(isset($data))
    <h1>tipo: {{$data->code}}</h1>
    <h1>tipo: {{$data->type}}</h1>
    <h1>Estado: {{$data->status}}</h1>
    <h1>Prioridad: {{$data->priority}}</h1>
    <h1>Fecha: {{$data->date}}</h1>
    <h1>Comentaro: {{$data->comments}}</h1>
@endif
