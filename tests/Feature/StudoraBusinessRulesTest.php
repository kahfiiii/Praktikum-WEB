<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Course;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudoraBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    private User $student1;
    private User $student2;
    private User $moderatorFilkom;
    private User $moderatorTeknik;
    private User $admin;
    private Course $courseFilkom;
    private Course $courseTeknik;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->student1 = User::create([
            'name' => 'Sohibul',
            'email' => 'sohibul@student.ub.ac.id',
            'password' => bcrypt('password'),
            'role' => 'student',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->student2 = User::create([
            'name' => 'Dewi',
            'email' => 'dewi@student.ub.ac.id',
            'password' => bcrypt('password'),
            'role' => 'student',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->moderatorFilkom = User::create([
            'name' => 'Nadia',
            'email' => 'nadia@student.ub.ac.id',
            'password' => bcrypt('password'),
            'role' => 'moderator',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->moderatorTeknik = User::create([
            'name' => 'Budi',
            'email' => 'budi@student.ub.ac.id',
            'password' => bcrypt('password'),
            'role' => 'moderator',
            'faculty' => 'Fakultas Teknik',
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@student.ub.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->courseFilkom = Course::create([
            'name' => 'Struktur Data',
            'code' => 'TIF2103',
            'faculty' => 'Fakultas Ilmu Komputer',
            'semester' => 'Genap',
        ]);

        $this->courseTeknik = Course::create([
            'name' => 'Mekanika Fluida',
            'code' => 'TKN2101',
            'faculty' => 'Fakultas Teknik',
            'semester' => 'Gasal',
        ]);
    }

    /**
     * BR-02: Edit & Hapus hanya oleh pemilik; Kuis draft hanya bisa dilihat pembuatnya.
     */
    public function test_br02_user_lain_tidak_bisa_edit_atau_hapus_materi_dan_kuis_draft(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Pointer',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        // Student2 mencoba edit materi milik student1 -> 403
        $this->actingAs($this->student2)
            ->get(route('materi.edit', $material))
            ->assertStatus(403);

        $this->actingAs($this->student2)
            ->put(route('materi.update', $material), [
                'title' => 'Judul Dibajak',
                'course_id' => $this->courseFilkom->id,
            ])
            ->assertStatus(403);

        // Student2 mencoba hapus materi milik student1 -> 403
        $this->actingAs($this->student2)
            ->delete(route('materi.destroy', $material))
            ->assertStatus(403);

        // Pemilik materi (student1) berhasil mengupdate dan menghapus
        $this->actingAs($this->student1)
            ->put(route('materi.update', $material), [
                'title' => 'Catatan Pointer Diperbarui',
                'course_id' => $this->courseFilkom->id,
            ])
            ->assertRedirect(route('materi.detail', $material));

        $this->assertDatabaseHas('materials', ['title' => 'Catatan Pointer Diperbarui']);

        // Kuis draft hanya bisa dilihat oleh pembuatnya
        $quiz = Quiz::create([
            'title' => 'Kuis Rahasia',
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'status' => 'draft',
        ]);

        $this->actingAs($this->student2)
            ->get(route('kuis.preview', $quiz))
            ->assertStatus(403);

        $this->actingAs($this->student1)
            ->get(route('kuis.preview', $quiz))
            ->assertStatus(200);
    }

    /**
     * BR-03: Menolak penghapusan mata kuliah jika masih memiliki materi.
     */
    public function test_br03_admin_tidak_bisa_hapus_mata_kuliah_yang_memiliki_materi(): void
    {
        Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Linked List',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.kelola.destroy', $this->courseFilkom));

        $response->assertSessionHasErrors('course');
        $this->assertDatabaseHas('courses', ['id' => $this->courseFilkom->id]);

        // Mata kuliah tanpa materi boleh dihapus
        $responseSuccess = $this->actingAs($this->admin)
            ->delete(route('admin.kelola.destroy', $this->courseTeknik));

        $responseSuccess->assertRedirect(route('admin.kelola'));
        $this->assertDatabaseMissing('courses', ['id' => $this->courseTeknik->id]);
    }

    /**
     * BR-09: Mahasiswa biasa tidak bisa verifikasi materi.
     */
    public function test_br09_mahasiswa_biasa_tidak_bisa_verifikasi_materi(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Graph',
            'verification_status' => 'unverified',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->actingAs($this->student2)
            ->post(route('moderasi.materi.verifikasi', $material))
            ->assertStatus(403);

        $this->assertEquals('unverified', $material->fresh()->verification_status);
    }

    /**
     * BR-10: Edit materi terverifikasi atau ditolak mereset status ke unverified.
     */
    public function test_br10_edit_materi_verified_atau_rejected_mereset_status_ke_unverified(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Binary Tree',
            'verification_status' => 'verified',
            'verified_by' => $this->moderatorFilkom->id,
            'verified_at' => now(),
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->actingAs($this->student1)
            ->put(route('materi.update', $material), [
                'title' => 'Catatan Binary Tree Edisi 2',
                'course_id' => $this->courseFilkom->id,
            ])
            ->assertRedirect(route('materi.detail', $material));

        $fresh = $material->fresh();
        $this->assertEquals('unverified', $fresh->verification_status);
        $this->assertNull($fresh->verified_by);
        $this->assertNull($fresh->verified_at);
        $this->assertNull($fresh->rejection_reason);

        // Coba dengan status rejected
        $fresh->update([
            'verification_status' => 'rejected',
            'rejection_reason' => 'Ada hak cipta',
            'verified_by' => $this->moderatorFilkom->id,
        ]);

        $this->actingAs($this->student1)
            ->put(route('materi.update', $material), [
                'title' => 'Catatan Binary Tree Edisi Revisi',
                'course_id' => $this->courseFilkom->id,
            ]);

        $reFresh = $material->fresh();
        $this->assertEquals('unverified', $reFresh->verification_status);
        $this->assertNull($reFresh->rejection_reason);
        $this->assertNull($reFresh->verified_by);
    }

    /**
     * BR-11: Moderator tidak boleh memverifikasi atau menolak materi milik sendiri.
     */
    public function test_br11_moderator_tidak_bisa_verifikasi_atau_tolak_materi_sendiri(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->moderatorFilkom->id,
            'title' => 'Catatan Pribadi Moderator',
            'verification_status' => 'unverified',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        // Coba verifikasi materi milik sendiri -> 403
        $this->actingAs($this->moderatorFilkom)
            ->post(route('moderasi.materi.verifikasi', $material))
            ->assertStatus(403);

        // Coba tolak materi milik sendiri -> 403
        $this->actingAs($this->moderatorFilkom)
            ->post(route('moderasi.materi.tolak', $material), [
                'rejection_reason' => 'Alasan penolakan materi sendiri yang cukup panjang.',
            ])
            ->assertStatus(403);

        $this->assertEquals('unverified', $material->fresh()->verification_status);
    }

    /**
     * BR-12: Tolak upvote pada komentar milik sendiri dan upvotes konsisten.
     */
    public function test_br12_tolak_upvote_komentar_sendiri_dan_jumlah_upvote_konsisten(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Materi Pertanyaan',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $comment = Comment::create([
            'material_id' => $material->id,
            'user_id' => $this->student1->id,
            'body' => 'Pertanyaan saya sendiri?',
            'kind' => 'question',
            'upvotes' => 0,
        ]);

        // Upvote komentar sendiri ditolak -> 403
        $this->actingAs($this->student1)
            ->post(route('komentar.upvote', $comment))
            ->assertStatus(403);

        $this->assertEquals(0, $comment->fresh()->upvotes);

        // Student2 upvote komentar student1 -> bertambah 1
        $this->actingAs($this->student2)
            ->post(route('komentar.upvote', $comment))
            ->assertRedirect();

        $this->assertEquals(1, $comment->fresh()->upvotes);
        $this->assertEquals(1, CommentVote::where('comment_id', $comment->id)->count());

        // Student2 klik upvote lagi -> toggle batalkan upvote
        $this->actingAs($this->student2)
            ->post(route('komentar.upvote', $comment))
            ->assertRedirect();

        $this->assertEquals(0, $comment->fresh()->upvotes);
        $this->assertEquals(0, CommentVote::where('comment_id', $comment->id)->count());
    }

    /**
     * BR-13: Verifikasi jawaban oleh penanya diizinkan, orang lain 403, hanya satu terverifikasi.
     */
    public function test_br13_verifikasi_jawaban_oleh_penanya_dan_hanya_satu_terverifikasi(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Materi Diskusi',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $question = Comment::create([
            'material_id' => $material->id,
            'user_id' => $this->student1->id,
            'body' => 'Bagaimana implementasi BFS?',
            'kind' => 'question',
        ]);

        $answer1 = Comment::create([
            'material_id' => $material->id,
            'user_id' => $this->student2->id,
            'parent_id' => $question->id,
            'body' => 'Pakai Queue mas.',
            'kind' => 'answer',
            'is_verified' => false,
        ]);

        $answer2 = Comment::create([
            'material_id' => $material->id,
            'user_id' => $this->moderatorFilkom->id,
            'parent_id' => $question->id,
            'body' => 'Pakai antrean FIFO dan tracking visited node.',
            'kind' => 'answer',
            'is_verified' => false,
        ]);

        // Orang lain (bukan penanya dan bukan moderator/admin) coba verifikasi -> 403
        $otherStudent = User::create([
            'name' => 'Orang Lain',
            'email' => 'oranglain@student.ub.ac.id',
            'password' => bcrypt('password'),
            'role' => 'student',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        $this->actingAs($otherStudent)
            ->post(route('moderasi.komentar.verifikasi', $answer1))
            ->assertStatus(403);

        // Penanya (student1) memverifikasi answer1 -> berhasil
        $this->actingAs($this->student1)
            ->post(route('moderasi.komentar.verifikasi', $answer1))
            ->assertRedirect();

        $this->assertTrue($answer1->fresh()->is_verified);
        $this->assertFalse($answer2->fresh()->is_verified);

        // Penanya kemudian memverifikasi answer2 -> answer1 otomatis di-reset, hanya answer2 yang verified
        $this->actingAs($this->student1)
            ->post(route('moderasi.komentar.verifikasi', $answer2))
            ->assertRedirect();

        $this->assertFalse($answer1->fresh()->is_verified);
        $this->assertTrue($answer2->fresh()->is_verified);
    }

    /**
     * Moderator beda fakultas ditolak memoderasi konten fakultas lain.
     */
    public function test_moderator_beda_fakultas_ditolak(): void
    {
        $materialTeknik = Material::create([
            'course_id' => $this->courseTeknik->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Termodinamika',
            'faculty' => 'Fakultas Teknik',
            'verification_status' => 'unverified',
        ]);

        // Moderator FILKOM coba verifikasi materi Teknik -> 403
        $this->actingAs($this->moderatorFilkom)
            ->post(route('moderasi.materi.verifikasi', $materialTeknik))
            ->assertStatus(403);

        // Moderator Teknik berhasil verifikasi materi Teknik
        $this->actingAs($this->moderatorTeknik)
            ->post(route('moderasi.materi.verifikasi', $materialTeknik))
            ->assertRedirect();

        $this->assertEquals('verified', $materialTeknik->fresh()->verification_status);

        // Admin bebas verifikasi lintas fakultas
        $materialTeknik2 = Material::create([
            'course_id' => $this->courseTeknik->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Statika Struktur',
            'faculty' => 'Fakultas Teknik',
            'verification_status' => 'unverified',
        ]);

        $this->actingAs($this->admin)
            ->post(route('moderasi.materi.verifikasi', $materialTeknik2))
            ->assertRedirect();

        $this->assertEquals('verified', $materialTeknik2->fresh()->verification_status);
    }

    /**
     * Laporan ganda untuk target yang sama ditolak, dan tidak bisa melaporkan konten sendiri.
     */
    public function test_laporan_ganda_dan_lapor_konten_sendiri_ditolak(): void
    {
        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Kontroversial',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        // Student1 melaporkan materi miliknya sendiri -> 403
        $this->actingAs($this->student1)
            ->post(route('materi.lapor', $material), [
                'reason' => 'tidak-pantas',
                'detail' => 'Lapor konten sendiri',
            ])
            ->assertStatus(403);

        // Student2 melaporkan materi student1 pertama kali -> berhasil
        $this->actingAs($this->student2)
            ->post(route('materi.lapor', $material), [
                'reason' => 'hak-cipta',
                'detail' => 'Slide mengambil gambar buku',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('reports', 1);

        // Student2 melaporkan lagi untuk target yang sama yang statusnya masih 'baru' -> ditolak
        $response = $this->actingAs($this->student2)
            ->post(route('materi.lapor', $material), [
                'reason' => 'hak-cipta',
                'detail' => 'Slide mengambil gambar buku lagi',
            ]);

        $response->assertSessionHasErrors('report');
        $this->assertDatabaseCount('reports', 1);
    }

    /**
     * Unduh materi membutuhkan autentikasi (login).
     */
    public function test_unduh_materi_perlu_login(): void
    {
        Storage::disk('local')->put('materi/test.pdf', 'Dummy content');

        $material = Material::create([
            'course_id' => $this->courseFilkom->id,
            'user_id' => $this->student1->id,
            'title' => 'Catatan Siap Unduh',
            'file_path' => 'materi/test.pdf',
            'original_filename' => 'catatan-siap-unduh.pdf',
            'file_type' => 'PDF',
            'faculty' => 'Fakultas Ilmu Komputer',
        ]);

        // Tamu (guest) mengakses rute unduh -> diarahkan ke login
        $this->get(route('materi.unduh', $material))
            ->assertRedirect(route('masuk'));

        // User terautentikasi dapat mengunduh
        $response = $this->actingAs($this->student1)
            ->get(route('materi.unduh', $material));

        $response->assertStatus(200);
    }
}
