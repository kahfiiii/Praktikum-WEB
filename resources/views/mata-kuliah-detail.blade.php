@extends('layouts.app')

@section('title', $course->name)

@section('content')
{{-- Back Link --}}
<div class="mb-4">
    <a href="{{ route('mata-kuliah') }}" class="inline-flex items-center gap-1.5 font-display text-[13px] font-bold text-[#68718a] hover:text-[#5568ee]">
        ← Semua mata kuliah
    </a>
</div>

{{-- Page Header --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="font-display text-[12px] font-bold text-[#5568ee]">{{ $course->code }}</p>
        <h1 class="mt-1 font-display text-[32px] font-extrabold tracking-[-1px] text-[#17254b] lg:text-[38px]">{{ $course->name }}</h1>
        <p class="mt-1 max-w-[600px] text-[15px] text-[#68718a]">
            {{ $course->description ?? 'Kumpulan catatan, slide, dan latihan pilihan mahasiswa Universitas Brawijaya.' }}
        </p>
    </div>
    <a href="{{ route('unggah') }}" class="inline-flex shrink-0 items-center gap-2 rounded-[13px] bg-[#5568ee] px-5 py-3 font-display text-[14px] font-bold text-white transition hover:bg-[#4252c9]" style="box-shadow: 0 4px 0 #3747bb">
        <x-icon name="upload" class="h-4 w-4" />
        Unggah materi
    </a>
</div>

{{-- Search & Filter Container --}}
<div class="mt-8 flex flex-col gap-4 rounded-[18px] border border-[#e5e2dc] bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" class="relative w-full max-w-[440px]">
        <x-icon name="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#68718a]" />
        <input name="q" value="{{ request('q') }}" placeholder="Cari judul atau topik..."
               class="w-full rounded-[12px] border border-[#d8dbe5] bg-white py-2.5 pl-12 pr-4 text-[14px] text-[#17254b] placeholder-[#68718a] focus:border-[#5568ee] focus:outline-none">
    </form>
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
        @php
            $types = ['Semua' => '', 'PDF' => 'pdf', 'DOCX' => 'docx', 'PPTX' => 'pptx'];
            $currentType = request('type', '');
        @endphp
        @foreach ($types as $label => $val)
            <a href="{{ request()->fullUrlWithQuery(['type' => $val, 'page' => null]) }}"
               class="rounded-full px-4 py-1.5 text-[12px] font-bold transition {{ ($currentType == $val) ? 'bg-[#5568ee] text-white' : 'border border-[#d8dbe5] bg-white text-[#68718a] hover:border-[#5568ee] hover:text-[#5568ee]' }}">
                {{ $label }}
            </a>
        @endforeach
        <a href="{{ request()->fullUrlWithQuery(['terverifikasi' => request('terverifikasi') ? null : '1', 'page' => null]) }}"
           class="rounded-full px-3.5 py-1.5 text-[12px] font-bold transition {{ request('terverifikasi') ? 'bg-[#466419] text-white' : 'border border-[#d8dbe5] bg-white text-[#466419] hover:border-[#466419]' }}">
            <x-icon name="shield-check" class="inline h-3.5 w-3.5 mr-0.5" />Hanya terverifikasi
        </a>
    </div>
</div>

{{-- Materials Count & Sort --}}
<div class="mt-8 flex items-center justify-between">
    <h2 class="font-display text-[18px] font-bold text-[#17254b]">
        {{ $materials->total() }} materi tersedia
    </h2>
    <span class="text-[13px] font-medium text-[#68718a]">Terbaru</span>
</div>

{{-- Materials List (Figma Row Style) --}}
<div class="mt-4 space-y-3.5">
    @forelse ($materials as $material)
        <a href="{{ route('materi.detail', $material) }}"
           class="group flex flex-col gap-4 rounded-[20px] border border-[#e5e2dc] bg-white p-5 transition hover:border-[#5568ee] hover:shadow-[0_8px_20px_rgba(23,37,75,0.06)] sm:flex-row sm:items-start sm:gap-6">
            {{-- File icon block --}}
            <div class="grid h-16 w-16 shrink-0 place-items-center rounded-[16px] {{ strtolower($material->file_type) === 'pdf' ? 'bg-[#ffe6e2] text-[#ff7868]' : (strtolower($material->file_type) === 'pptx' ? 'bg-[#fff5bd] text-[#a87a19]' : 'bg-[#e9ddff] text-[#6741a8]') }}">
                <div class="flex flex-col items-center">
                    <x-icon name="file-text" class="h-5 w-5" />
                    <span class="mt-0.5 text-[10px] font-bold uppercase">{{ $material->file_type }}</span>
                </div>
            </div>

            {{-- Body info --}}
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-[#f6f5fb] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#68718a]">
                        {{ $material->file_type }}
                    </span>
                    <span class="text-[12px] text-[#68718a]">{{ $material->created_at ? $material->created_at->diffForHumans() : 'Baru saja' }}</span>
                    @if ($material->isVerified())
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#eafaaf] px-2 py-0.5 text-[10px] font-bold text-[#466419]">
                            <x-icon name="shield-check" class="h-3 w-3" /> Terverifikasi
                        </span>
                    @elseif ($material->verification_status === 'rejected')
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#ffe6e2] px-2 py-0.5 text-[10px] font-bold text-[#ff7868]">
                            <x-icon name="x-circle" class="h-3 w-3" /> Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-[#f6f5fb] px-2 py-0.5 text-[10px] font-bold text-[#68718a]">
                            <x-icon name="clock" class="h-3 w-3" /> Belum diverifikasi
                        </span>
                    @endif
                </div>

                <h3 class="mt-1.5 font-display text-[18px] font-bold text-[#17254b] group-hover:text-[#5568ee]">
                    {{ $material->title }}
                </h3>

                @if ($material->body)
                    <p class="mt-1 line-clamp-2 text-[13px] leading-relaxed text-[#68718a]">
                        {{ Str::limit(strip_tags($material->body), 150) }}
                    </p>
                @endif

                <div class="mt-3 flex flex-wrap items-center gap-4 text-[12px] text-[#68718a]">
                    <div class="flex items-center gap-1.5">
                        <span class="grid h-5 w-5 place-items-center rounded-full bg-[#cde969] text-[9px] font-bold text-[#17254b]">
                            {{ $material->author ? $material->author->initials() : 'UB' }}
                        </span>
                        <span class="font-medium text-[#17254b]">{{ $material->author ? $material->author->name : 'Anonim' }}</span>
                    </div>
                    <span>{{ $material->sizeLabel() }}</span>
                </div>
            </div>

            {{-- Action chevron --}}
            <div class="hidden self-center sm:block">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-[#f6f5fb] text-[#17254b] group-hover:bg-[#5568ee] group-hover:text-white">
                    <x-icon name="arrow-right" class="h-4 w-4" />
                </span>
            </div>
        </a>
    @empty
        <div class="rounded-[20px] border border-[#e5e2dc] bg-white p-12 text-center">
            <p class="font-display text-[16px] font-bold text-[#17254b]">Belum ada materi untuk mata kuliah ini</p>
            <p class="mt-1 text-[13px] text-[#68718a]">Jadilah yang pertama mengunggah catatan atau slide kuliah.</p>
            <a href="{{ route('unggah') }}" class="mt-4 inline-flex items-center gap-2 rounded-[13px] bg-[#5568ee] px-5 py-2.5 font-display text-[14px] font-bold text-white">
                Unggah sekarang
            </a>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $materials->links() }}
</div>
@endsection
