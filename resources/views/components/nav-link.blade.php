@props(['active', 'sidebar' => false])

@php
if ($sidebar) {
    $classes = ($active ?? false)
        ? 'sidebar-link flex items-center gap-3 pl-3 py-2.5 text-sm font-medium text-white bg-indigo-800/60 border-l-4 border-indigo-400 transition duration-150'
        : 'sidebar-link flex items-center gap-3 pl-4 py-2.5 text-sm font-medium text-indigo-200/80 hover:text-white hover:bg-indigo-800/30 border-l-4 border-transparent transition duration-150';
}
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($sidebar && isset($icon))
        <span class="shrink-0">{{ $icon }}</span>
    @endif
    <span class="sidebar-text">{{ $slot }}</span>
</a>
