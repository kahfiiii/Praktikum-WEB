<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Material;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function courses()
    {
        return view('admin.kelola', [
            'courses' => Course::withCount('materials')->orderBy('name')->get(),
            'stats' => $this->stats(),
        ]);
    }

    public function stats(): array
    {
        return [
            'materials' => Material::count(),
            'verified' => Material::where('verification_status', 'verified')->count(),
            'attempts' => Attempt::count(),
            'faculties' => Course::distinct()->count('faculty'),
        ];
    }

    public function storeCourse(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'code' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:Gasal,Genap'],
            'faculty' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:400'],
        ], [
            'name.required' => 'Nama mata kuliah wajib diisi.',
            'code.required' => 'Kode mata kuliah wajib diisi.',
            'semester.required' => 'Semester wajib dipilih.',
            'faculty.required' => 'Fakultas wajib dipilih.',
        ]);

        Course::create($data + ['accent' => 'primary']);

        return redirect()->route('admin.kelola')->with('status', 'Mata kuliah "'.$data['name'].'" ditambahkan.');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $course->update($request->validate([
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'is_active' => ['nullable', 'boolean'],
        ]));

        return redirect()->route('admin.kelola')->with('status', 'Mata kuliah diperbarui.');
    }

    public function destroyCourse(Course $course)
    {
        if ($course->materials()->count() > 0) {
            return back()->withErrors(['course' => 'Mata kuliah tidak dapat dihapus karena masih memiliki materi.'])
                         ->with('error', 'Mata kuliah tidak dapat dihapus karena masih memiliki materi.');
        }

        $name = $course->name;
        $course->delete();

        return redirect()->route('admin.kelola')->with('status', 'Mata kuliah "'.$name.'" dihapus.');
    }

    public function comments()
    {
        return view('admin.moderasi', [
            'comments' => Comment::with(['author', 'material'])->latest()->get(),
        ]);
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['status' => 'approved', 'reports' => 0]);

        return back()->with('status', 'Komentar disetujui.');
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();

        return back()->with('status', 'Komentar dihapus.');
    }

    public function moderators(Request $request)
    {
        $query = User::query()->latest();

        if ($faculty = $request->query('faculty')) {
            $query->where('faculty', $faculty);
        }

        if ($q = $request->query('q')) {
            $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('nim', 'like', "%{$q}%"));
        }

        return view('admin.moderator', [
            'users' => $query->paginate(20)->withQueryString(),
            'faculties' => User::whereNotNull('faculty')->distinct()->orderBy('faculty')->pluck('faculty'),
            'faculty' => $faculty,
            'q' => $q,
            'stats' => $this->stats(),
        ]);
    }

    public function toggleModerator(User $user)
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['user' => 'Peran admin tidak bisa diubah dari halaman ini.']);
        }

        $user->update(['role' => $user->role === 'moderator' ? 'student' : 'moderator']);

        $msg = $user->role === 'moderator'
            ? $user->name.' sekarang menjadi moderator.'
            : 'Peran moderator '.$user->name.' dicabut.';

        return back()->with('status', $msg);
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'stats' => $this->stats(),
            'reports' => Report::with('reporter')->latest()->take(5)->get(),
            'recent' => Material::with(['course', 'author'])->latest()->take(5)->get(),
        ]);
    }
}
