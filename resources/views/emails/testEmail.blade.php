@component('mail::message')

<h1>{{ $title }}</h1>
<h2>Estaciones con umbral superado</h2>

@foreach ($data->red as $item)
@component('mail::panel',[
    'alertColor'        => 'red',
    'title'             => $item->name_station,
    'valueAlert'        => $item->a10_value,
    'previousDeference' => $item->dif_previous_a10,
    'initialDate'       => $item->date_initial,
    'finalDate'         => $item->date_final,
    'alertStatus'       => $item->alert_status
])
@endcomponent
@endforeach

@foreach ($data->orange as $item)
@component('mail::panel',[
        'alertColor'        => 'orange',
        'title'             => $item->name_station,
        'valueAlert'        => $item->a10_value,
        'previousDeference' => $item->dif_previous_a10,
        'initialDate'       => $item->date_initial,
        'finalDate'         => $item->date_final,
        'alertStatus'       => $item->alert_status
])
@endcomponent
@endforeach

@foreach ($data->yellow as $item)
@component('mail::panel',[
        'alertColor'        => 'yellow',
        'title'             => $item->name_station,
        'valueAlert'        => $item->a10_value,
        'previousDeference' => $item->dif_previous_a10,
        'initialDate'       => $item->date_initial,
        'finalDate'         => $item->date_final,
        'alertStatus'       => $item->alert_status
])
@endcomponent
@endforeach

@foreach ($data->green as $item)
@component('mail::panel',[
        'alertColor'        => 'green',
        'title'             => $item->name_station,
        'valueAlert'        => $item->a10_value,
        'previousDeference' => $item->dif_previous_a10,
        'initialDate'       => $item->date_initial,
        'finalDate'         => $item->date_final,
        'alertStatus'       => $item->alert_status
])
@endcomponent
@endforeach

@endcomponent
