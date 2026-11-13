@extends('layouts.app')

@section('title', $material->title)

@section('content')
<a href="{{ route('mata-kuliah.detail', $material->course) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Kembali ke {{ $material->course->name }}
</a>

<h1 class="mt-3 font-display text-[24px] font-extrabold leading-tight tracking-[-0.9px] text-ink">{{ $material->title }}</h1>

<div class="mt-3 flex flex-wrap items-center gap-2">
    @include('partials.badge-verifikasi', ['status' => $material->verification_status])
    <span class="badge {{ $material->typeClasses() }}">{{ $material->file_type }}</span>
    <span class="text-[11px] text-ink-muted">{{ $material->sizeLabel() }} · {{ $material->author?->name ?? 'Anonim' }}</span>
</div>

@if ($material->verification_status === 'rejected' && $material->rejection_reason)
    <div class="mt-3 rounded-[13px] border border-coral bg-coral-soft p-3.5">
        <p class="text-[11px] font-bold text-coral-ink">Alasan penolakan</p>
        <p class="mt-1 text-[11px] leading-relaxed text-coral-ink">{{ $material->rejection_reason }}</p>
    </div>
@endif

<div class="mt-3 flex flex-wrap gap-2">
    {{-- Unduh Materi --}}
    @if ($material->file_path)
        <a href="{{ route('materi.unduh', $material) }}" class="btn-outline !px-3 !py-2 !text-[11px]">
            <x-icon name="download" class="h-3.5 w-3.5" />Unduh
        </a>
    @else
        <button type="button" disabled title="File belum tersedia" class="btn-outline !px-3 !py-2 !text-[11px] opacity-60 cursor-not-allowed">
            <x-icon name="download" class="h-3.5 w-3.5" />File belum tersedia
        </button>
    @endif

    {{-- Pemilik Materi: Edit & Hapus --}}
    @if (auth()->id() === $material->user_id)
        <a href="{{ route('materi.edit', $material) }}" class="btn-outline !px-3 !py-2 !text-[11px]">
            <x-icon name="pencil" class="h-3.5 w-3.5" />Edit
        </a>
        <form method="POST" action="{{ route('materi.destroy', $material) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus materi ini?')" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger !px-3 !py-2 !text-[11px]">
                <x-icon name="trash-2" class="h-3.5 w-3.5" />Hapus
            </button>
        </form>
    @endif

    {{-- Moderasi Materi: Hanya untuk moderator/admin fakultas terkait dan bukan pemilik sendiri --}}
    @php
        $materialFaculty = $material->faculty ?? $material->course?->faculty;
        $canModerateMaterial = auth()->user()->canModerateContent($materialFaculty) && auth()->id() !== $material->user_id;
    @endphp

    @if ($canModerateMaterial)
        @if ($material->verification_status !== 'verified')
            <form method="POST" action="{{ route('moderasi.materi.verifikasi', $material) }}">
                @csrf
                <button class="btn-lime !px-3 !py-2 !text-[11px]">
                    <x-icon name="badge-check" class="h-3.5 w-3.5" />Verifikasi
                </button>
            </form>
        @endif
        @if ($material->verification_status !== 'rejected')
            <a href="#modal-tolak" class="btn-danger !px-3 !py-2 !text-[11px]">
                <x-icon name="x-circle" class="h-3.5 w-3.5" />Tolak
            </a>
        @endif
    @endif

    {{-- Laporkan Materi: Hanya jika bukan pemilik --}}
    @if (auth()->id() !== $material->user_id)
        <a href="#modal-lapor-materi" class="btn-outline !px-3 !py-2 !text-[11px]">
            <x-icon name="flag" class="h-3.5 w-3.5" />Laporkan
        </a>
    @endif

    @php
        $waText = rawurlencode($material->title.' — '.route('materi.detail', $material));
    @endphp
    <a href="https://wa.me/?text={{ $waText }}" target="_blank" rel="noopener" class="btn-outline !px-3 !py-2 !text-[11px]">
        <x-icon name="share-2" class="h-3.5 w-3.5" />WhatsApp
    </a>
</div>

<article class="card mt-4 whitespace-pre-line p-5 text-sm leading-[1.75] text-ink">{{ $material->body }}</article>

