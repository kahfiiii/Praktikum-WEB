@extends('layouts.app')

@section('title', 'Pratinjau Kuis')

@section('content')
<a href="{{ route('kuis-saya') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Kembali ke kuis saya
</a>

<div class="mt-3 flex items-start justify-between gap-3">
    <div>
        <h1 class="font-display text-[24px] font-extrabold leading-tight tracking-[-0.9px] text-ink">{{ $quiz->title }}</h1>
        <p class="mt-1 text-xs text-ink-muted">{{ $quiz->questions->count() }} soal · {{ $quiz->duration_minutes }} menit · {{ $quiz->course->name }}</p>
    </div>
    <span class="badge shrink-0 {{ $quiz->isAiGenerated() ? 'border-violet bg-violet-soft text-violet' : 'border-primary bg-primary-soft text-primary' }}">
        {{ $quiz->isAiGenerated() ? 'Dibuat AI' : 'Bank soal' }}
    </span>
</div>

<div class="mt-5 grid gap-3 md:grid-cols-2">
    @foreach ($quiz->questions as $i => $question)
        <div class="card p-4">
            <p class="font-display text-[11px] font-extrabold text-primary">Soal {{ $i + 1 }}</p>
            <p class="mt-1.5 text-xs font-medium leading-relaxed text-ink">{{ $question->prompt }}</p>
            <div class="mt-2.5 grid gap-1.5">
                @foreach ($question->options as $index => $option)
                    <div class="flex items-center gap-2 rounded-[9px] border {{ $index === $question->answer_index ? 'border-lime-dark bg-lime-soft' : 'border-line-soft bg-surface' }} px-3 py-2">
                        <span class="grid h-4 w-4 place-items-center rounded-full border {{ $index === $question->answer_index ? 'border-lime-dark bg-lime-dark text-white' : 'border-line-soft' }} text-[8px] font-bold">
                            {{ $index === $question->answer_index ? '✓' : '' }}
                        </span>
                        <span class="text-[11px] text-ink">{{ $option }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<div class="mt-5 flex gap-2">
    <a href="{{ route('kuis.pilih') }}" class="btn-outline flex-1 !text-xs">Buat ulang</a>
    <form method="POST" action="{{ route('kuis.publish', $quiz) }}" class="flex-1">
        @csrf
        <button class="btn-primary w-full !text-xs">Simpan kuis</button>
    </form>
</div>
@endsection
