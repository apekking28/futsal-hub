@props(['type' => 'info', 'message'])

@php
    $styles = [
        'success' => 'bg-[#DCFCE7] text-[#166534] border-[#BBF7D0]',
        'danger'  => 'bg-[#FEE2E2] text-[#991B1B] border-[#FECACA]',
        'info'    => 'bg-[#E0F2FE] text-[#075985] border-[#BAE6FD]',
        'warning' => 'bg-[#FEF3C7] text-[#92400E] border-[#FDE68A]',
    ];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 5000)"
     class="mb-4 p-4 rounded-lg border flex items-center justify-between {{ $styles[$type] ?? $styles['info'] }} shadow-sm">
    <div class="text-sm font-medium">
        {{ $message }}
    </div>
    <button @click="show = false" class="text-sm font-bold opacity-70 hover:opacity-100">&times;</button>
</div>
