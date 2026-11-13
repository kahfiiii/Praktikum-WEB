@extends('layouts.app')

@section('title', 'Unggah Materi')

@section('content')
<div class="mx-auto max-w-[820px]">
    {{-- Back Link --}}
    <div class="mb-4">
        <a href="{{ route('beranda') }}" class="inline-flex items-center gap-1.5 font-display text-[13px] font-bold text-[#68718a] hover:text-[#5568ee]">
            ← Batal dan kembali
        </a>
    </div>

    {{-- Page Head --}}
    <div class="mb-8">
        <p class="font-display text-[12px] font-bold text-[#5568ee]">Berbagi itu keren</p>
        <h1 class="mt-1 font-display text-[32px] font-extrabold tracking-[-1px] text-[#17254b] lg:text-[38px]">Unggah materi</h1>
        <p class="mt-1 text-[15px] text-[#68718a]">Bantu teman-teman se-fakultas belajar lebih mudah dengan catatan atau slide kuliahmu.</p>
    </div>

    {{-- Form Card --}}
    <div class="rounded-[22px] border border-[#e5e2dc] bg-white p-6 sm:p-10 shadow-[0_8px_30px_rgba(23,37,75,0.04)]">
        <form method="POST" action="{{ route('unggah.store') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
            @csrf

            {{-- Judul Materi --}}
            <div>
                <label class="mb-2 block font-display text-[14px] font-bold text-[#17254b]" for="title">Judul materi</label>
                <input id="title" name="title" type="text" required value="{{ old('title') }}"
                       placeholder="Ringkasan Linked List & Implementasinya"
                       class="w-full rounded-[12px] border border-[#d8dbe5] bg-white px-4 py-3 text-[14px] text-[#17254b] placeholder-[#68718a] focus:border-[#5568ee] focus:outline-none @error('title') !border-[#ff7868] @enderror">
                @error('title')<p class="mt-1.5 text-[12px] font-bold text-[#a84439]">{{ $message }}</p>@enderror
            </div>

            {{-- Deskripsi Singkat --}}
            <div>
                <label class="mb-2 block font-display text-[14px] font-bold text-[#17254b]" for="body">Deskripsi singkat</label>
                <textarea id="body" name="body" rows="3"
                          placeholder="Catatan visual lengkap dari konsep dasar sampai contoh implementasi."
                          class="w-full rounded-[12px] border border-[#d8dbe5] bg-white px-4 py-3 text-[14px] text-[#17254b] placeholder-[#68718a] focus:border-[#5568ee] focus:outline-none resize-none">{{ old('body') }}</textarea>
                <p class="mt-1.5 text-[12px] text-[#68718a]">Ceritakan isi dan kegunaan materi ini.</p>
            </div>

            {{-- Mata Kuliah --}}
            <div>
                <label class="mb-2 block font-display text-[14px] font-bold text-[#17254b]" for="course_id">Mata kuliah</label>
                <div class="relative">
                    <select id="course_id" name="course_id" required
                            class="w-full appearance-none rounded-[12px] border border-[#d8dbe5] bg-white px-4 py-3 text-[14px] text-[#17254b] focus:border-[#5568ee] focus:outline-none @error('course_id') !border-[#ff7868] @enderror">
                        <option value="">Pilih mata kuliah</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }} ({{ $course->code }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[#68718a]">
                        ▼
                    </div>
                </div>
                @error('course_id')<p class="mt-1.5 text-[12px] font-bold text-[#a84439]">{{ $message }}</p>@enderror
            </div>

            {{-- Berkas Materi (Drag & Drop Zone Sesuai Figma) --}}
            <div>
                <label class="mb-2 block font-display text-[14px] font-bold text-[#17254b]">Berkas materi</label>
                <label for="file" class="group flex cursor-pointer flex-col items-center justify-center rounded-[16px] border-2 border-dashed border-[#d8dbe5] bg-[#fbf8f1] px-6 py-10 text-center transition hover:border-[#5568ee] hover:bg-[#e9ebff]/30">
                    <div class="grid h-12 w-12 place-items-center rounded-full bg-[#e9ddff] text-[#6741a8] transition group-hover:scale-110">
                        <x-icon name="cloud-upload" class="h-6 w-6" />
                    </div>
                    <p class="mt-4 font-display text-[14px] font-bold text-[#17254b]">Tarik berkas ke sini, atau pilih dari perangkat</p>
                    <p class="mt-1 text-[12px] text-[#68718a]">PDF, DOCX, PPTX · maks. 5 MB</p>
                    <p id="file-name" class="mt-3 text-[13px] font-bold text-[#5568ee]"></p>
                    <input id="file" name="file" type="file" accept=".pdf,.docx,.pptx" required class="sr-only">
                </label>
                @error('file')<p class="mt-1.5 text-[12px] font-bold text-[#a84439]">{{ $message }}</p>@enderror
            </div>

            {{-- Checkbox Hak Cipta --}}
            <div class="rounded-[14px] border border-[#e5e2dc] bg-[#fffdf8] p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" required checked class="mt-1 h-4 w-4 rounded border-[#d8dbe5] text-[#5568ee] focus:ring-[#5568ee]">
                    <div class="text-[13px]">
                        <p class="font-bold text-[#17254b]">Saya memastikan materi ini milik saya atau boleh dibagikan</p>
                        <p class="text-[#68718a]">Jangan unggah materi berhak cipta tanpa izin pemiliknya.</p>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#f0eee9]">
                <a href="{{ route('beranda') }}" class="rounded-[13px] px-6 py-3 font-display text-[14px] font-bold text-[#68718a] hover:text-[#17254b]">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-[13px] bg-[#5568ee] px-7 py-3 font-display text-[14px] font-bold text-white transition hover:bg-[#4252c9]" style="box-shadow: 0 4px 0 #3747bb">
                    Unggah sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('file')?.addEventListener('change', (e) => {
        const name = e.target.files?.[0]?.name;
        const out = document.getElementById('file-name');
        if (out) out.textContent = name ? '✓ Berkas dipilih: ' + name : '';
    });
</script>
@endsection
