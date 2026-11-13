@extends('layouts.base')

@section('title', 'Detail Laporan')

@section('body')
<div class="min-h-screen bg-canvas-warm pb-10">
    @include('moderasi._nav', ['active' => 'laporan'])

    <main class="mx-auto max-w-5xl px-5 pt-5 lg:px-8 lg:pt-8">
        @if (session('status'))
            <div class="mb-4 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-xs font-medium text-lime-ink" role="status">{{ session('status') }}</div>
        @endif

        <a href="{{ route('moderasi.laporan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" />Daftar laporan
        </a>

        <div class="mt-3 flex items-start justify-between gap-3">
            <div>
                <h1 class="font-display text-[22px] font-extrabold leading-tight tracking-[-0.8px] text-ink">Laporan #{{ $report->id }}</h1>
                <p class="mt-1 text-xs text-ink-muted">{{ $report->contentLabel() }} · {{ $report->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="badge shrink-0 {{ $report->statusClasses() }}">{{ $report->statusLabel() }}</span>
        </div>

        <div class="card mt-4 p-4">
            <p class="text-[10px] font-bold uppercase tracking-wide text-ink-muted">Alasan pelapor</p>
            <p class="mt-1 font-display text-sm font-bold text-ink">{{ $report->reasonLabel() }}</p>
            @if ($report->detail)
                <p class="mt-2 rounded-[9px] bg-canvas-warm p-2.5 text-[11px] leading-relaxed text-ink-muted">{{ $report->detail }}</p>
            @endif
            <p class="mt-2.5 text-[10px] text-ink-muted">Dilaporkan oleh {{ $report->reporter?->name ?? 'Anonim' }}</p>
        </div>

        <div class="card mt-3 p-4">
            <p class="text-[10px] font-bold uppercase tracking-wide text-ink-muted">Pratinjau konten</p>
            @if ($content)
                @if ($report->reportable_type === 'material')
                    <p class="mt-2 font-display text-sm font-bold text-ink">{{ $content->title }}</p>
                    <p class="mt-1 text-[11px] text-ink-muted">{{ $content->file_type }} · {{ $content->author?->name ?? 'Anonim' }}</p>
                    <p class="mt-2 line-clamp-6 text-[11px] leading-relaxed text-ink">{{ \Illuminate\Support\Str::limit($content->body, 400) }}</p>
                    <a href="{{ route('materi.detail', $content) }}" class="btn-outline mt-3 w-full !py-2 !text-[11px]">Lihat materi lengkap</a>
                @else
                    <p class="mt-2 text-[11px] font-bold text-ink">{{ $content->author?->name ?? 'Anonim' }}</p>
                    <p class="mt-1 text-[11px] leading-relaxed text-ink">{{ $content->body }}</p>
                @endif
            @else
                <div class="mt-2 rounded-[9px] bg-gray-fill p-4 text-center">
                    <p class="text-[11px] font-bold text-ink-muted">Konten sudah dihapus</p>
                </div>
            @endif
        </div>

        <div class="mt-4 grid gap-2">
            @if ($report->status === 'baru')
                <form method="POST" action="{{ route('moderasi.laporan.proses', $report) }}">
                    @csrf
                    <button class="btn-primary w-full !text-xs">
                        <x-icon name="loader" class="h-4 w-4" />Proses laporan
                    </button>
                </form>
            @endif

            @if ($content)
                <form method="POST" action="{{ route('moderasi.laporan.hapus', $report) }}"
                      onsubmit="return confirm('Hapus konten yang dilaporkan? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button class="btn-danger w-full !text-xs">
                        <x-icon name="trash-2" class="h-4 w-4" />Hapus konten
                    </button>
                </form>
            @endif

            @if ($report->status !== 'selesai')
                <form method="POST" action="{{ route('moderasi.laporan.selesai', $report) }}">
                    @csrf
                    <button class="btn-lime w-full !text-xs">
                        <x-icon name="check-check" class="h-4 w-4" />Tandai selesai
                    </button>
                </form>
            @endif
        </div>
    </main>
</div>
@endsection
