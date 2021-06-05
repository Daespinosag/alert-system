@component('mail::message')

<h1>{{ $title }}</h1>
@if(isset($data))
@foreach ($data as $item)
@component('mail::panel',[
    'alertColor'        => $item->alert_tag,
    'title'             => $item->station->name,
    'valueAlert'        => $item->alert_level,
    'previousDeference' => $item->indicator_previous_difference,
    'initialDate'       => $item->date_time_initial,
    'finalDate'         => $item->date_time_final,
    'alertStatus'       => $item->alert_status
])
@endcomponent
@endforeach
@endif
