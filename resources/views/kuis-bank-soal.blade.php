@extends('layouts.app')

@section('title', 'Buat Kuis Bank Soal')

@section('content')
<a href="{{ route('kuis.pilih') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Pilih metode
</a>

<div class="mt-3 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="font-display text-[24px] font-extrabold tracking-[-0.9px] text-ink">Buat kuis dari bank soal</h1>
        <p class="mt-1 text-xs text-ink-muted">{{ $questions->count() }} soal tersedia di bank soal.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('kuis.bank-soal.template') }}" class="btn-outline !px-3 !py-1.5 !text-[11px]">
            <x-icon name="download" class="h-3.5 w-3.5" />Template CSV
        </a>
        <a href="#modal-unggah-bank" class="btn-primary !px-3 !py-1.5 !text-[11px]">
            <x-icon name="upload" class="h-3.5 w-3.5" />Unggah ke Bank Soal
        </a>
    </div>
</div>

@if (session('status'))
    <div class="mt-4 rounded-[13px] border border-lime-dark bg-lime-soft p-3 text-xs font-bold text-lime-ink">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('kuis.bank-soal.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4" novalidate>
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

    {{-- Opsi Unggah Berkas Soal Langsung --}}
    <div class="card p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-bold text-ink">Unggah berkas soal baru (Opsional)</p>
                <p class="mt-0.5 text-[11px] text-ink-muted">Tambahkan soal langsung dari berkas CSV atau JSON Anda.</p>
            </div>
            <a href="{{ route('kuis.bank-soal.template') }}" class="text-[11px] font-bold text-primary underline">Contoh format</a>
        </div>
        <input type="file" id="file_soal" name="file_soal" accept=".csv,.json,.txt" class="mt-3 field !py-2 !text-xs @error('file_soal') !border-coral @enderror">
        @error('file_soal')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
    </div>

    <fieldset>
        <legend class="label">Atau pilih dari bank soal yang sudah ada</legend>
        @error('question_ids')<p class="mb-2 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
        <div class="grid gap-2 max-h-[360px] overflow-y-auto pr-1">
            @forelse ($questions as $question)
                <label class="card flex cursor-pointer items-start gap-3 p-3.5 transition hover:border-primary">
                    <input type="checkbox" name="question_ids[]" value="{{ $question->id }}"
                           class="mt-0.5 h-4 w-4 shrink-0 rounded border-line-soft text-primary focus:ring-primary">
                    <span class="text-xs leading-relaxed text-ink">{{ $question->prompt }}</span>
                </label>
            @empty
                <p class="text-xs italic text-ink-muted">Belum ada soal di bank soal. Silakan unggah berkas soal di atas.</p>
            @endforelse
        </div>
    </fieldset>

    <div class="flex gap-2">
        <a href="{{ route('kuis.pilih') }}" class="btn-outline flex-1 !text-xs">Batal</a>
        <button class="btn-primary flex-1 !text-xs">Simpan kuis</button>
    </div>
</form>

{{-- Modal Unggah ke Bank Soal Umum --}}
<div id="modal-unggah-bank" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/40 p-5 target:flex">
    <div class="w-full max-w-[420px] rounded-[26px] bg-surface p-5 shadow-[var(--shadow-modal)]">
        <div class="flex items-center justify-between">
            <p class="font-display text-base font-extrabold text-ink">Unggah ke Bank Soal</p>
            <a href="#" aria-label="Tutup" class="grid h-8 w-8 place-items-center rounded-full bg-gray-fill">
                <x-icon name="x" class="h-4 w-4 text-ink" />
            </a>
        </div>
        <p class="mt-1 text-xs text-ink-muted">Soal yang diunggah akan masuk ke bank soal umum dan dapat digunakan di semua kuis.</p>

        <form method="POST" action="{{ route('kuis.bank-soal.upload') }}" enctype="multipart/form-data" class="mt-4 space-y-3" novalidate>
            @csrf
            <div>
                <label class="label" for="upload_course_id">Mata kuliah (Opsional)</label>
                <select id="upload_course_id" name="course_id" class="field">
                    <option value="">Umum</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="bank_file">Pilih berkas CSV atau JSON</label>
                <input type="file" id="bank_file" name="file" required accept=".csv,.json,.txt" class="field !py-2 !text-xs">
                <p class="mt-1 text-[11px] text-ink-muted">Kolom CSV: Pertanyaan, Opsi A, B, C, D, Kunci (A/B/C/D)</p>
            </div>
            <div class="flex gap-2 pt-2">
                <a href="#" class="btn-outline flex-1 !text-xs">Batal</a>
                <button type="submit" class="btn-primary flex-1 !text-xs">Unggah soal</button>
            </div>
        </form>
    </div>
</div>
@endsection
