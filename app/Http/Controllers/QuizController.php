<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
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
            'question_ids' => ['nullable', 'array'],
            'question_ids.*' => ['exists:questions,id'],
            'file_soal' => ['nullable', 'file', 'mimes:csv,txt,json', 'max:2048'],
        ], [
            'title.required' => 'Judul kuis wajib diisi.',
            'course_id.required' => 'Pilih mata kuliah.',
            'file_soal.mimes' => 'Format file soal harus berupa CSV atau JSON.',
            'file_soal.max' => 'Ukuran berkas soal maksimal 2 MB.',
        ]);

        $uploadedQuestions = [];
        if ($request->hasFile('file_soal')) {
            $uploadedQuestions = $this->parseQuestionsFile($request->file('file_soal'));
        }

        $hasExisting = !empty($data['question_ids']);
        $hasUploaded = !empty($uploadedQuestions);

        if (!$hasExisting && !$hasUploaded) {
            return back()->withInput()->withErrors([
                'question_ids' => 'Pilih minimal satu soal dari daftar atau unggah berkas bank soal (CSV/JSON).',
            ]);
        }

        $quiz = Quiz::create([
            'title' => $data['title'],
            'course_id' => $data['course_id'],
            'user_id' => Auth::id(),
            'source' => 'manual',
            'status' => 'published',
            'duration_minutes' => $data['duration_minutes'],
        ]);

        // 1. Soal yang dipilih dari bank soal (duplikasi agar tetap tersedia di bank)
        if ($hasExisting) {
            $chosen = Question::whereIn('id', $data['question_ids'])->get();
            foreach ($chosen as $q) {
                Question::create([
                    'quiz_id' => $quiz->id,
                    'prompt' => $q->prompt,
                    'options' => $q->options,
                    'answer_index' => $q->answer_index,
                ]);
            }
        }

        // 2. Soal dari berkas yang diunggah
        foreach ($uploadedQuestions as $uq) {
            Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => $uq['prompt'],
                'options' => $uq['options'],
                'answer_index' => $uq['answer_index'],
            ]);
        }

        $totalQuestions = ($hasExisting ? count($data['question_ids']) : 0) + count($uploadedQuestions);

        return redirect()->route('kuis-saya')
            ->with('status', 'Kuis "'.$quiz->title.'" berhasil dibuat dengan '.$totalQuestions.' soal.');
    }

    public function uploadBank(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,json', 'max:2048'],
            'course_id' => ['nullable', 'exists:courses,id'],
        ], [
            'file.required' => 'Silakan pilih berkas bank soal yang akan diunggah.',
            'file.mimes' => 'Format berkas harus berupa CSV atau JSON.',
            'file.max' => 'Ukuran berkas maksimal 2 MB.',
        ]);

        $questions = $this->parseQuestionsFile($request->file('file'));

        if (empty($questions)) {
            return back()->withErrors(['file' => 'Tidak ada soal yang valid ditemukan di dalam berkas. Pastikan format sesuai template.']);
        }

        // Cari atau buat wadah kuis bank soal untuk course ini
        $courseId = $request->input('course_id') ?: Course::first()?->id;
        $poolQuiz = Quiz::firstOrCreate(
            ['title' => 'Bank Soal Utama', 'source' => 'manual'],
            ['course_id' => $courseId, 'user_id' => Auth::id(), 'status' => 'published', 'duration_minutes' => 60]
        );

        foreach ($questions as $q) {
            Question::create([
                'quiz_id' => $poolQuiz->id,
                'prompt' => $q['prompt'],
                'options' => $q['options'],
                'answer_index' => $q['answer_index'],
            ]);
        }

        return back()->with('status', count($questions).' soal berhasil diimpor ke dalam bank soal.');
    }

    public function downloadTemplate()
    {
        $csv = "Pertanyaan,Opsi A,Opsi B,Opsi C,Opsi D,Kunci\n";
        $csv .= "\"Apa kepanjangan dari PHP?\",\"Hypertext Preprocessor\",\"Preprocessed Hypertext\",\"Personal Home Page\",\"Program Hypertext\",\"A\"\n";
        $csv .= "\"Protokol default web aman yang memakai enkripsi TLS adalah?\",\"FTP\",\"HTTP\",\"HTTPS\",\"SSH\",\"C\"\n";
        $csv .= "\"Struktur data yang menerapkan konsep FIFO (First In First Out) adalah?\",\"Stack\",\"Queue\",\"Tree\",\"Graph\",\"B\"\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-bank-soal.csv"',
        ]);
    }

    private function parseQuestionsFile($file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $content = file_get_contents($file->getRealPath());
        $questions = [];

        if ($ext === 'json') {
            $data = json_decode($content, true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (!empty($item['prompt']) && !empty($item['options']) && is_array($item['options'])) {
                        $questions[] = [
                            'prompt' => trim($item['prompt']),
                            'options' => array_values(array_map('trim', $item['options'])),
                            'answer_index' => (int) ($item['answer_index'] ?? 0),
                        ];
                    }
                }
            }
        } else {
            $lines = str_getcsv($content, "\n");
            foreach ($lines as $lineIndex => $line) {
                $line = trim($line);
                if (empty($line)) continue;
                $row = str_getcsv($line);
                if (empty($row) || count($row) < 3) continue;

                // Abaikan baris header
                if ($lineIndex === 0 && (stripos($row[0], 'soal') !== false || stripos($row[0], 'pertanyaan') !== false || stripos($row[0], 'prompt') !== false)) {
                    continue;
                }

                $prompt = trim($row[0]);
                $lastCol = trim(end($row));
                $options = array_slice($row, 1, count($row) - 2);

                $answerIndex = 0;
                if (is_numeric($lastCol)) {
                    $answerIndex = (int) $lastCol;
                } else {
                    $map = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4];
                    $answerIndex = $map[strtoupper($lastCol)] ?? 0;
                }

                if (!empty($prompt) && count($options) >= 2) {
                    $cleanOptions = array_values(array_map('trim', $options));
                    $questions[] = [
                        'prompt' => $prompt,
                        'options' => $cleanOptions,
                        'answer_index' => min($answerIndex, count($cleanOptions) - 1),
                    ];
                }
            }
        }

        return $questions;
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

        $course = Course::find($data['course_id']);

        $quiz = Quiz::create([
            'title' => 'Kuis AI · '.Str::title($data['topic']),
            'course_id' => $data['course_id'],
            'user_id' => Auth::id(),
            'source' => 'ai',
            'status' => 'draft',
            'duration_minutes' => 20,
        ]);

        $questionsData = [];
        $baseUrl = rtrim(env('OMNIROUTE_BASE_URL', 'http://127.0.0.1:20128/v1'), '/');
        $apiKey = env('OMNIROUTE_API_KEY');
        $model = env('OMNIROUTE_QUIZ_MODEL', 'agy/gemini-3.8-flash-medium');

        // Panggil OmniRoute AI Gateway jika dikonfigurasi
        if (!empty($apiKey)) {
            $prompt = "Buatlah {$data['count']} butir soal bertipe '{$data['type']}' untuk materi perkuliahan '{$course?->name}' dengan topik spesifik: '{$data['topic']}'. Gunakan Bahasa Indonesia akademis standar Universitas Brawijaya.\n";
            $prompt .= "KEMBALIKAN HANYA JSON MURNI (Array of Objects), tanpa blok kode markdown (jangan pakai ```json), tanpa teks pembuka atau penutup.\n";
            $prompt .= "Format JSON setiap soal:\n";
            $prompt .= "[\n";
            $prompt .= "  {\n";
            $prompt .= "    \"prompt\": \"Pertanyaan soal\",\n";
            $prompt .= "    \"options\": [\"Opsi A\", \"Opsi B\", \"Opsi C\", \"Opsi D\"],\n";
            $prompt .= "    \"answer_index\": 0\n";
            $prompt .= "  }\n";
            $prompt .= "]\n";
            $prompt .= "Catatan: answer_index adalah integer 0, 1, 2, atau 3 yang menunjukkan jawaban yang benar.";

            try {
                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post("$baseUrl/chat/completions", [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'Kamu adalah asisten dosen ahli di Universitas Brawijaya yang bertugas menyusun soal kuis akademik bermutu tinggi. Selalu balas dalam format JSON murni yang diminta.',
                            ],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.7,
                    ]);

                if ($response->successful()) {
                    $raw = $response->json('choices.0.message.content');
                    if (!empty($raw)) {
                        // Bersihkan tag markdown ```json ... ``` jika model menyertakannya
                        $clean = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
                        $clean = preg_replace('/\s*```$/', '', $clean);
                        $parsed = json_decode($clean, true);

                        if (is_array($parsed) && count($parsed) > 0) {
                            foreach ($parsed as $item) {
                                if (!empty($item['prompt']) && !empty($item['options']) && is_array($item['options'])) {
                                    $questionsData[] = [
                                        'prompt' => trim($item['prompt']),
                                        'options' => array_values(array_map('trim', $item['options'])),
                                        'answer_index' => isset($item['answer_index']) ? (int) $item['answer_index'] : 0,
                                    ];
                                }
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Fallback otomatis jika gateway AI offline
            }
        }

        // Fallback jika OmniRoute tidak aktif atau gagal mengembalikan format JSON
        if (empty($questionsData)) {
            $templates = [
                ['Apa inti dari konsep %s?', ['Definisi dasar pada materi', 'Istilah yang tidak berkaitan', 'Rumus yang sudah lama dihapus', 'Fungsi bantu yang jarang dipakai'], 0],
                ['Kapan %s paling tepat dipakai?', ['Saat data perlu akses acak cepat', 'Saat data bersifat sekuensial', 'Saat memori sangat terbatas', 'Saat hasil harus unik'], 1],
                ['Apa risiko utama dari %s?', ['Konsumsi memori berlebih', 'Hasil tidak konsisten', 'Kehilangan struktur data', 'Waktu akses bertambah linear'], 3],
                ['Contoh penerapan %s di aplikasi nyata adalah?', ['Antrean tiket event', 'Menghitung nilai rata-rata', 'Menyimpan riwayat chat', 'Mengurutkan nama mahasiswa'], 0],
            ];

            for ($i = 0; $i < $data['count']; $i++) {
                $t = $templates[$i % count($templates)];
                $questionsData[] = [
                    'prompt' => sprintf($t[0], $data['topic']),
                    'options' => $t[1],
                    'answer_index' => $t[2],
                ];
            }
        }

        foreach ($questionsData as $q) {
            Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => $q['prompt'],
                'options' => $q['options'],
                'answer_index' => $q['answer_index'],
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
