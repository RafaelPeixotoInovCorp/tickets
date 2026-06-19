@props(['filled' => false, 'class' => 'w-4 h-4'])

<span
    {{ $attributes->merge(['class' => 'kirri-pin-icon inline-block shrink-0 ' . $class . ($filled ? ' kirri-pin-icon-filled' : '')]) }}
    style="--kirri-pin-icon: url('{{ asset('images/pin-flaticon.png') }}')"
    aria-hidden="true"
></span>
