<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Course;
use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('beranda', [
            'courses' => Course::withCount(['materials', 'quizzes'])->latest()->take(3)->get(),
            'stats' => [
                'courses' => Course::count(),
                'materials' => Material::count(),
                'quizzes' => Attempt::where('user_id', $user->id)->count(),
            ],
            'continue' => Material::with('course')->latest()->first(),
            'myQuizzes' => Quiz::with('course')->where('user_id', $user->id)->get(),
        ]);
    }

    public function landing()
    {
        return view('landing', [
            'courses' => Course::withCount('materials')->take(3)->get(),
            'materials' => Material::count(),
            'quizzes' => Quiz::count(),
        ]);
    }
}
