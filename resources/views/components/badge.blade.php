@props(['status'])

@php
    $class = [
        'pending'        => 'bg-yellow-100 text-yellow-800',
        'pending_full'   => 'bg-orange-100 text-orange-800',
        'paid_dp'        => 'bg-blue-100 text-blue-800',
        'paid'           => 'bg-green-100 text-green-800',
        'approved'       => 'bg-green-100 text-green-800',
        'failed'         => 'bg-red-100 text-red-800',
    ][$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span {{ $attributes->merge([
    'class' => "px-3 py-1 rounded-full text-xs font-semibold $class"
]) }}>
    {{ ucfirst(str_replace('_',' ',$status)) }}
</span>