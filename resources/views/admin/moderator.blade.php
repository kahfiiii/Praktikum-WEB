@extends('layouts.base')

@section('title', 'Kelola Moderator')

@section('body')
<div class="min-h-screen bg-canvas-warm pb-10">
    @include('moderasi._nav', ['active' => 'moderator'])

    <main class="mx-auto max-w-5xl px-5 pt-5 lg:px-8 lg:pt-8">
        @if (session('status'))
            <div class="mb-4 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-xs font-medium text-lime-ink" role="status">{{ session('status') }}</div>
        @endif
        @error('user')
            <div class="mb-4 rounded-[13px] border border-coral bg-coral-soft px-4 py-3 text-xs font-medium text-coral-ink" role="alert">{{ $message }}</div>
        @enderror

        <h1 class="font-display text-[22px] font-extrabold tracking-[-0.8px] text-ink">Kelola moderator</h1>
        <p class="mt-1 text-xs text-ink-muted">{{ $users->where('role', 'moderator')->count() }} moderator aktif dari {{ $users->count() }} pengguna</p>

        <form method="GET" class="mt-4 space-y-2">
            <div class="relative">
                <x-icon name="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
                <label class="sr-only" for="q">Cari pengguna</label>
                <input id="q" name="q" value="{{ $q }}" placeholder="Cari nama, NIM, atau email" class="field !pl-10">
            </div>
            <label class="sr-only" for="faculty">Filter fakultas</label>
            <select id="faculty" name="faculty" onchange="this.form.submit()" class="field !py-2.5 !text-xs">
                <option value="">Semua fakultas</option>
                @foreach ($faculties as $fac)
                    <option value="{{ $fac }}" @selected($faculty === $fac)>{{ $fac }}</option>
                @endforeach
            </select>
        </form>

        @if ($users->isEmpty())
            <div class="card mt-4 p-6 text-center">
                <p class="font-display text-sm font-bold text-ink">Pengguna tidak ditemukan</p>
                <p class="mt-1 text-xs text-ink-muted">Coba kata kunci atau fakultas lain.</p>
            </div>
        @else
            <div class="mt-4 grid gap-2.5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($users as $user)
                    <div class="card p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-primary-soft font-display text-[11px] font-extrabold text-primary">{{ $user->initials() }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-display text-xs font-bold text-ink">{{ $user->name }}</p>
                                <p class="truncate text-[10px] text-ink-muted">{{ $user->nim }} · {{ $user->faculty ?? 'Fakultas belum diisi' }}</p>
                            </div>
                            <span class="badge shrink-0 {{ $user->roleClasses() }} text-[9px]">{{ $user->roleLabel() }}</span>
                        </div>
                        @if ($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.moderator.toggle', $user) }}" class="mt-2.5">
                                @csrf
                                <button class="w-full !py-2 !text-[11px] {{ $user->role === 'moderator' ? 'btn-outline' : 'btn-primary' }}">
                                    {{ $user->role === 'moderator' ? 'Cabut peran moderator' : 'Jadikan moderator' }}
                                </button>
                            </form>
                        @else
                            <p class="mt-2.5 rounded-[9px] bg-gray-fill px-3 py-2 text-[10px] text-ink-muted">Akun admin, peran tidak diubah dari sini.</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </main>
</div>
@endsection
