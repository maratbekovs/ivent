@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2.5 text-sm font-semibold bg-primary text-white rounded-lg shadow-md'
            : 'flex items-center px-4 py-2.5 text-sm font-medium text-gray-300 hover:bg-primary-dark/50 hover:text-white rounded-lg transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
