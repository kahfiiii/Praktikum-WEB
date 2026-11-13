{{-- Sparkle 4 sudut, bentuknya sama seperti glyph ✦ di Figma --}}
<svg {{ $attributes->merge(['class' => 'pointer-events-none absolute select-none']) }}
     width="{{ $size }}" height="{{ $size }}" viewBox="0 0 100 100"
     style="fill: {{ $color }}; opacity: {{ $opacity }}; transform: rotate({{ $rotate }}deg);"
     aria-hidden="true" focusable="false">
    <path d="M50 0 L58.5 41.5 L100 50 L58.5 58.5 L50 100 L41.5 58.5 L0 50 L41.5 41.5 Z" />
</svg>
