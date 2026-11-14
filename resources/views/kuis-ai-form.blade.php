@extends('layouts.app')

@section('title', 'Buat Kuis dengan AI')

@section('content')
<a href="{{ route('kuis.pilih') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Pilih metode
</a>

<h1 class="mt-3 font-display text-[24px] font-extrabold tracking-[-0.9px] text-ink">Buat kuis dengan AI</h1>
<p class="mt-1 text-xs text-ink-muted">AI membaca materimu lalu menyusun soal.</p>

<form method="POST" action="{{ route('kuis.ai.proses') }}" class="mt-5 space-y-4" novalidate>
    @csrf

    <div>
        <label class="label" for="course_id">Mata kuliah</label>
        <select id="course_id" name="course_id" required class="field @error('course_id') !border-coral @enderror">
            <option value="">Pilih mata kuliah</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
            @endforeach
        </select>
        @error('course_id')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="label" for="topic">Topik materi</label>
        <input id="topic" name="topic" type="text" required value="{{ old('topic') }}" placeholder="Linked list dan pointer"
               class="field @error('topic') !border-coral @enderror">
        @error('topic')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="label" for="count">Jumlah soal</label>
        <input id="count" name="count" type="number" min="3" max="15" value="{{ old('count', 8) }}" class="field">
    </div>

    <div>
        <label class="label" for="type">Tipe soal</label>
        <select id="type" name="type" class="field">
            <option value="pilihan-ganda" @selected(old('type') === 'pilihan-ganda')>Pilihan ganda</option>
            <option value="benar-salah" @selected(old('type') === 'benar-salah')>Benar atau salah</option>
            <option value="esai" @selected(old('type') === 'esai')>Esai</option>
        </select>
    </div>

    <button class="btn-primary w-full">
        <x-icon name="sparkles" class="h-4 w-4" />Generate soal
    </button>
</form>

<p class="mt-3 text-center text-[11px] text-ink-muted">Soal dibuat secara otomatis oleh model AI melalui OmniRoute Gateway.</p>
@endsection