<div class="mt-4 flex gap-2">
    <a href="{{ route('kuis.pilih', ['course_id' => $material->course_id]) }}" class="btn-primary flex-1 !text-xs">
        <x-icon name="sparkles" class="h-4 w-4" />Uji pemahamanmu
    </a>
    <a href="{{ route('kuis.pilih', ['course_id' => $material->course_id]) }}" class="btn-outline flex-1 !text-xs">Buat kuis</a>
</div>

<section class="mt-7">
    <div class="flex items-end justify-between">
        <h2 class="font-display text-base font-extrabold text-ink">Diskusi</h2>
        <span class="text-[11px] text-ink-muted">{{ $comments->count() }} diskusi</span>
    </div>

    <div class="mt-3 rounded-[13px] border border-line bg-canvas-warm p-3.5">
        <div class="flex gap-2.5">
            <x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0 text-primary" />
            <p class="text-[11px] leading-relaxed text-ink-muted">
                Jawaban di sini berasal dari sesama mahasiswa dan bukan jawaban resmi dosen.
                Untuk kepastian, konfirmasi ke dosen pengampu.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('materi.komentar', $material) }}" class="mt-3">
        @csrf
        <input type="hidden" name="kind" value="question">
        <label class="label" for="body">Tulis pertanyaan</label>
        <textarea id="body" name="body" rows="2" required placeholder="Tulis pertanyaan atau catatan..." class="field resize-none @error('body') !border-coral @enderror">{{ old('body') }}</textarea>
        @error('body')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
        <button class="btn-primary mt-2 w-full !py-2.5 !text-xs">
            <x-icon name="send" class="h-4 w-4" />Kirim pertanyaan
        </button>
    </form>

    @if ($comments->isEmpty())
        <div class="card mt-4 p-6 text-center">
            <span class="mx-auto grid h-11 w-11 place-items-center rounded-full bg-gray-fill">
                <x-icon name="messages-square" class="h-5 w-5 text-ink-muted" />
            </span>
            <p class="mt-3 font-display text-sm font-bold text-ink">Belum ada diskusi</p>
            <p class="mt-1 text-xs text-ink-muted">Jadi yang pertama bertanya di materi ini.</p>
        </div>
    @endif

    <div class="mt-4 grid gap-3 md:grid-cols-2">
        @foreach ($comments as $comment)
            @php
                $answers = $comment->replies->sortByDesc(fn ($r) => $r->is_verified)->values();
                $hasVerified = $answers->contains(fn ($r) => $r->is_verified);
                $stateLabel = $comment->is_verified || $hasVerified ? 'Terverifikasi' : ($answers->count() > 0 ? 'Terjawab' : 'Belum terjawab');
                $stateClass = $stateLabel === 'Terverifikasi'
                    ? 'border-lime-dark bg-lime-soft text-lime-ink'
                    : ($stateLabel === 'Terjawab' ? 'border-primary bg-primary-soft text-primary' : 'border-line-soft bg-gray-fill text-ink-muted');
                $canDeleteComment = auth()->user()->canModerateContent($materialFaculty);
            @endphp
            <article class="card p-3.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="grid h-7 w-7 place-items-center rounded-full bg-lime-soft font-display text-[10px] font-extrabold text-lime-ink">{{ $comment->author?->initials() ?? 'AN' }}</span>
                    <p class="text-xs font-bold text-ink">{{ $comment->author?->name ?? 'Anonim' }}</p>
                    <span class="badge border-primary bg-primary-soft text-[10px] text-primary">Pertanyaan</span>
                    <span class="badge {{ $stateClass }} text-[10px]">{{ $stateLabel }}</span>
                    <span class="ml-auto text-[10px] text-ink-muted">{{ $comment->created_at->diffForHumans() }}</span>
                </div>

                <p class="mt-2 text-xs leading-relaxed text-ink">{{ $comment->body }}</p>

                <div class="mt-2.5 flex flex-wrap items-center gap-2">
                    {{-- Upvote --}}
                    @if (auth()->id() !== $comment->user_id)
                        <form method="POST" action="{{ route('komentar.upvote', $comment) }}" class="inline">
                            @csrf
                            <button class="inline-flex items-center gap-1.5 rounded-full border border-line-soft px-2.5 py-1 text-[10px] font-bold text-ink-muted hover:border-primary hover:text-primary">
                                <x-icon name="arrow-big-up" class="h-3 w-3" />{{ $comment->upvotes }} berguna
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-line-soft bg-gray-fill px-2.5 py-1 text-[10px] font-bold text-ink-muted cursor-default" title="Tidak dapat upvote komentar sendiri">
                            <x-icon name="arrow-big-up" class="h-3 w-3" />{{ $comment->upvotes }} berguna
                        </span>
                    @endif

                    {{-- Laporkan Komentar: Hanya jika bukan milik sendiri --}}
                    @if (auth()->id() !== $comment->user_id)
                        <a href="#modal-lapor-komentar" onclick="document.getElementById('form-lapor-komentar').action = '{{ url('/komentar') }}/' + {{ $comment->id }} + '/lapor';" class="inline-flex items-center gap-1 rounded-full border border-line-soft px-2.5 py-1 text-[10px] font-bold text-ink-muted hover:border-coral hover:text-coral-ink">
                            <x-icon name="flag" class="h-3 w-3" />Laporkan
                        </a>
                    @endif

                    {{-- Hapus Komentar Langsung oleh Moderator/Admin (FR-19) --}}
                    @if ($canDeleteComment)
                        <form method="POST" action="{{ route('komentar.destroy', $comment) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus pertanyaan ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 rounded-full border border-line-soft px-2.5 py-1 text-[10px] font-bold text-coral hover:border-coral">
                                <x-icon name="trash-2" class="h-2.5 w-2.5" />Hapus
                            </button>
                        </form>
                    @endif

                    {{-- Salin Ringkasan untuk Konfirmasi Dosen (Item 13) --}}
                    @if ($comment->isQuestion() && ! $comment->is_verified && ! $hasVerified)
                        <button type="button"
                                onclick="salinRingkasanDosen({{ Js::from($material->title) }}, {{ Js::from($material->course->name) }}, {{ Js::from(url()->current()) }}, {{ Js::from($comment->body) }}, {{ Js::from($answers->map(fn($a) => ['body' => $a->body, 'verified' => $a->is_verified, 'author' => $a->author?->name ?? 'Anonim'])->toArray()) }})"
                                class="inline-flex items-center gap-1 rounded-full border border-line-soft px-2.5 py-1 text-[10px] font-bold text-ink-muted hover:border-violet hover:text-violet">
                            <x-icon name="copy" class="h-3 w-3" />Salin ringkasan
                        </button>
                    @endif
                </div>

                @if ($answers->isNotEmpty())
                    <div class="mt-3 grid gap-2 border-t border-line pt-3">
                        @foreach ($answers as $reply)
                            <div class="rounded-[10px] p-2.5 {{ $reply->is_verified ? 'border border-lime-dark bg-lime-soft' : 'bg-gray-fill' }}">
                                @if ($reply->is_verified)
                                    <span class="badge border-lime-dark bg-surface text-[9px] text-lime-ink">
                                        <x-icon name="badge-check" class="h-2.5 w-2.5" />Terverifikasi
                                    </span>
                                @endif
                                <div class="mt-1.5 flex items-center gap-2">
                                    <p class="text-[11px] font-bold text-ink">{{ $reply->author?->name ?? 'Anonim' }}</p>
                                    <span class="ml-auto text-[10px] text-ink-muted">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 text-[11px] leading-relaxed text-ink">{{ $reply->body }}</p>
                                @if ($reply->source_ref)
                                    <p class="mt-1.5 flex items-center gap-1 text-[10px] italic text-ink-muted">
                                        <x-icon name="book-open" class="h-3 w-3" />Sumber: {{ $reply->source_ref }}
                                    </p>
                                @endif
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    {{-- Upvote Balasan --}}
                                    @if (auth()->id() !== $reply->user_id)
                                        <form method="POST" action="{{ route('komentar.upvote', $reply) }}" class="inline">
                                            @csrf
                                            <button class="inline-flex items-center gap-1 rounded-full border border-line-soft bg-surface px-2 py-0.5 text-[10px] font-bold text-ink-muted hover:border-primary hover:text-primary">
                                                <x-icon name="arrow-big-up" class="h-2.5 w-2.5" />{{ $reply->upvotes }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full border border-line-soft bg-surface px-2 py-0.5 text-[10px] font-bold text-ink-muted cursor-default">
                                            <x-icon name="arrow-big-up" class="h-2.5 w-2.5" />{{ $reply->upvotes }}
                                        </span>
                                    @endif

                                    {{-- Laporkan Balasan --}}
                                    @if (auth()->id() !== $reply->user_id)
                                        <a href="#modal-lapor-komentar" onclick="document.getElementById('form-lapor-komentar').action = '{{ url('/komentar') }}/' + {{ $reply->id }} + '/lapor';" class="inline-flex items-center gap-1 rounded-full border border-line-soft bg-surface px-2 py-0.5 text-[10px] font-bold text-ink-muted hover:border-coral hover:text-coral-ink">
                                            <x-icon name="flag" class="h-2.5 w-2.5" />Laporkan
                                        </a>
                                    @endif

                                    {{-- Verifikasi Jawaban (BR-13 & Item 8): Pemilik pertanyaan ATAU moderator/admin fakultas sama --}}
                                    @php
                                        $canVerifyReply = ! $reply->is_verified && (auth()->id() === $comment->user_id || auth()->user()->canModerateContent($materialFaculty));
                                    @endphp
                                    @if ($canVerifyReply)
                                        <form method="POST" action="{{ route('moderasi.komentar.verifikasi', $reply) }}">
                                            @csrf
                                            <button class="inline-flex items-center gap-1 rounded-full border border-lime-dark bg-surface px-2 py-0.5 text-[10px] font-bold text-lime-ink hover:bg-lime-soft">
                                                <x-icon name="check-check" class="h-2.5 w-2.5" />Tandai terverifikasi
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hapus Balasan Langsung oleh Moderator/Admin (FR-19) --}}
                                    @if ($canDeleteComment)
                                        <form method="POST" action="{{ route('komentar.destroy', $reply) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus balasan ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-full border border-line-soft bg-surface px-2 py-0.5 text-[10px] font-bold text-coral hover:border-coral">
                                                <x-icon name="trash-2" class="h-2.5 w-2.5" />Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('materi.komentar', $material) }}" class="mt-3 grid gap-2 border-t border-line pt-3">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="hidden" name="kind" value="answer">
                    <label class="sr-only" for="reply-{{ $comment->id }}">Jawaban untuk {{ $comment->author?->name }}</label>
                    <input id="reply-{{ $comment->id }}" name="body" required minlength="3" placeholder="Tulis jawaban..." class="field !py-2 !text-[11px]">
                    <input name="source_ref" placeholder="Sumber/referensi (opsional) - halaman slide, buku, dll." class="field !py-2 !text-[11px]">
                    <button class="btn-outline !py-2 !text-[11px]">Kirim jawaban</button>
                </form>
            </article>
        @endforeach
    </div>
