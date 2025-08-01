@props([
    'href'   => '#',
    'color'  => 'primary',   // primary, danger, secondary, dst.
    'size'   => 'sm',        // sm, md, lg
])

@php
    // Map warna ke Tailwind class
    $palette = [
        'primary'   => 'bg-primary-600 hover:bg-primary-700 text-white',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-900',
        'danger'    => 'bg-red-600 hover:bg-red-700 text-white',
    ];

    // Map ukuran ke padding
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2   text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $classes = ($palette[$color] ?? $palette['primary']) .
               ' ' .
               ($sizes[$size]   ?? $sizes['sm']) .
               ' rounded font-semibold inline-block transition';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
