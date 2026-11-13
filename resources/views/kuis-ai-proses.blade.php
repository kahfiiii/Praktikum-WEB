@extends('layouts.app')

@section('title', 'AI Memproses')

@section('content')
<div class="pt-6">
    <h1 class="font-display text-[24px] font-extrabold tracking-[-0.9px] text-ink">AI sedang menyusun soal</h1>
    <p class="mt-1 text-xs text-ink-muted">{{ $course?->name ?? 'Mata kuliah' }} · {{ $payload['topic'] ?? 'Topik' }}</p>

    <div class="card mt-5 p-4" data-ai-progress>
        <div class="grid gap-3">
            @foreach (['Membaca materi dan topik', 'Menyusun soal dan pilihan jawaban', 'Menyiapkan pratinjau'] as $i => $step)
                <div class="flex items-center gap-3" data-step>
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-gray-fill" data-dot>
                        <x-icon name="check" class="h-3.5 w-3.5 text-white" />
                    </span>
                    <span class="text-xs font-medium {{ $i === 0 ? 'text-ink' : 'text-ink-muted' }}">{{ $step }}</span>
                </div>
            @endforeach
        </div>
        <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-gray-fill">
            <div class="h-full w-1/3 animate-pulse rounded-full bg-primary"></div>
        </div>
        <p class="mt-2 text-[11px] text-ink-muted">Progres 2 dari 3 langkah</p>
    </div>

    <form id="ai-form" method="POST" action="{{ route('kuis.ai.generate') }}" class="sr-only">
        @csrf
        <input type="hidden" name="course_id" value="{{ $payload['course_id'] ?? '' }}">
        <input type="hidden" name="topic" value="{{ $payload['topic'] ?? '' }}">
        <input type="hidden" name="count" value="{{ $payload['count'] ?? 8 }}">
        <input type="hidden" name="type" value="{{ $payload['type'] ?? 'pilihan-ganda' }}">
    </form>

    <a href="{{ route('kuis.pilih') }}" class="btn-outline mt-5 w-full !text-xs">Batalkan</a>
</div>
@endsection
