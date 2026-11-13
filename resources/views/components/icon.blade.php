{{--
    Ikon SVG lokal (pengganti lucide CDN).
    Pakai: <x-icon name="home" class="h-4 w-4" />
    Semua path 24x24, stroke 1.8, mengikuti gaya ikon Figma.
--}}
@php
    $paths = [
        'home'        => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>',
        'library'     => '<path d="M4 5.5A1.5 1.5 0 0 1 5.5 4H9v16H5.5A1.5 1.5 0 0 1 4 18.5z"/><path d="M9 4h4.5A1.5 1.5 0 0 1 15 5.5v13a1.5 1.5 0 0 1-1.5 1.5H9"/><path d="m17 6 3 .8-2.4 12.2"/>',
        'book'        => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M4 5.5v15"/><path d="M8 7.5h8M8 11h5"/>',
        'upload'      => '<path d="M12 16V4"/><path d="m7.5 8.5 4.5-4.5 4.5 4.5"/><path d="M4 15v3.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V15"/>',
        'list-checks' => '<path d="M4 6.5 6 8.5 9 5.5"/><path d="M4 17.5 6 19.5 9 16.5"/><path d="M13 7h7M13 18h7"/>',
        'file-text'   => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M8.5 13h7M8.5 16.5h4.5"/>',
        'clock'       => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
        'circle-help' => '<circle cx="12" cy="12" r="8.5"/><path d="M9.6 9.6a2.5 2.5 0 1 1 3.6 2.2c-.8.4-1.2 1-1.2 1.9"/><path d="M12 16.8v.01"/>',
        'chevron-right' => '<path d="m9.5 5.5 6.5 6.5-6.5 6.5"/>',
        'chevron-left'  => '<path d="m14.5 5.5-6.5 6.5 6.5 6.5"/>',
        'arrow-left'  => '<path d="M20 12H4"/><path d="m10 6-6 6 6 6"/>',
        'arrow-right' => '<path d="M4 12h16"/><path d="m14 6 6 6-6 6"/>',
        'menu'        => '<path d="M3.5 7h17M3.5 12h17M3.5 17h17"/>',
        'x'           => '<path d="m6 6 12 12M18 6 6 18"/>',
        'log-out'     => '<path d="M15 4h3.5A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5H15"/><path d="M9 16l-4-4 4-4"/><path d="M5 12h10"/>',
        'flag'        => '<path d="M5 3v18"/><path d="M5 4.5h9l-1.2 3.5L14 11.5H5"/>',
        'shield-check'=> '<path d="M12 3 5 6v5.5c0 4.2 3 7.8 7 9.5 4-1.7 7-5.3 7-9.5V6z"/><path d="m9 12 2 2 4-4"/>',
        'settings'    => '<circle cx="12" cy="12" r="2.8"/><path d="M12 3.5v2M12 18.5v2M4.9 7.5l1.7 1M17.4 15.5l1.7 1M4.9 16.5l1.7-1M17.4 8.5l1.7-1"/>',
        'sparkles'    => '<path d="M12 4.5 13.4 9 18 10.4 13.4 11.8 12 16.3 10.6 11.8 6 10.4 10.6 9z"/><path d="M18.5 15.5l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7z"/>',
        'users'       => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 19.5c0-3 2.5-5.2 5.5-5.2s5.5 2.2 5.5 5.2"/><path d="M16 5.4a3.2 3.2 0 0 1 0 6.2M17.5 14.8c2 .6 3.5 2.4 3.5 4.7"/>',
        'message-square' => '<path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v7a2.5 2.5 0 0 1-2.5 2.5H9l-5 4z"/>',
        'messages-square'=> '<path d="M3.5 6A2.5 2.5 0 0 1 6 3.5h8A2.5 2.5 0 0 1 16.5 6v4A2.5 2.5 0 0 1 14 12.5H8l-4.5 3.5z"/><path d="M20 9.5v5.5a2.5 2.5 0 0 1-2.5 2.5H16"/>',
        'send'        => '<path d="M21 3 10.5 13.5"/><path d="M21 3l-6.8 18-3.7-8.2L2.5 9z"/>',
        'message-circle'=> '<path d="M20.5 11.5a8 8 0 0 1-11.8 7L4 20.5l2-4.6A8 8 0 1 1 20.5 11.5z"/>',
        'badge-check' => '<path d="m12 3 2.2 1.6 2.7-.3 1 2.5 2.4 1.3-.7 2.6.7 2.6-2.4 1.3-1 2.5-2.7-.3L12 21l-2.2-1.6-2.7.3-1-2.5L3.7 16l.7-2.6-.7-2.6L6.1 9.5l1-2.5 2.7.3z"/><path d="m9 12 2 2 4-4"/>',
        'x-circle'    => '<circle cx="12" cy="12" r="8.5"/><path d="m9.5 9.5 5 5M14.5 9.5l-5 5"/>',
        'check'       => '<path d="m5 12.5 4.5 4.5L19 7"/>',
        'check-check' => '<path d="m2.5 12.5 4 4L15 8"/><path d="m10.5 16.5 1.5 1.5L21 8.5"/>',
        'info'        => '<circle cx="12" cy="12" r="8.5"/><path d="M12 11v5"/><path d="M12 8v.01"/>',
        'alert-triangle' => '<path d="M12 3.8 2.8 19.5h18.4z"/><path d="M12 9.5v4"/><path d="M12 16.4v.01"/>',
        'search'      => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 4.5 4.5"/>',
        'plus'        => '<path d="M12 5v14M5 12h14"/>',
        'pencil'      => '<path d="M4 20h4L19.5 8.5a2.1 2.1 0 0 0-3-3L5 17z"/><path d="M14.5 5.5l4 4"/>',
        'trash-2'     => '<path d="M4 7h16"/><path d="M9 7V4.5h6V7"/><path d="M6 7l1 13h10l1-13"/><path d="M10.5 11v5M13.5 11v5"/>',
        'download'    => '<path d="M12 4v12"/><path d="m7.5 11.5 4.5 4.5 4.5-4.5"/><path d="M4 19h16"/>',
        'cloud-upload'=> '<path d="M7 17.5A3.5 3.5 0 0 1 7 10.5a5 5 0 0 1 9.6-1.2A3.8 3.8 0 0 1 17.5 17.5"/><path d="M12 20v-7"/><path d="m9 15.5 3-3 3 3"/>',
        'book-open'   => '<path d="M12 6.5C10 4.8 7 4.5 4 5v13c3-.5 6-.2 8 1.5 2-1.7 5-2 8-1.5V5c-3-.5-6-.2-8 1.5z"/><path d="M12 6.5v13"/>',
        'history'     => '<path d="M4 12a8 8 0 1 0 2.6-5.9"/><path d="M4 5v4h4"/><path d="M12 8v4.5l3 1.8"/>',
        'arrow-big-up'=> '<path d="M12 5.5 6 12h3.5v6.5h5V12H18z"/>',
        'mail-question' => '<rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="m4 7 8 6 8-6"/>',
        'share-2'     => '<circle cx="17.5" cy="6" r="2.6"/><circle cx="6.5" cy="12" r="2.6"/><circle cx="17.5" cy="18" r="2.6"/><path d="m8.8 10.8 6.4-3.4M8.8 13.2l6.4 3.4"/>',
        'scale'       => '<path d="M12 4v16"/><path d="M7 20h10"/><path d="M4 9h16"/><path d="M4 9 1.5 15h5z"/><path d="M20 9l-2.5 6h5z"/>',
        'user-check'  => '<circle cx="9.5" cy="8" r="3.3"/><path d="M3.5 20c0-3.2 2.7-5.5 6-5.5 1.3 0 2.5.4 3.5 1"/><path d="m15.5 17 2 2 3.5-3.5"/>',
        'building-2'  => '<path d="M4 20V6l7-3v17"/><path d="M11 20V9l8 3v8"/><path d="M2 20h20"/><path d="M7 8.5v.01M7 12v.01M15 14v.01M15 17v.01"/>',
        'loader'      => '<path d="M12 3.5v4M12 16.5v4M3.5 12h4M16.5 12h4M6 6l2.8 2.8M15.2 15.2 18 18M6 18l2.8-2.8M15.2 8.8 18 6"/>',
        'eye'         => '<path d="M2.5 12S6 6 12 6s9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/><circle cx="12" cy="12" r="2.6"/>',
        'user'        => '<circle cx="12" cy="8" r="3.5"/><path d="M4.5 20c0-3.6 3.2-6 7.5-6s7.5 2.4 7.5 6"/>',
    ];
    $d = $paths[$name] ?? $paths['circle-help'];
@endphp
<svg {{ $attributes->merge(['class' => 'inline-block shrink-0']) }} viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="{{ $stroke ?? '1.8' }}" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false">{!! $d !!}</svg>