</section>

{{-- MODAL LAPOR MATERI --}}
@if (auth()->id() !== $material->user_id)
<div id="modal-lapor-materi" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/40 p-5 target:flex">
    <div class="w-full max-w-[360px] rounded-[26px] bg-surface p-5 shadow-[var(--shadow-modal)]">
        <div class="flex items-center justify-between">
            <p class="font-display text-base font-extrabold text-ink">Laporkan materi</p>
            <a href="#" aria-label="Tutup" class="grid h-8 w-8 place-items-center rounded-full bg-gray-fill">
                <x-icon name="x" class="h-4 w-4 text-ink" />
            </a>
        </div>
        <form method="POST" action="{{ route('materi.lapor', $material) }}" class="mt-4 space-y-3" novalidate>
            @csrf
            <fieldset class="space-y-2">
                <legend class="label">Alasan laporan</legend>
                @foreach ([['hak-cipta', 'Melanggar hak cipta'], ['tidak-pantas', 'Tidak pantas'], ['informasi-salah', 'Informasi salah'], ['lainnya', 'Lainnya']] as [$val, $label])
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-[9px] border border-line-soft px-3 py-2.5 text-xs text-ink hover:border-primary">
                        <input type="radio" name="reason" value="{{ $val }}" required class="h-4 w-4 text-primary focus:ring-primary">
                        {{ $label }}
                    </label>
                @endforeach
            </fieldset>
            <div>
                <label class="label" for="detail-materi">Keterangan</label>
                <textarea id="detail-materi" name="detail" rows="3" class="field resize-none" placeholder="Jelaskan singkat masalahnya (opsional)"></textarea>
            </div>
            <div class="flex gap-2 pt-1">
                <a href="#" class="btn-outline flex-1 !text-xs">Batal</a>
                <button type="submit" class="btn-danger flex-1 !text-xs">Kirim laporan</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- MODAL LAPOR KOMENTAR (Terpisah & Dynamic Action) --}}
