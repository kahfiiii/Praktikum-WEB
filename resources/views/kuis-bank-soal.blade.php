@extends('layouts.app')

@section('title', 'Buat Kuis Bank Soal')

@section('content')
<a href="{{ route('kuis.pilih') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Pilih metode
</a>

<h1 class="mt-3 font-display text-[24px] font-extrabold tracking-[-0.9px] text-ink">Buat kuis dari bank soal</h1>
<p class="mt-1 text-xs text-ink-muted">{{ $questions->count() }} soal tersedia di bank soal.</p>

<form method="POST" action="{{ route('kuis.bank-soal.store') }}" class="mt-5 space-y-4" novalidate>
    @csrf

    <div>
        <label class="label" for="title">Judul kuis</label>
        <input id="title" name="title" type="text" required value="{{ old('title') }}" placeholder="Latihan Linked List Dasar"
               class="field @error('title') !border-coral @enderror">
        @error('title')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="label" for="course_id">Mata kuliah</label>
            <select id="course_id" name="course_id" required class="field @error('course_id') !border-coral @enderror">
                <option value="">Pilih</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label" for="duration_minutes">Durasi (menit)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" min="5" max="120" value="{{ old('duration_minutes', 15) }}" class="field">
        </div>
    </div>

    <fieldset>
        <legend class="label">Pilih soal</legend>
        @error('question_ids')<p class="mb-2 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
        <div class="grid gap-2">
            @foreach ($questions as $question)
                <label class="card flex cursor-pointer items-start gap-3 p-3.5 transition hover:border-primary">
                    <input type="checkbox" name="question_ids[]" value="{{ $question->id }}"
                           class="mt-0.5 h-4 w-4 shrink-0 rounded border-line-soft text-primary focus:ring-primary">
                    <span class="text-xs leading-relaxed text-ink">{{ $question->prompt }}</span>
                </label>
            @endforeach
        </div>
    </fieldset>

    <div class="flex gap-2">
        <a href="{{ route('kuis.pilih') }}" class="btn-outline flex-1 !text-xs">Batal</a>
        <button class="btn-primary flex-1 !text-xs">Simpan kuis</button>
    </div>
</form>
@endsection
