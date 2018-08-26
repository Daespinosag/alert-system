@component('mail::message')

    <h1>Lista de estaciones - Alerta inundación</h1>

@component('mail::panel',[
    'alertColor'        => 'red',
    'title'             => 'Nombre Estación',
    'valueAlert'        => 100,
    'previousDeference' => 10,
    'initialDate'       => '2018-08-01 12:10:10',
    'finalDate'         => '2018-08-01 12:15:10',
    'alertStatus'       => '/images/alert-icons/alert-increase.png'
])

@endcomponent

@component('mail::panel',[
    'alertColor'        => 'orange',
    'title'             => 'Nombre Estación',
    'valueAlert'        => 100,
    'previousDeference' => 10,
    'initialDate'       => '2018-08-01 12:10:10',
    'finalDate'         => '2018-08-01 12:15:10',
    'alertStatus'       => '/images/alert-icons/alert-equal.png'
])
@endcomponent

@component('mail::panel',[
'alertColor'        => 'yellow',
'title'             => 'Nombre Estación',
'valueAlert'        => 100,
'previousDeference' => 10,
'initialDate'       => '2018-08-01 12:10:10',
'finalDate'         => '2018-08-01 12:15:10',
'alertStatus'       => '/images/alert-icons/alert-equal.png'
])
@endcomponent

@component('mail::panel',[
'alertColor'        => 'green',
'title'             => 'Nombre Estación',
'valueAlert'        => 100,
'previousDeference' => 10,
'initialDate'       => '2018-08-01 12:10:10',
'finalDate'         => '2018-08-01 12:15:10',
'alertStatus'       => '/images/alert-icons/alert-decrease.png'
])
@endcomponent


@endcomponent
