{{-- Logo STUDORA: Icon "S" Playful Tilted + Coral Shadow + Wordmark (Sesuai Desain Figma 100%) --}}
<a href="{{ auth()->check() ? route('beranda') : route('landing') }}" {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center gap-2.5 transition-transform hover:scale-[1.02] active:scale-[0.98]']) }}>
    <div class="relative flex items-center justify-center {{ $wrapperSize }}">
        <div class="-rotate-6 flex-none">
            <div class="{{ $boxSize }} flex items-center justify-center bg-[#5568ee] text-white select-none shadow-[inset_0_-2px_0_rgba(23,37,75,0.15)]"
                 style="filter: drop-shadow({{ $shadowOffset }} 0px #ff7868);">
                <div class="rotate-6 flex items-center justify-center">
                    <span class="font-display font-extrabold {{ $letterSize }} leading-none text-white tracking-[-0.7px]">
                        S
                    </span>
                </div>
            </div>
        </div>
    </div>
    @if ($showWordmark)
        <span class="font-display {{ $textSize }} font-extrabold tracking-[-1px] {{ $inverse ? 'text-white' : 'text-[#17254b]' }}">STUDORA</span>
    @endif
</a>
