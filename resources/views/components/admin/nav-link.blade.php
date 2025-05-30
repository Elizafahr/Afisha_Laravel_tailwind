<!-- resources/views/components/admin/nav-link.blade.php -->
@props(['active' => false, 'href'])

<a href="{{ $href }}" @class([
    'flex items-center px-4 py-3 text-sm font-medium',
    'bg-gray-900 text-white' => $active,
    'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
])>
    {{ $slot }}
</a>
