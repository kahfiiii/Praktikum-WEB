@extends('layouts.base')

@section('title', 'Daftar Laporan')

@section('body')
<div class="min-h-screen bg-canvas-warm pb-10">
    @include('moderasi._nav', ['active' => 'laporan'])

    <main class="mx-auto max-w-5xl px-5 pt-5 lg:px-8 lg:pt-8">
        @if (session('status'))
            <div class="mb-4 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-xs font-medium text-lime-ink" role="status">{{ session('status') }}</div>
        @endif

        <div class="flex items-end justify-between">
            <div>
                <h1 class="font-display text-[22px] font-extrabold tracking-[-0.8px] text-ink">Daftar laporan</h1>
                <p class="mt-1 text-xs text-ink-muted">{{ $counts['baru'] }} laporan baru menunggu</p>
            </div>
        </div>

        <form method="GET" class="mt-4 space-y-2">
            <div class="relative">
                <x-icon name="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                <label class="sr-only" for="q">Cari laporan</label>
                <input id="q" name="q" value="{{ $q }}" placeholder="Cari alasan atau pelapor" class="field !pl-10">
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1">
                @foreach ([['', 'Semua', null], ['baru', 'Baru', $counts['baru']], ['diproses', 'Diproses', $counts['diproses']], ['selesai', 'Selesai', $counts['selesai']]] as [$val, $label, $count])
                    <a href="{{ route('moderasi.laporan', array_filter(['status' => $val, 'q' => $q])) }}"
                       class="chip shrink-0 {{ ($status ?? '') === $val ? 'chip-active' : '' }}">
                        {{ $label }}@if ($count !== null && $count > 0)<span class="ml-1.5">{{ $count }}</span>@endif
                    </a>
                @endforeach
            </div>
        </form>

        @if ($reports->isEmpty())
            <div class="card mt-4 p-6 text-center">
                <span class="mx-auto grid h-11 w-11 place-items-center rounded-full bg-lime-soft">
                    <x-icon name="shield-check" class="h-5 w-5 text-lime-ink" />
                </span>
                <p class="mt-3 font-display text-sm font-bold text-ink">Belum ada laporan</p>
                <p class="mt-1 text-xs text-ink-muted">
                    {{ $status || $q ? 'Tidak ada laporan yang cocok dengan filter ini.' : 'Semua konten bersih. Kerja bagus!' }}
                </p>
            </div>
        @else
            <div class="card mt-4 overflow-hidden">
                <div class="grid grid-cols-[1fr_auto] gap-2 border-b border-line bg-canvas-warm px-4 py-2.5 text-[10px] font-bold uppercase tracking-wide text-ink-muted">
                    <span>Konten &amp; alasan</span><span>Status</span>
                </div>
                @foreach ($reports as $report)
                    <a href="{{ route('moderasi.laporan.detail', $report) }}" class="block border-b border-line px-4 py-3 last:border-0 hover:bg-canvas-warm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="badge border-line-soft bg-gray-fill text-[9px] text-ink-muted">{{ $report->contentLabel() }}</span>
                                    <span class="text-[10px] text-ink-muted">#{{ $report->id }}</span>
                                </div>
                                <p class="mt-1 truncate font-display text-xs font-bold text-ink">{{ $report->reasonLabel() }}</p>
                                <p class="mt-0.5 text-[10px] text-ink-muted">oleh {{ $report->reporter?->name ?? 'Anonim' }} · {{ $report->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="badge shrink-0 {{ $report->statusClasses() }} text-[10px]">{{ $report->statusLabel() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        @endif
    </main>
</div>
@endsection
