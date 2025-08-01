@props(['title' => null])

<section {{ $attributes->merge(['class' =>
    'space-y-4 rounded-2xl bg-gray-50 p-8 shadow-lg ring-1 ring-gray-100']) }}>
    @if($title)
        <h2 class="text-2xl font-semibold text-gray-900">{{ $title }}</h2>
    @endif
    {{ $slot }}
</section>
