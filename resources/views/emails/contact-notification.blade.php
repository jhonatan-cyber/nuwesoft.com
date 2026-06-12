<x-mail::message>
@if (app()->getLocale() === 'es')
# Nuevo mensaje de contacto

**Nombre:** {{ $message->nombre }}

**Email:** {{ $message->email }}

**Mensaje:**
{{ $message->mensaje }}

<x-mail::button :url="url('/dashboard')">
Ir al Dashboard
</x-mail::button>

<small>Recibido el {{ $message->created_at->format('d/m/Y H:i') }}</small>
@else
# New contact message

**Name:** {{ $message->nombre }}

**Email:** {{ $message->email }}

**Message:**
{{ $message->mensaje }}

<x-mail::button :url="url('/dashboard')">
Go to Dashboard
</x-mail::button>

<small>Received on {{ $message->created_at->format('m/d/Y H:i') }}</small>
@endif
</x-mail::message>
