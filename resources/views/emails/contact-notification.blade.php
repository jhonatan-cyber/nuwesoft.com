<x-mail::message>
@if (app()->getLocale() === 'es')
# Nuevo mensaje de contacto

**Nombre:** {{ $message->nombre }}

**Email:** {{ $message->email }}

**Mensaje:**
{{ $message->mensaje ?: 'Sin mensaje (solo adjunto)' }}

@if ($message->attachment_url)
**Adjunto:** [{{ $message->attachment_name }}]({{ $message->attachment_url }})
@endif

<x-mail::button :url="url('/dashboard')">
Ir al Dashboard
</x-mail::button>

<small>Recibido el {{ $message->created_at->format('d/m/Y H:i') }}</small>
@else
# New contact message

**Name:** {{ $message->nombre }}

**Email:** {{ $message->email }}

**Message:**
{{ $message->message ?: 'No message (attachment only)' }}

@if ($message->attachment_url)
**Attachment:** [{{ $message->attachment_name }}]({{ $message->attachment_url }})
@endif

<x-mail::button :url="url('/dashboard')">
Go to Dashboard
</x-mail::button>

<small>Received on {{ $message->created_at->format('m/d/Y H:i') }}</small>
@endif
</x-mail::message>
