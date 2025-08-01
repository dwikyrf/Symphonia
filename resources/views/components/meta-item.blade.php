@props(['label'])
<div>
    <h3 class="text-sm font-medium text-gray-600">{{ $label }}</h3>
    <p class="text-lg font-semibold text-gray-800">
        {{ $slot }}
    </p>
</div>