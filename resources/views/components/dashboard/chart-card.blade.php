@props(['title', 'canvasId'])

<div class="p-6 bg-white rounded-lg shadow">
    <h3 class="mb-4 text-lg font-semibold text-gray-900">{{ $title }}</h3>
    <canvas id="{{ $canvasId }}"></canvas>
</div>
