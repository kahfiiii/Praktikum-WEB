@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<a href="{{ route('materi.detail', $material) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Kembali ke materi
</a>

<h1 class="mt-3 font-display text-[24px] font-extrabold tracking-[-0.9px] text-ink">Edit materi</h1>
<p class="mt-1 text-xs text-ink-muted">{{ $material->file_type }} · {{ $material->sizeLabel() }}</p>

<div class="mt-3 flex flex-wrap items-center gap-2">
    @include('partials.badge-verifikasi', ['status' => $material->verification_status])
</div>

@if (in_array($material->verification_status, ['verified', 'rejected'], true))
    <div class="mt-3 rounded-[13px] border border-line bg-canvas-warm p-3.5">
        <div class="flex gap-2.5">
            <x-icon name="alert-triangle" class="mt-0.5 h-4 w-4 shrink-0 text-primary" />
            <p class="text-[11px] leading-relaxed text-ink">
                Mengubah materi ini akan mengembalikan statusnya menjadi belum diverifikasi untuk ditinjau ulang oleh moderator.
            </p>
        </div>
    </div>
@endif

<div class="mt-3 rounded-[13px] border border-line bg-canvas-warm p-3.5">
    <p class="text-[11px] font-bold text-ink">Disclaimer hak cipta</p>
    <p class="mt-1 text-[11px] leading-relaxed text-ink-muted">
        Hanya unggah materi yang kamu buat sendiri atau yang boleh dibagikan. Jangan mengunggah buku,
        slide dosen, atau gambar berhak cipta tanpa izin pemiliknya.
    </p>
</div>

<form method="POST" action="{{ route('materi.update', $material) }}" enctype="multipart/form-data" class="mt-5 space-y-4" novalidate>
    @csrf
    @method('PUT')

    <div>
        <label class="label" for="title">Judul materi</label>
        <input id="title" name="title" type="text" required value="{{ old('title', $material->title) }}"
               class="field @error('title') !border-coral @enderror">
        @error('title')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="label" for="course_id">Mata kuliah</label>
        <select id="course_id" name="course_id" class="field">
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $material->course_id) == $course->id)>{{ $course->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="label" for="file">Ganti berkas materi (opsional)</label>
        <p class="text-[11px] text-ink-muted mb-1.5">Berkas saat ini: {{ $material->original_filename ?: $material->title }} ({{ $material->file_type }}, {{ $material->sizeLabel() }})</p>
        <input id="file" name="file" type="file" accept=".pdf,.docx,.pptx" class="field @error('file') !border-coral @enderror">
        <p class="mt-1 text-[11px] text-ink-muted">Format: PDF, DOCX, PPTX · Maksimal 5 MB</p>
        @error('file')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="label" for="body">Isi catatan</label>
        <textarea id="body" name="body" rows="10" class="field resize-y">{{ old('body', $material->body) }}</textarea>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('materi.detail', $material) }}" class="btn-outline flex-1 !text-xs">Batal</a>
        <button type="submit" class="btn-primary flex-1 !text-xs">Simpan perubahan</button>
    </div>
</form>
@endsection
