@props([
    'variant' => 'primary',   // primary|secondary|success|error
    'size'    => 'md',        // xs|sm|md|lg
    'type'    => 'button',
])

@php
$variant = [
   'primary'   => 'bg-primary-600 hover:bg-primary-700 text-white',
   'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-900',
   'success'   => 'bg-green-600 hover:bg-green-700 text-white',
   'error'     => 'bg-red-600 hover:bg-red-700 text-white',
][$variant] ?? 'bg-gray-200 text-gray-900';

$size = [
   'xs'=>'px-2.5 py-1 text-xs','sm'=>'px-3 py-1.5 text-sm',
   'md'=>'px-4 py-2 text-sm', 'lg'=>'px-5 py-2.5 text-base',
][$size] ?? 'px-4 py-2 text-sm';
@endphp

<button {{ $attributes->merge([
         'type' => $type,
         'class' => "rounded font-medium transition {$variant} {$size}"
      ]) }}>
   {{ $slot }}
</button>
