@props([
    'variant' => 'horizontal', // 'horizontal' | 'stacked'
    'iconSize' => 'h-10',      // default height icon
    'textSize' => 'h-7',       // default height text
])

@if($variant === 'stacked')
    <div class="flex flex-col items-center justify-center gap-1 text-center">
        <img src="{{ asset('images/logo-icon.png') }}" alt="FutsalHub Icon" class="{{ $iconSize }} w-auto object-contain transition-transform group-hover:scale-105">
        <img src="{{ asset('images/logo-text.png') }}" alt="FutsalHub" class="{{ $textSize }} w-auto object-contain">
    </div>
@else
    <div class="flex items-center gap-2.5">
        <img src="{{ asset('images/logo-icon.png') }}" alt="FutsalHub Icon" class="{{ $iconSize }} w-auto object-contain transition-transform group-hover:scale-105">
        <img src="{{ asset('images/logo-text.png') }}" alt="FutsalHub" class="{{ $textSize }} w-auto object-contain">
    </div>
@endif