<div id="modal-lapor-komentar" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/40 p-5 target:flex">
    <div class="w-full max-w-[360px] rounded-[26px] bg-surface p-5 shadow-[var(--shadow-modal)]">
        <div class="flex items-center justify-between">
            <p class="font-display text-base font-extrabold text-ink">Laporkan komentar</p>
            <a href="#" aria-label="Tutup" class="grid h-8 w-8 place-items-center rounded-full bg-gray-fill">
                <x-icon name="x" class="h-4 w-4 text-ink" />
            </a>
        </div>
        <form id="form-lapor-komentar" method="POST" action="" class="mt-4 space-y-3" novalidate>
            @csrf
            <fieldset class="space-y-2">
                <legend class="label">Alasan laporan</legend>
                @foreach ([['tidak-pantas', 'Tidak pantas'], ['informasi-salah', 'Informasi salah'], ['hak-cipta', 'Melanggar hak cipta'], ['lainnya', 'Lainnya']] as [$val, $label])
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-[9px] border border-line-soft px-3 py-2.5 text-xs text-ink hover:border-primary">
                        <input type="radio" name="reason" value="{{ $val }}" required class="h-4 w-4 text-primary focus:ring-primary">
                        {{ $label }}
                    </label>
                @endforeach
            </fieldset>
            <div>
                <label class="label" for="detail-komentar">Keterangan</label>
                <textarea id="detail-komentar" name="detail" rows="3" class="field resize-none" placeholder="Jelaskan singkat masalahnya (opsional)"></textarea>
            </div>
            <div class="flex gap-2 pt-1">
                <a href="#" class="btn-outline flex-1 !text-xs">Batal</a>
                <button type="submit" class="btn-danger flex-1 !text-xs">Kirim laporan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TOLAK MATERI --}}
