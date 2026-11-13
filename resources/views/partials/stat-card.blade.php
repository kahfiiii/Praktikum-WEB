<div class="card p-3.5">
    <span class="grid h-8 w-8 place-items-center rounded-[9px] {{ $tone ?? 'bg-primary-soft text-primary' }}">
        <x-icon :name="$icon" class="h-4 w-4" />
    </span>
    <p class="mt-2.5 font-display text-[22px] font-extrabold leading-none text-ink">{{ $value }}</p>
    <p class="mt-1 text-[10px] font-medium leading-tight text-ink-muted">{{ $label }}</p>
</div>
