@props(['title', 'value', 'color' => 'gray'])

@php
    $colors = [
        'green' => 'text-green-600',
        'blue' => 'text-blue-600',
        'yellow' => 'text-yellow-600',
        'purple' => 'text-purple-600',
        'gray' => 'text-gray-600',
    ];
@endphp

<div class="p-6 bg-white rounded-lg shadow">
    <h3 class="text-sm text-gray-500">{{ $title }}</h3>
    <p class="mt-2 text-2xl font-bold {{ $colors[$color] ?? $colors['gray'] }}">{{ $value }}</p>
</div>
