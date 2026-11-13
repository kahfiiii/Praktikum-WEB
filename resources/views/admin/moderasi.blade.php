@extends('layouts.base')

@section('title', 'Moderasi Komentar')

@section('body')
<div class="min-h-screen bg-canvas-warm pb-16">
    @include('moderasi._nav', ['active' => 'komentar'])

    <main class="mx-auto max-w-[1160px] px-6 pt-8 lg:px-0 lg:pt-12">
        @if (session('status'))
            <div class="mb-6 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-sm font-medium text-lime-ink" role="status">
                {{ session('status') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div>
            <p class="font-display text-[12px] font-bold text-primary">Ruang diskusi</p>
            <h1 class="mt-1 font-display text-[32px] font-extrabold tracking-[-1px] text-ink lg:text-[36px]">Moderasi komentar</h1>
            <p class="mt-1 text-[15px] text-ink-muted">Tinjau komentar dan diskusi mahasiswa agar tetap aman dan sesuai konteks belajar.</p>
        </div>

        {{-- Comments List (Figma Row Style: Frame 18:6235) --}}
        <div class="mt-8 space-y-4">
            @forelse ($comments as $comment)
                <div class="flex flex-col justify-between gap-4 rounded-[20px] border border-line bg-surface p-6 shadow-sm transition hover:border-primary/50 sm:flex-row sm:items-center">
                    {{-- Avatar inisial & Konten Komentar --}}
                    <div class="flex items-start gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-primary-soft font-display text-[12px] font-extrabold text-primary">
                            {{ $comment->author?->initials() ?? 'UB' }}
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-display text-[14px] font-bold text-ink">{{ $comment->author?->name ?? 'Anonim' }}</span>
                                <span class="text-[11px] text-ink-muted">· {{ $comment->created_at->diffForHumans() }}</span>
                                @if ($comment->reports > 0)
                                    <span class="badge border-coral bg-coral-soft text-[10px] text-coral-ink">
                                        {{ $comment->reports }} laporan
                                    </span>
                                @endif
                            </div>

                            <p class="mt-1.5 text-[14px] leading-relaxed text-ink">
                                {{ $comment->body }}
                            </p>

                            <p class="mt-2 text-[12px] font-medium text-ink-muted">
                                Di materi: <a href="{{ route('materi.detail', $comment->material_id) }}" class="text-primary hover:underline">{{ $comment->material?->title ?? 'Materi' }}</a>
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Aksi Hapus / Setujui --}}
                    <div class="flex items-center gap-2.5 self-end sm:self-center">
                        <form method="POST" action="{{ route('admin.moderasi.hapus', $comment) }}"
                              onsubmit="return confirm('Hapus komentar ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="inline-flex items-center gap-1.5 rounded-[12px] border border-coral bg-coral-soft px-4 py-2.5 font-display text-[12px] font-bold text-coral-ink transition hover:bg-coral hover:text-white">
                                <x-icon name="trash-2" class="h-3.5 w-3.5" />
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-[20px] border border-line bg-surface p-12 text-center">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-lime-soft text-lime-ink">
                        <x-icon name="check" class="h-6 w-6" />
                    </div>
                    <p class="mt-3 font-display text-[16px] font-bold text-ink">Tidak ada komentar yang perlu dimoderasi</p>
                    <p class="mt-1 text-[13px] text-ink-muted">Semua komentar diskusi bersih dan tertib.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
