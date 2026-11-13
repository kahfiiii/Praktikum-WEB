<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index()
    {
        return view('kuis-saya', [
            'quizzes' => Quiz::with(['course'])->withCount('questions')
                ->where('user_id', Auth::id())->latest()->get(),
            'attempts' => Attempt::with('quiz')->where('user_id', Auth::id())->latest()->get(),
        ]);
    }

    public function choose()
    {
        return view('kuis-pilih', ['courses' => Course::orderBy('name')->get()]);
    }

    public function createManual(Request $request)
    {
        return view('kuis-bank-soal', [
            'courses' => Course::withCount('materials')->orderBy('name')->get(),
            'questions' => Question::inRandomOrder()->take(8)->get(),
            'selectedCourse' => $request->integer('course_id') ?: null,
        ]);
    }

    public function storeManual(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:4', 'max:100'],
            'course_id' => ['required', 'exists:courses,id'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:120'],
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['exists:questions,id'],
        ], [
            'title.required' => 'Judul kuis wajib diisi.',
            'course_id.required' => 'Pilih mata kuliah.',
            'question_ids.required' => 'Pilih minimal satu soal.',
            'question_ids.min' => 'Pilih minimal satu soal dari bank soal.',
        ]);

        $quiz = Quiz::create([
            'title' => $data['title'],
            'course_id' => $data['course_id'],
            'user_id' => Auth::id(),
            'source' => 'manual',
            'status' => 'published',
            'duration_minutes' => $data['duration_minutes'],
        ]);

        Question::whereIn('id', $data['question_ids'])->update(['quiz_id' => $quiz->id]);

        return redirect()->route('kuis-saya')->with('status', 'Kuis "'.$quiz->title.'" berhasil dibuat dari bank soal.');
    }

    public function createAi()
    {
        return view('kuis-ai-form', ['courses' => Course::orderBy('name')->get()]);
    }

    public function processing(Request $request)
    {
        if (! $request->filled('course_id')) {
            return redirect()->route('kuis.ai');
        }

        return view('kuis-ai-proses', [
            'payload' => $request->only(['course_id', 'topic', 'count', 'type']),
            'course' => Course::find($request->integer('course_id')),
        ]);
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'topic' => ['required', 'string', 'min:3', 'max:120'],
            'count' => ['required', 'integer', 'min:3', 'max:15'],
            'type' => ['required', 'in:pilihan-ganda,benar-salah,esai'],
        ]);

        $quiz = Quiz::create([
            'title' => 'Kuis AI · '.Str::title($data['topic']),
            'course_id' => $data['course_id'],
            'user_id' => Auth::id(),
            'source' => 'ai',
            'status' => 'draft',
            'duration_minutes' => 20,
        ]);

        // Soal contoh dihasilkan dari topik yang dikirim, tanpa layanan AI berbayar.
        $templates = [
            ['Apa inti dari konsep %s?', ['Definisi dasar pada materi', 'Istilah yang tidak berkaitan', 'Rumus yang sudah lama dihapus', 'Fungsi bantu yang jarang dipakai'], 0],
            ['Kapan %s paling tepat dipakai?', ['Saat data perlu akses acak cepat', 'Saat data bersifat sekuensial', 'Saat memori sangat terbatas', 'Saat hasil harus unik'], 1],
            ['Apa risiko utama dari %s?', ['Konsumsi memori berlebih', 'Hasil tidak konsisten', 'Kehilangan struktur data', 'Waktu akses bertambah linear'], 3],
            ['Contoh penerapan %s di aplikasi nyata adalah?', ['Antrean tiket event', 'Menghitung nilai rata-rata', 'Menyimpan riwayat chat', 'Mengurutkan nama mahasiswa'], 0],
        ];

        for ($i = 0; $i < $data['count']; $i++) {
            $t = $templates[$i % count($templates)];
            Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => sprintf($t[0], $data['topic']),
                'options' => $t[1],
                'answer_index' => $t[2],
            ]);
        }

        return redirect()->route('kuis.preview', $quiz);
    }

    public function preview(Quiz $quiz)
    {
        Gate::authorize('view', $quiz);

        return view('kuis-preview', [
            'quiz' => $quiz->load(['course', 'questions']),
        ]);
    }

    public function publish(Quiz $quiz)
    {
        Gate::authorize('publish', $quiz);

        $quiz->update(['status' => 'published']);

        return redirect()->route('kuis-saya')->with('status', 'Kuis "'.$quiz->title.'" tersimpan dan siap dikerjakan.');
    }

    public function play(Quiz $quiz)
    {
        Gate::authorize('view', $quiz);

        return view('kuis-play', ['quiz' => $quiz->load('questions')]);
    }

    public function destroy(Quiz $quiz)
    {
        Gate::authorize('delete', $quiz);

        $title = $quiz->title;
        $quiz->delete();

        return redirect()->route('kuis-saya')->with('status', 'Kuis "'.$title.'" berhasil dihapus.');
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $answers = $request->input('answers', []);
        $correct = 0;

        foreach ($quiz->questions as $question) {
            if ((int) ($answers[$question->id] ?? -1) === $question->answer_index) {
                $correct++;
            }
        }

        $score = $quiz->questions->count() > 0
            ? (int) round($correct / $quiz->questions->count() * 100)
            : 0;

        Attempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'score' => $score,
            'completed_at' => now(),
        ]);

        return redirect()->route('kuis-saya')->with('status', "Kuis selesai. Skor kamu {$score}. Benar {$correct} dari {$quiz->questions->count()} soal.");
    }
}
