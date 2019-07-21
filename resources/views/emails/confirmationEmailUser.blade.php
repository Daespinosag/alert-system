@component('mail::message')

 <h1>Hola {{ $name }}, </h1>
 <h2>Gracias por registrarte a nuestros <strong> sistemas de alerta </strong> !</h2>

Por favor confirma tu correo electrónico.
Para ello simplemente debes hacer click en el siguiente enlace:

@component('mail::button', ['url' => url('/register/verify/' . $code) , 'color' => 'green'])
 Validar Cuenta
@endcomponent

Si no se registró en nuestro sistema o no autorizo su registro por favor ignore este correo

@endcomponent