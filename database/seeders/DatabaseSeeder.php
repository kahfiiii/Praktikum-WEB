<?php

namespace Database\Seeders;

use App\Models\Attempt;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Material;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin STUDORA',
            'email' => 'admin@student.ub.ac.id',
            'password' => 'password',
            'role' => 'admin',
            'nim' => '255150707000',
            'initials' => 'AD',
            'faculty' => 'Fakultas Ilmu Komputer',
            'study_program' => 'Informatika',
        ]);

        $nadia = User::create([
            'name' => 'Nadia Putri',
            'email' => 'nadia@student.ub.ac.id',
            'password' => 'password',
            'role' => 'moderator',
            'nim' => '255150707111',
            'initials' => 'NP',
            'faculty' => 'Fakultas Ilmu Komputer',
            'study_program' => 'Informatika',
        ]);

        $budi = User::create([
            'name' => 'Budi Pratama',
            'email' => 'budi@student.ub.ac.id',
            'password' => 'password',
            'role' => 'moderator',
            'nim' => '235150207222',
            'initials' => 'BP',
            'faculty' => 'Fakultas Teknik',
            'study_program' => 'Teknik Elektro',
        ]);

        $sohibul = User::create([
            'name' => 'Mohamad Sohibul Kahfi',
            'email' => 'sohibulkahfi@student.ub.ac.id',
            'password' => 'password',
            'role' => 'student',
            'nim' => '255150707111033',
            'initials' => 'SK',
            'faculty' => 'Fakultas Ilmu Komputer',
            'study_program' => 'Informatika',
        ]);

        $rahmat = User::create([
            'name' => 'Rahmat Hidayat',
            'email' => 'rahmat@student.ub.ac.id',
            'password' => 'password',
            'role' => 'student',
            'nim' => '235150207111',
            'initials' => 'RH',
            'faculty' => 'Fakultas Teknik',
            'study_program' => 'Teknik Sipil',
        ]);

        $dewi = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@student.ub.ac.id',
            'password' => 'password',
            'role' => 'student',
            'nim' => '245150607111',
            'initials' => 'DL',
            'faculty' => 'Fakultas Ekonomi dan Bisnis',
            'study_program' => 'Manajemen',
        ]);

        $courses = [
            ['faculty' => 'Fakultas Ilmu Komputer', 'name' => 'Struktur Data', 'code' => 'TIF2103', 'semester' => 'Genap', 'accent' => 'primary', 'description' => 'Linked list, stack, queue, tree, dan graph dengan contoh soal yang sering keluar di kuis.'],
            ['faculty' => 'Fakultas Ilmu Komputer', 'name' => 'Basis Data', 'code' => 'TIF2104', 'semester' => 'Genap', 'accent' => 'coral', 'description' => 'Normalisasi, SQL, dan perancangan ERD untuk kebutuhan aplikasi nyata.'],
            ['faculty' => 'Fakultas Ilmu Komputer', 'name' => 'Kecerdasan Buatan', 'code' => 'TIF2140', 'semester' => 'Gasal', 'accent' => 'violet', 'description' => 'Pencarian heuristik, logika fuzzy, dan dasar machine learning.'],
            ['faculty' => 'Fakultas Ilmu Komputer', 'name' => 'Pemrograman Web', 'code' => 'TIF2201', 'semester' => 'Gasal', 'accent' => 'lime', 'description' => 'Laravel, Blade, dan deploy aplikasi web dari nol sampai online.'],
            ['name' => 'Jaringan Komputer', 'code' => 'TIF2202', 'semester' => 'Gasal', 'accent' => 'primary', 'faculty' => 'Fakultas Ilmu Komputer', 'description' => 'Subnetting, routing, dan konfigurasi perangkat jaringan.'],
            ['name' => 'Sistem Operasi', 'code' => 'TIF2203', 'semester' => 'Genap', 'accent' => 'coral', 'faculty' => 'Fakultas Ilmu Komputer', 'description' => 'Penjadwalan proses, manajemen memori, dan sinkronisasi.'],
            ['name' => 'Hukum Perdata', 'code' => 'HKU2101', 'semester' => 'Gasal', 'accent' => 'primary', 'faculty' => 'Fakultas Hukum', 'description' => 'Dasar perikatan, perjanjian, dan penyelesaian sengketa.'],
            ['name' => 'Manajemen Keuangan', 'code' => 'EKB2202', 'semester' => 'Genap', 'accent' => 'coral', 'faculty' => 'Fakultas Ekonomi dan Bisnis', 'description' => 'Analisis laporan keuangan dan keputusan investasi.'],
            ['name' => 'Mekanika Fluida', 'code' => 'TKN2101', 'semester' => 'Gasal', 'accent' => 'lime', 'faculty' => 'Fakultas Teknik', 'description' => 'Konsep aliran fluida, persamaan Bernoulli, dan aplikasinya pada keteknikan.'],
            ['name' => 'Anatomi Dasar', 'code' => 'KDK1101', 'semester' => 'Gasal', 'accent' => 'violet', 'faculty' => 'Fakultas Kedokteran', 'description' => 'Morfologi, struktur organ tubuh manusia, dan istilah anatomi dasar.'],
        ];

        $created = [];
        foreach ($courses as $data) {
            $created[] = Course::create($data);
        }

        $sd = $created[0];
        $ft = $created[8]; // Mekanika Fluida

        // Buat mock file di storage private untuk demo download
        Storage::disk('local')->put('materi/sample-linked-list.pdf', "%PDF-1.4 Mock PDF Ringkasan Linked List Studora UB");

        $materials = [
            [
                'title' => 'Ringkasan Linked List & Implementasinya',
                'file_type' => 'PDF',
                'file_size_kb' => 1840,
                'file_path' => 'materi/sample-linked-list.pdf',
                'original_filename' => 'ringkasan-linked-list.pdf',
                'verification_status' => 'verified',
                'faculty' => 'Fakultas Ilmu Komputer',
                'body' => "Linked list adalah struktur data linear yang setiap elemennya menyimpan data dan pointer ke elemen berikutnya.\n\nKeunggulan utama dibanding array: penambahan dan penghapusan di tengah data berjalan O(1) selama pointer sudah diketahui. Kelemahan: akses acak harus traversal dari head, jadi O(n).\n\nJenis yang wajib paham: single linked list, double linked list, dan circular linked list. Untuk soal ujian, biasanya muncul perintah membalik linked list secara iteratif.",
            ],
            [
                'title' => 'Slide: Stack dan Queue',
                'file_type' => 'PPTX',
                'file_size_kb' => 3120,
                'verification_status' => 'unverified',
                'faculty' => 'Fakultas Ilmu Komputer',
                'body' => "Stack memakai prinsip LIFO (last in first out), queue memakai FIFO (first in first out).\n\nOperasi stack: push, pop, peek. Operasi queue: enqueue, dequeue, front.\n\nImplementasi dengan array punya batas kapasitas, sedangkan implementasi dengan linked list lebih fleksibel.",
            ],
            [
                'title' => 'Latihan Soal Tree + Pembahasan',
                'file_type' => 'PDF',
                'file_size_kb' => 980,
                'verification_status' => 'verified',
                'faculty' => 'Fakultas Ilmu Komputer',
                'body' => "Binary tree adalah pohon yang tiap node-nya punya maksimal dua anak.\n\nTraversal yang wajib hafal: preorder (root-left-right), inorder (left-root-right), postorder (left-right-root), dan level order memakai queue.\n\nSoal latihan biasanya menanyakan urutan hasil traversal dari pohon yang diberikan.",
            ],
            [
                'title' => 'Modul Praktikum Graph',
                'file_type' => 'PDF',
                'file_size_kb' => 2210,
                'verification_status' => 'rejected',
                'rejection_reason' => 'Slide memakai gambar berhak cipta tanpa izin penerbit.',
                'faculty' => 'Fakultas Ilmu Komputer',
                'body' => "Graph terdiri dari vertex dan edge. Bisa direpresentasikan dengan adjacency matrix atau adjacency list.\n\nBFS memakai queue dan menghasilkan shortest path pada graph tanpa bobot. DFS memakai stack atau rekursi.",
            ],
        ];

        $madeMaterials = [];
        foreach ($materials as $m) {
            $madeMaterials[] = Material::create($m + ['course_id' => $sd->id, 'user_id' => $sohibul->id]);
        }

        // Tambah materi untuk Fakultas Teknik oleh Rahmat
        $materiTeknik = Material::create([
            'course_id' => $ft->id,
            'user_id' => $rahmat->id,
            'title' => 'Catatan Aliran Laminar & Turbulen',
            'file_type' => 'PDF',
            'file_size_kb' => 1500,
            'verification_status' => 'unverified',
            'faculty' => 'Fakultas Teknik',
            'body' => 'Catatan lengkap rumus bilangan Reynolds dan penerapannya pada pipa tertutup.',
        ]);

        $first = $madeMaterials[1];
        $c1 = Comment::create([
            'material_id' => $first->id,
            'user_id' => $sohibul->id,
            'kind' => 'question',
            'upvotes' => 4,
            'body' => 'Bagian implementasi stack pakai linked list masih bingung, waktu pop pointer-nya harus digeser ke mana ya?',
        ]);
        Comment::create([
            'material_id' => $first->id,
            'user_id' => $nadia->id,
            'parent_id' => $c1->id,
            'kind' => 'answer',
            'body' => 'Top diarahkan ke top->next, lalu node lama di-free. Kalau top jadi null berarti stack kosong.',
            'source_ref' => 'Slide pertemuan 4, halaman 12',
            'is_verified' => true,
            'verified_by' => $nadia->id,
            'upvotes' => 12,
        ]);
        Comment::create([
            'material_id' => $first->id,
            'user_id' => $rahmat->id,
            'parent_id' => $c1->id,
            'kind' => 'answer',
            'body' => 'Pop itu O(1) karena cuma memindah top, bukan traversal.',
            'upvotes' => 3,
        ]);
        $c2 = Comment::create([
            'material_id' => $first->id,
            'user_id' => $sohibul->id,
            'kind' => 'question',
            'upvotes' => 1,
            'body' => 'Slide nomor 12 ada salah ketik di kompleksitas waktu dequeue, seharusnya O(1) bukan O(n).',
            'reports' => 2,
            'status' => 'pending',
        ]);
        Comment::create([
            'material_id' => $first->id,
            'user_id' => $dewi->id,
            'parent_id' => $c2->id,
            'kind' => 'answer',
            'upvotes' => 2,
            'body' => 'Setuju, sudah saya cek juga di catatan dosen.',
            'status' => 'approved',
        ]);

        $quizData = [
            ['title' => 'Latihan Linked List Dasar', 'source' => 'manual', 'status' => 'published', 'duration_minutes' => 15],
            ['title' => 'Kuis AI · Struktur Data', 'source' => 'ai', 'status' => 'draft', 'duration_minutes' => 20],
            ['title' => 'Stack & Queue Kilat', 'source' => 'manual', 'status' => 'published', 'duration_minutes' => 10],
        ];

        $firstQuizId = null;
        foreach ($quizData as $q) {
            $quiz = Quiz::create($q + ['course_id' => $sd->id, 'user_id' => $sohibul->id, 'material_id' => $first->id]);
            $firstQuizId ??= $quiz->id;

            Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => 'Apa kompleksitas waktu mengakses elemen ke-n pada singly linked list?',
                'options' => ['O(1)', 'O(log n)', 'O(n)', 'O(n log n)'],
                'answer_index' => 2,
            ]);
            Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => 'Struktur data yang memakai prinsip LIFO adalah?',
                'options' => ['Queue', 'Stack', 'Graph', 'Tree'],
                'answer_index' => 1,
            ]);
            Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => 'Traversal inorder pada binary tree mengunjungi node dengan urutan?',
                'options' => ['Root, kiri, kanan', 'Kiri, root, kanan', 'Kiri, kanan, root', 'Kanan, root, kiri'],
                'answer_index' => 1,
            ]);
        }

        Attempt::create([
            'quiz_id' => $firstQuizId,
            'user_id' => $sohibul->id,
            'score' => 80,
            'completed_at' => now()->subDays(3),
        ]);
        Attempt::create([
            'quiz_id' => $firstQuizId,
            'user_id' => $dewi->id,
            'score' => 60,
            'completed_at' => now()->subDay(),
        ]);

        Report::create([
            'reportable_type' => 'comment',
            'reportable_id' => $c2->id,
            'reporter_id' => $rahmat->id,
            'reason' => 'informasi-salah',
            'detail' => 'Komentar menyebut kompleksitas dequeue O(n), padahal di slide resmi O(1).',
            'status' => 'baru',
        ]);
        Report::create([
            'reportable_type' => 'material',
            'reportable_id' => $madeMaterials[3]->id,
            'reporter_id' => $dewi->id,
            'reason' => 'hak-cipta',
            'detail' => 'Gambar di slide diambil dari buku penerbit tanpa izin.',
            'status' => 'baru',
        ]);
        Report::create([
            'reportable_type' => 'comment',
            'reportable_id' => $c1->id,
            'reporter_id' => $rahmat->id,
            'reason' => 'tidak-pantas',
            'detail' => 'Nada balasan terlalu kasar ke penanya.',
            'status' => 'diproses',
            'handled_by' => $nadia->id,
        ]);

        // Laporan konten teknik
        Report::create([
            'reportable_type' => 'material',
            'reportable_id' => $materiTeknik->id,
            'reporter_id' => $sohibul->id,
            'reason' => 'informasi-salah',
            'detail' => 'Rumus Reynolds di halaman 3 ada salah satuan.',
            'status' => 'baru',
        ]);

        $this->command?->info('Seeded: '.count($created).' mata kuliah, '.(count($madeMaterials) + 1).' materi, 3 kuis, 4 laporan.');
    }
}
