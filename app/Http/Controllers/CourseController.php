<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::withCount(['materials', 'quizzes'])->latest();

        $selectedFaculty = $request->query('fakultas') ?: $request->query('faculty');
        if ($selectedFaculty) {
            $query->where('faculty', $selectedFaculty);
        }

        if ($q = $request->query('q')) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('code', 'like', "%{$q}%");
            });
        }

        return view('mata-kuliah', [
            'courses' => $query->get(),
            'faculties' => Course::whereNotNull('faculty')->distinct()->orderBy('faculty')->pluck('faculty'),
            'selectedFaculty' => $selectedFaculty,
        ]);
    }

    public function show(Request $request, Course $course)
    {
        $materialsQuery = $course->materials()->with('author')->latest();

        if ($q = $request->query('q')) {
            $materialsQuery->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('body', 'like', "%{$q}%");
            });
        }

        if ($type = $request->query('type')) {
            $materialsQuery->where('file_type', strtoupper($type));
        }

        $onlyVerified = $request->boolean('terverifikasi') || $request->boolean('verified') || $request->query('status') === 'verified';
        if ($onlyVerified) {
            $materialsQuery->where('verification_status', 'verified');
        }

        return view('mata-kuliah-detail', [
            'course' => $course,
            'materials' => $materialsQuery->paginate(10)->withQueryString(),
            'quizzes' => $course->quizzes()->withCount('questions')->get(),
            'onlyVerified' => $onlyVerified,
        ]);
    }

    public function materials(Course $course)
    {
        return redirect()->route('mata-kuliah.detail', $course);
    }
}
