@props(['type' => 'error', 'message' => ''])

@if($message)
    @php
        $styles = match($type) {
            'error' => [
                'bg' => 'bg-red-50 dark:bg-red-900/20',
                'border' => 'border-red-200 dark:border-red-800',
                'text' => 'text-red-700 dark:text-red-300',
                'icon' => 'text-red-500 dark:text-red-400',
                'ring' => 'ring-red-500/20',
            ],
            'warning' => [
                'bg' => 'bg-amber-50 dark:bg-amber-900/20',
                'border' => 'border-amber-200 dark:border-amber-800',
                'text' => 'text-amber-700 dark:text-amber-300',
                'icon' => 'text-amber-500 dark:text-amber-400',
                'ring' => 'ring-amber-500/20',
            ],
            'info' => [
                'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                'border' => 'border-blue-200 dark:border-blue-800',
                'text' => 'text-blue-700 dark:text-blue-300',
                'icon' => 'text-blue-500 dark:text-blue-400',
                'ring' => 'ring-blue-500/20',
            ],
            default => [
                'bg' => 'bg-red-50 dark:bg-red-900/20',
                'border' => 'border-red-200 dark:border-red-800',
                'text' => 'text-red-700 dark:text-red-300',
                'icon' => 'text-red-500 dark:text-red-400',
                'ring' => 'ring-red-500/20',
            ],
        };
    @endphp

    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        {{ $attributes->merge(['class' => "rounded-lg border p-4 {$styles['bg']} {$styles['border']} shadow-sm"]) }}
    >
        <div class="flex items-start gap-3">
            <div class="shrink-0 mt-0.5">
                @if($type === 'error')
                    <svg class="w-5 h-5 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                @elseif($type === 'warning')
                    <svg class="w-5 h-5 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            <div class="flex-1">
                <p class="text-sm font-medium {{ $styles['text'] }}">
                    {{ $message }}
                </p>
            </div>

            <button
                type="button"
                @click="show = false"
                class="shrink-0 ml-2 {{ $styles['icon'] }} hover:opacity-70 transition-opacity"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif
