@extends('layouts.app')

@section('title', 'Mata Kuliah')

@section('content')
{{-- ===== PAGE HEADER ===== --}}
<div class="flex flex-col gap-1">
    <p class="font-display text-[13px] font-bold text-[#5568ee]">Perpustakaan mini</p>
    <h1 class="font-display text-[32px] font-extrabold tracking-[-1px] text-[#17254b] lg:text-[38px]">Mata kuliah</h1>
    <p class="text-[15px] text-[#68718a]">Pilih ruang belajar yang mau kamu jelajahi.</p>
</div>

{{-- ===== SEARCH & COUNT ===== --}}
<div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex flex-1 flex-wrap items-center gap-3">
        <form method="GET" class="relative w-full max-w-[340px]">
            @if (request('fakultas'))
                <input type="hidden" name="fakultas" value="{{ request('fakultas') }}">
            @endif
            <x-icon name="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#68718a]" />
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Cari mata kuliah..."
                   class="w-full rounded-[14px] border border-[#d8dbe5] bg-white py-2.5 pl-12 pr-4 text-[14px] text-[#17254b] placeholder-[#68718a] focus:border-[#5568ee] focus:outline-none">
        </form>

        <form method="GET" class="inline-block">
            @if (request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            <select name="fakultas" onchange="this.form.submit()" class="rounded-[14px] border border-[#d8dbe5] bg-white px-3.5 py-2.5 text-[13px] font-medium text-[#17254b] focus:border-[#5568ee] focus:outline-none">
                <option value="">Semua Fakultas</option>
                @foreach ($faculties as $f)
                    <option value="{{ $f }}" {{ (request('fakultas') === $f || (isset($selectedFaculty) && $selectedFaculty === $f)) ? 'selected' : '' }}>{{ $f }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <p class="text-[13px] font-medium text-[#68718a]">
        {{ $courses->count() }} mata kuliah ditemukan
    </p>
</div>

@php
    $filtered = $courses;

    // Glyph / Simbol unik per mata kuliah sesuai Figma
    $glyphs = ['⌁', '◇', '▦', '✦', '⌘', '◎'];
    $colors = [
        ['bg' => 'bg-[#e9ebff]', 'text' => 'text-[#5568ee]'],
        ['bg' => 'bg-[#ffe6e2]', 'text' => 'text-[#ff7868]'],
        ['bg' => 'bg-[#eafaaf]', 'text' => 'text-[#466419]'],
        ['bg' => 'bg-[#e9ddff]', 'text' => 'text-[#6741a8]'],
        ['bg' => 'bg-[#fff5bd]', 'text' => 'text-[#a87a19]'],
        ['bg' => 'bg-[#e0f7f6]', 'text' => 'text-[#0e7490]'],
    ];
@endphp

{{-- ===== COURSE CARDS GRID (373px x 250px Cards) ===== --}}
<div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($filtered as $idx => $course)
        @php
            $glyph = $glyphs[$idx % count($glyphs)];
            $cTheme = $colors[$idx % count($colors)];
        @endphp
        <a href="{{ route('mata-kuliah.detail', $course) }}"
           class="group flex flex-col justify-between rounded-[22px] border border-[#e5e2dc] bg-white p-7 transition-all duration-200 hover:-translate-y-1 hover:border-[#5568ee] hover:shadow-[0_12px_30px_rgba(23,37,75,0.08)]">
            <div>
                {{-- Glyph Icon Circle --}}
                <div class="grid h-[54px] w-[54px] place-items-center rounded-[18px] {{ $cTheme['bg'] }} {{ $cTheme['text'] }} text-[26px] font-bold">
                    {{ $glyph }}
                </div>

                {{-- Code & Name --}}
                <p class="mt-6 text-[12px] font-bold tracking-wider text-[#68718a]">{{ $course->code }}</p>
                <h2 class="mt-1 font-display text-[20px] font-bold leading-[26px] text-[#17254b] group-hover:text-[#5568ee]">
                    {{ $course->name }}
                </h2>
            </div>

            {{-- Footer info --}}
            <div class="mt-6 flex items-center justify-between border-t border-[#f0eee9] pt-4">
                <span class="text-[13px] font-medium text-[#68718a]">{{ $course->materials_count }} materi</span>
                <span class="grid h-9 w-9 place-items-center rounded-full bg-[#f6f5fb] text-[#17254b] transition group-hover:bg-[#5568ee] group-hover:text-white">
                    <x-icon name="arrow-right" class="h-4 w-4" />
                </span>
            </div>
        </a>
    @empty
        <div class="col-span-full rounded-[22px] border border-[#e5e2dc] bg-white p-12 text-center">
            <p class="font-display text-[16px] font-bold text-[#17254b]">Belum ada mata kuliah yang cocok</p>
            <p class="mt-1 text-[13px] text-[#68718a]">Coba cari dengan kata kunci lain.</p>
        </div>
    @endforelse
</div>
@endsection