@if ($canModerateMaterial)
<div id="modal-tolak" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink/40 p-5 target:flex">
    <div class="w-full max-w-[360px] rounded-[26px] bg-surface p-5 shadow-[var(--shadow-modal)]">
        <div class="flex items-center justify-between">
            <p class="font-display text-base font-extrabold text-ink">Tolak materi</p>
            <a href="#" aria-label="Tutup" class="grid h-8 w-8 place-items-center rounded-full bg-gray-fill">
                <x-icon name="x" class="h-4 w-4 text-ink" />
            </a>
        </div>
        <form method="POST" action="{{ route('moderasi.materi.tolak', $material) }}" class="mt-4 space-y-3" novalidate>
            @csrf
            <div>
                <label class="label" for="rejection_reason">Alasan penolakan</label>
                <textarea id="rejection_reason" name="rejection_reason" rows="4" required minlength="10"
                          class="field resize-none @error('rejection_reason') !border-coral @enderror"
                          placeholder="Contoh: slide memakai gambar berhak cipta tanpa izin penerbit.">{{ old('rejection_reason') }}</textarea>
                @error('rejection_reason')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-2 pt-1">
                <a href="#" class="btn-outline flex-1 !text-xs">Batal</a>
                <button type="submit" class="btn-danger flex-1 !text-xs">Tolak materi</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function salinRingkasanDosen(title, course, url, question, answers) {
    let text = "RINGKASAN PERTANYAAN UNTUK KONFIRMASI DOSEN\n";
    text += "Materi: " + title + "\n";
    text += "Mata Kuliah: " + course + "\n";
    text += "Tautan: " + url + "\n\n";
    text += "Pertanyaan:\n" + question + "\n\n";
    text += "Daftar Jawaban:\n";
    if (!answers || answers.length === 0) {
        text += "- Belum ada jawaban.\n";
    } else {
        answers.forEach((ans, i) => {
            let status = ans.verified ? "[Terverifikasi]" : "[Belum diverifikasi]";
            text += (i + 1) + ". " + ans.body + " (oleh: " + ans.author + ") " + status + "\n";
        });
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            alert("Ringkasan diskusi berhasil disalin ke clipboard!");
        }).catch(() => fallbackCopy(text));
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const el = document.createElement('textarea');
    el.value = text;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    alert("Ringkasan diskusi berhasil disalin ke clipboard!");
}
</script>
@endsection
