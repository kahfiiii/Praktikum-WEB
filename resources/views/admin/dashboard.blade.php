@extends('layouts.base')

@section('title', 'Dashboard Admin')

@section('body')
<div class="min-h-screen bg-canvas-warm pb-16">
    @include('moderasi._nav', ['active' => 'dashboard'])

    <main class="mx-auto max-w-[1160px] px-6 pt-8 lg:px-0 lg:pt-12">
        @if (session('status'))
            <div class="mb-6 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-sm font-medium text-lime-ink" role="status">
                {{ session('status') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div>
            <p class="font-display text-[12px] font-bold text-primary">Ringkasan platform</p>
            <h1 class="mt-1 font-display text-[32px] font-extrabold tracking-[-1px] text-ink lg:text-[36px]">Dashboard admin</h1>
            <p class="mt-1 text-[15px] text-ink-muted">Pantau konten, moderasi laporan, dan aktivitas belajar mahasiswa.</p>
        </div>

        {{-- Stat Cards Grid --}}
        <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            {{-- Materi --}}
            <div class="flex items-center gap-4 rounded-[18px] border border-line bg-surface p-5">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-[14px] bg-primary-soft text-primary">
                    <x-icon name="file-text" class="h-6 w-6" />
                </span>
                <div>
                    <p class="font-display text-[26px] font-extrabold text-ink">{{ $stats['materials'] }}</p>
                    <p class="text-[12px] font-medium text-ink-muted">Materi terunggah</p>
                </div>
            </div>

            {{-- Terverifikasi --}}
            <div class="flex items-center gap-4 rounded-[18px] border border-line bg-surface p-5">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-[14px] bg-lime-soft text-lime-ink">
                    <x-icon name="badge-check" class="h-6 w-6" />
                </span>
                <div>
                    <p class="font-display text-[26px] font-extrabold text-ink">{{ $stats['verified'] }}</p>
                    <p class="text-[12px] font-medium text-ink-muted">Materi terverifikasi</p>
                </div>
            </div>

            {{-- Kuis --}}
            <div class="flex items-center gap-4 rounded-[18px] border border-line bg-surface p-5">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-[14px] bg-violet-soft text-violet">
                    <x-icon name="list-checks" class="h-6 w-6" />
                </span>
                <div>
                    <p class="font-display text-[26px] font-extrabold text-ink">{{ $stats['attempts'] }}</p>
                    <p class="text-[12px] font-medium text-ink-muted">Kuis dikerjakan</p>
                </div>
            </div>

            {{-- Fakultas --}}
            <div class="flex items-center gap-4 rounded-[18px] border border-line bg-surface p-5">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-[14px] bg-coral-soft text-coral-ink">
                    <x-icon name="building-2" class="h-6 w-6" />
                </span>
                <div>
                    <p class="font-display text-[26px] font-extrabold text-ink">{{ $stats['faculties'] }}</p>
                    <p class="text-[12px] font-medium text-ink-muted">Fakultas tercakup</p>
                </div>
            </div>
        </div>

        {{-- Laporan Terbaru --}}
        <section class="mt-12">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display text-[20px] font-extrabold text-ink">Laporan terbaru</h2>
                    <p class="text-[13px] text-ink-muted">Laporan dari mahasiswa yang butuh ditinjau</p>
                </div>
                <a href="{{ route('moderasi.laporan') }}" class="font-display text-[13px] font-bold text-primary hover:underline">
                    Lihat semua laporan →
                </a>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($reports as $report)
                    <a href="{{ route('moderasi.laporan.detail', $report) }}"
                       class="group flex flex-col justify-between rounded-[18px] border border-line bg-surface p-5 transition hover:border-primary hover:shadow-sm">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="badge {{ $report->statusClasses() }} text-[10px]">
                                    {{ $report->statusLabel() }}
                                </span>
                                <span class="text-[11px] text-ink-muted">{{ $report->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="mt-3 font-display text-[15px] font-bold text-ink group-hover:text-primary">
                                {{ $report->reasonLabel() }}
                            </h3>
                            <p class="mt-1 line-clamp-2 text-[12px] leading-relaxed text-ink-muted">
                                {{ $report->contentLabel() }}
                            </p>
                        </div>
                        <div class="mt-4 border-t border-line/60 pt-3 text-[11px] font-semibold text-primary">
                            Tinjau laporan →
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-[18px] border border-line bg-surface p-8 text-center">
                        <p class="font-display text-[14px] font-bold text-ink">Semua beres! Belum ada laporan masuk.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Materi Terbaru --}}
        <section class="mt-12">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display text-[20px] font-extrabold text-ink">Materi terbaru</h2>
                    <p class="text-[13px] text-ink-muted">Materi yang baru saja diunggah oleh mahasiswa</p>
                </div>
                <a href="{{ route('admin.kelola') }}" class="font-display text-[13px] font-bold text-primary hover:underline">
                    Kelola rak materi →
                </a>
            </div>

            <div class="mt-4 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($recent as $material)
                    <a href="{{ route('materi.detail', $material) }}"
                       class="group flex items-start gap-3.5 rounded-[18px] border border-line bg-surface p-4 transition hover:border-primary hover:shadow-sm">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-[12px] {{ $material->file_type === 'pdf' ? 'bg-coral-soft text-coral-ink' : ($material->file_type === 'pptx' ? 'bg-[#fff5bd] text-[#a87a19]' : 'bg-violet-soft text-violet') }}">
                            <x-icon name="file-text" class="h-5 w-5" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-display text-[14px] font-bold text-ink group-hover:text-primary">{{ $material->title }}</p>
                            <p class="mt-0.5 text-[11px] text-ink-muted">{{ $material->course->name }} · {{ $material->author?->name ?? 'Anonim' }}</p>
                            <div class="mt-2 flex items-center gap-2">
                                @include('partials.badge-verifikasi', ['status' => $material->verification_status])
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
</div>
@endsection
