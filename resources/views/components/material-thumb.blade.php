<span {{ $attributes->merge(['class' => 'grid shrink-0 place-items-center ' . $boxClasses() . ' ' . $toneClasses()]) }}>
    @if (strtoupper($type) === 'PPTX')
        <svg class="{{ $iconClasses() }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="4" width="18" height="13" rx="2.5" />
            <path d="M8 21h8M12 17v4M7.5 8.5h9M7.5 11.5h6" />
        </svg>
    @elseif (strtoupper($type) === 'PDF')
        <svg class="{{ $iconClasses() }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
            <path d="M14 3v5h5M8.5 13h7M8.5 16.5h4.5" />
        </svg>
    @else
        <svg class="{{ $iconClasses() }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4H19v16H6.5A2.5 2.5 0 0 1 4 17.5z" />
            <path d="M8 8h7M8 11.5h5" />
        </svg>
    @endif
</span>
