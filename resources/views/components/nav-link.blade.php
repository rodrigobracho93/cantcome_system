@props(['active', 'sidebar' => false])

@php
if ($sidebar) {
    $classes = ($active ?? false)
        ? 'sidebar-link active flex items-center gap-3 pl-3 py-2.5 text-sm font-medium text-white bg-indigo-800/60 border-l-4 border-indigo-400 transition duration-200'
        : 'sidebar-link flex items-center gap-3 pl-4 py-2.5 text-sm font-medium text-white/80 hover:text-white hover:bg-indigo-800/30 border-l-4 border-transparent transition duration-200';
}
@endphp

<a {{ $attributes->merge(['class' => $classes . ' group']) }}>
    @if($sidebar && isset($icon))
        <span class="shrink-0 transition-all duration-200 group-hover:scale-110 group-hover:translate-x-0.5 group-hover:text-indigo-300">{{ $icon }}</span>
    @endif
    <span class="sidebar-text transition-colors duration-200">{{ $slot }}</span>
</a>
