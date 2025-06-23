<!-- File: resources/views/components/sidebar-link.blade.php -->
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 text-sm font-semibold bg-gray-700 rounded-md transition-colors duration-200'
            : 'flex items-center px-4 py-2 text-sm font-semibold hover:bg-gray-700 rounded-md transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
