@extends('layouts.base')

@section('title', 'Kelola Mata Kuliah')

@section('body')
<div class="min-h-screen bg-canvas-warm pb-16">
    @include('moderasi._nav', ['active' => 'mata kuliah'])

    <main class="mx-auto max-w-[1160px] px-6 pt-8 lg:px-0 lg:pt-12">
        @if (session('status'))
            <div class="mb-6 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-sm font-medium text-lime-ink" role="status">
                {{ session('status') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-display text-[12px] font-bold text-primary">Panel admin</p>
                <h1 class="mt-1 font-display text-[32px] font-extrabold tracking-[-1px] text-ink lg:text-[36px]">Kelola mata kuliah</h1>
                <p class="mt-1 text-[15px] text-ink-muted">Jaga rak materi tetap rapi dan mudah ditemukan mahasiswa.</p>
            </div>
            <a href="#modal-tambah" class="inline-flex shrink-0 items-center gap-2 rounded-[13px] bg-primary px-5 py-3 font-display text-[14px] font-bold text-white transition hover:bg-primary-dark" style="box-shadow: 0 4px 0 #3747bb">
                <x-icon name="plus" class="h-4 w-4" />
                Tambah mata kuliah
            </a>
        </div>

        {{-- Table Container Sesuai Figma --}}
        <div class="mt-8 overflow-hidden rounded-[20px] border border-line bg-surface shadow-sm">
            <div class="grid grid-cols-[140px_1fr_120px_120px] items-center gap-4 border-b border-line bg-canvas-warm px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-ink-muted">
                <span>Kode</span>
                <span>Nama mata kuliah</span>
                <span>Jumlah materi</span>
                <span class="text-right">Aksi</span>
            </div>

            <div class="divide-y divide-line/70">
                @foreach ($courses as $course)
                    <div class="grid grid-cols-[140px_1fr_120px_120px] items-center gap-4 px-6 py-4 transition hover:bg-canvas-warm/50">
                        {{-- Kode --}}
                        <span class="font-display text-[13px] font-bold text-primary">
                            {{ $course->code }}
                        </span>

                        {{-- Nama & Deskripsi --}}
                        <div class="min-w-0">
                            <p class="font-display text-[15px] font-bold text-ink">{{ $course->name }}</p>
                            <p class="truncate text-[12px] text-ink-muted">{{ $course->faculty }} · Semester {{ $course->semester }}</p>
                        </div>

                        {{-- Jumlah Materi --}}
                        <span class="text-[13px] font-medium text-ink-muted">
                            {{ $course->materials_count }} materi
                        </span>

                        {{-- Aksi --}}
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('mata-kuliah.detail', $course) }}" aria-label="Lihat {{ $course->name }}"
                               class="grid h-8 w-8 place-items-center rounded-[10px] bg-primary-soft text-primary transition hover:bg-primary hover:text-white" title="Lihat materi">
                                <x-icon name="eye" class="h-4 w-4" />
                            </a>
                            <form method="POST" action="{{ route('admin.kelola.destroy', $course) }}"
                                  onsubmit="return confirm('Hapus {{ $course->name }}? Materi dan kuis di dalamnya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button aria-label="Hapus {{ $course->name }}" class="grid h-8 w-8 place-items-center rounded-[10px] bg-coral-soft text-coral-ink transition hover:bg-coral hover:text-white" title="Hapus">
                                    <x-icon name="trash-2" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

{{-- Modal tambah mata kuliah (Figma Modal Style) --}}
<div id="modal-tambah" class="fixed inset-0 z-40 hidden items-center justify-center bg-ink/40 p-5 target:flex backdrop-blur-xs">
    <div class="w-full max-w-[420px] rounded-[24px] bg-surface p-7 shadow-[var(--shadow-modal)]">
        <div class="flex items-center justify-between border-b border-line pb-4">
            <div>
                <p class="font-display text-lg font-extrabold text-ink">Tambah mata kuliah</p>
                <p class="text-xs text-ink-muted">Tambahkan kategori ke rak platform</p>
            </div>
            <a href="#" aria-label="Tutup" class="grid h-8 w-8 place-items-center rounded-full bg-gray-fill text-ink hover:bg-line">
                <x-icon name="x" class="h-4 w-4" />
            </a>
        </div>
        <form method="POST" action="{{ route('admin.kelola.store') }}" class="mt-5 space-y-4" novalidate>
            @csrf
            <div>
                <label class="label" for="name">Nama mata kuliah</label>
                <input id="name" name="name" required value="{{ old('name') }}" placeholder="Contoh: Struktur Data" class="field">
                @error('name')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label" for="code">Kode MK</label>
                    <input id="code" name="code" required value="{{ old('code') }}" placeholder="CIF62004" class="field">
                </div>
                <div>
                    <label class="label" for="semester">Semester</label>
                    <select id="semester" name="semester" required class="field">
                        <option value="Gasal">Gasal</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="label" for="faculty">Fakultas</label>
                <input id="faculty" name="faculty" required value="Fakultas Ilmu Komputer" class="field">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <a href="#" class="btn-ghost !text-xs">Batal</a>
                <button type="submit" class="btn-primary !text-xs">Simpan mata kuliah</button>
            </div>
        </form>
    </div>
</div>
@endsection
