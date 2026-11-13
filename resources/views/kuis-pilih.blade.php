@extends('layouts.app')

@section('title', 'Pilih Metode Kuis')

@section('content')
<a href="{{ route('kuis-saya') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-ink-muted">
    <x-icon name="arrow-left" class="h-3.5 w-3.5" />Kembali ke daftar kuis
</a>

<h1 class="mt-3 font-display text-[26px] font-extrabold leading-tight tracking-[-1px] text-ink">Mau bikin kuis bagaimana?</h1>
<p class="mt-1 text-xs text-ink-muted">Pilih satu jalur, bisa diubah nanti.</p>

<div class="mt-5 grid gap-3 md:grid-cols-2">
    <a href="{{ route('kuis.bank-soal') }}" class="card block p-4 transition hover:border-primary">
        <div class="flex items-start gap-3">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-[12px] bg-primary-soft text-primary">
                <x-icon name="library" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="font-display text-sm font-extrabold text-ink">Pakai bank soal</p>
                <p class="mt-1 text-xs leading-relaxed text-ink-muted">Pilih soal yang sudah ada, atur jumlah dan nilainya sendiri.</p>
                <span class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-primary">Mulai dari kosong <x-icon name="arrow-right" class="h-3 w-3" /></span>
            </div>
        </div>
    </a>

    <a href="{{ route('kuis.ai') }}" class="card block p-4 transition hover:border-violet">
        <div class="flex items-start gap-3">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-[12px] bg-violet-soft text-violet">
                <x-icon name="sparkles" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="font-display text-sm font-extrabold text-ink">Buat dengan AI</p>
                <p class="mt-1 text-xs leading-relaxed text-ink-muted">AI menyusun soal dari topik atau materi yang kamu pilih.</p>
                <span class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-violet">Buat dari materi <x-icon name="arrow-right" class="h-3 w-3" /></span>
            </div>
        </div>
    </a>
</div>

<div class="card mt-5 border-lime-dark bg-lime-soft p-4">
    <p class="font-display text-xs font-extrabold text-lime-ink">Tips</p>
    <p class="mt-1 text-[11px] leading-relaxed text-lime-ink">Kuis dari bank soal cocok untuk latihan cepat, sedangkan mode AI membantu saat materi baru dan soal belum tersedia.</p>
</div>
@endsection
