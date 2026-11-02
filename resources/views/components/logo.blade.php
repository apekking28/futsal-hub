@props([
    'variant' => 'horizontal', // 'horizontal' | 'stacked'
    'iconSize' => 'h-12 sm:h-14', // Ditambah dari h-10 ke h-12/h-14
    'textSize' => 'h-8 sm:h-9', // Ditambah dari h-7 ke h-8/h-9
])

@if ($variant === 'stacked')
    <div class="flex flex-col items-center justify-center gap-1.5 text-center">
        <img src="{{ asset('images/logo-icon.png') }}" alt="FutsalHub Icon"
            class="{{ $iconSize }} w-auto object-contain transition-transform group-hover:scale-105">
        <img src="{{ asset('images/logo-text.png') }}" alt="FutsalHub" class="{{ $textSize }} w-auto object-contain">
    </div>
@else
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo-icon.png') }}" alt="FutsalHub Icon"
            class="{{ $iconSize }} w-auto object-contain transition-transform group-hover:scale-105">
        <img src="{{ asset('images/logo-text.png') }}" alt="FutsalHub"
            class="{{ $textSize }} w-auto object-contain">
    </div>
@endif
