@extends('layouts.app')

@section('title', 'Kerjakan Kuis')

@section('content')
<a href="{{ route('kuis-saya') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Kembali
</a>

<h1 class="mt-3 font-display text-[24px] font-extrabold leading-tight tracking-[-0.9px] text-ink">{{ $quiz->title }}</h1>
<p class="mt-1 text-xs text-ink-muted">{{ $quiz->questions->count() }} soal · {{ $quiz->duration_minutes }} menit</p>

<form method="POST" action="{{ route('kuis.submit', $quiz) }}" class="mt-5 grid gap-3">
    @csrf
    @foreach ($quiz->questions as $i => $question)
        <div class="card p-4" data-option-group>
            <p class="font-display text-[11px] font-extrabold text-primary">Soal {{ $i + 1 }}</p>
            <p class="mt-1.5 text-xs font-medium leading-relaxed text-ink">{{ $question->prompt }}</p>
            <div class="mt-2.5 grid gap-1.5">
                @foreach ($question->options as $index => $option)
                    <label data-option class="flex cursor-pointer items-center gap-2 rounded-[9px] border border-line-soft bg-surface px-3 py-2.5 transition hover:border-primary" for="q{{ $question->id }}-{{ $index }}">
                        <input id="q{{ $question->id }}-{{ $index }}" type="radio" name="answers[{{ $question->id }}]" value="{{ $index }}" class="sr-only">
                        <span data-dot class="grid h-4 w-4 shrink-0 place-items-center rounded-full border border-line-soft bg-primary text-white opacity-0">
                            <x-icon name="check" class="h-2.5 w-2.5" />
                        </span>
                        <span class="text-[11px] text-ink">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach

    <button class="btn-primary mt-2 w-full">Selesai dan lihat skor</button>
</form>
@endsection
