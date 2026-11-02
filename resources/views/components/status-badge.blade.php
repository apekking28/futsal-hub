@props(['status', 'type' => 'payment'])

@php
    $badgeConfig = [
        // Payment Statuses
        'pending'   => ['bg' => 'bg-[#FEF3C7]', 'text' => 'text-[#92400E]', 'label' => 'Menunggu Verifikasi'],
        'approved'  => ['bg' => 'bg-[#DCFCE7]', 'text' => 'text-[#166534]', 'label' => 'Disetujui'],
        'rejected'  => ['bg' => 'bg-[#FEE2E2]', 'text' => 'text-[#991B1B]', 'label' => 'Ditolak'],

        // Booking Statuses
        'reserved'  => ['bg' => 'bg-[#E0F2FE]', 'text' => 'text-[#075985]', 'label' => 'Reserved'],
        'confirmed' => ['bg' => 'bg-[#DCFCE7]', 'text' => 'text-[#166534]', 'label' => 'Terkonfirmasi'],
        'completed' => ['bg' => 'bg-[#F1F5F9]', 'text' => 'text-[#334155]', 'label' => 'Selesai'],
        'cancelled' => ['bg' => 'bg-[#F1F5F9]', 'text' => 'text-[#334155]', 'label' => 'Dibatalkan'],
    ];

    $config = $badgeConfig[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ucfirst($status)];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
    {{ $config['label'] }}
</span>
