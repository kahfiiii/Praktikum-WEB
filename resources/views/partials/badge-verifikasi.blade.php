{{-- Badge status verifikasi: 3 varian --}}
@php
    $verifyMap = [
        'verified' => ['label' => 'Terverifikasi', 'icon' => 'badge-check', 'class' => 'border-lime-dark bg-lime-soft text-lime-ink'],
        'rejected' => ['label' => 'Ditolak', 'icon' => 'x-circle', 'class' => 'border-coral bg-coral-soft text-coral-ink'],
        'unverified' => ['label' => 'Belum diverifikasi', 'icon' => 'clock', 'class' => 'border-line-soft bg-gray-fill text-ink-muted'],
    ];
    $v = $verifyMap[$status ?? 'unverified'];
@endphp
<span class="badge {{ $v['class'] }}">
    <x-icon :name="$v['icon']" class="h-3 w-3" />{{ $v['label'] }}
</span>
