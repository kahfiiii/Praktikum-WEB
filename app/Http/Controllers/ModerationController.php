<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Material;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModerationController extends Controller
{
    // ---------- Laporan ----------
    public function reports(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['reporter', 'reportable'])->latest();

        // Filter fakultas jika moderator non-admin
        if (! $user->isAdmin()) {
            $faculty = $user->faculty;
            $materialIds = Material::where('faculty', $faculty)
                ->orWhereHas('course', fn ($c) => $c->where('faculty', $faculty))
                ->pluck('id');

            $commentIds = Comment::whereHas('material', function ($m) use ($faculty) {
                $m->where('faculty', $faculty)
                    ->orWhereHas('course', fn ($c) => $c->where('faculty', $faculty));
            })->pluck('id');

            $query->where(function ($q) use ($materialIds, $commentIds) {
                $q->where(fn ($sub) => $sub->where('reportable_type', 'material')->whereIn('reportable_id', $materialIds))
                    ->orWhere(fn ($sub) => $sub->where('reportable_type', 'comment')->whereIn('reportable_id', $commentIds));
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($q = $request->query('q')) {
            $query->where(function ($w) use ($q) {
                $w->where('detail', 'like', "%{$q}%")
                    ->orWhere('reason', 'like', "%{$q}%")
                    ->orWhereHas('reporter', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        // Hitung statistik sesuai filter hak akses
        $countsQuery = clone $query;
        // hilangkan filter status dan q untuk menghitung tab status
        $baseCountQuery = Report::query();
        if (! $user->isAdmin()) {
            $faculty = $user->faculty;
            $materialIds = Material::where('faculty', $faculty)
                ->orWhereHas('course', fn ($c) => $c->where('faculty', $faculty))
                ->pluck('id');

            $commentIds = Comment::whereHas('material', function ($m) use ($faculty) {
                $m->where('faculty', $faculty)
                    ->orWhereHas('course', fn ($c) => $c->where('faculty', $faculty));
            })->pluck('id');

            $baseCountQuery->where(function ($q) use ($materialIds, $commentIds) {
                $q->where(fn ($sub) => $sub->where('reportable_type', 'material')->whereIn('reportable_id', $materialIds))
                    ->orWhere(fn ($sub) => $sub->where('reportable_type', 'comment')->whereIn('reportable_id', $commentIds));
            });
        }

        return view('moderasi.laporan', [
            'reports' => $query->paginate(15)->withQueryString(),
            'counts' => [
                'baru' => (clone $baseCountQuery)->where('status', 'baru')->count(),
                'diproses' => (clone $baseCountQuery)->where('status', 'diproses')->count(),
                'selesai' => (clone $baseCountQuery)->where('status', 'selesai')->count(),
            ],
            'status' => $status,
            'q' => $q,
        ]);
    }

    public function reportDetail(Report $report)
    {
        $this->authorizeReportModeration($report);

        return view('moderasi.laporan-detail', [
            'report' => $report->load('reporter'),
            'content' => $report->reportable_type === 'material'
                ? Material::find($report->reportable_id)
                : Comment::with('author')->find($report->reportable_id),
        ]);
    }

    public function reportProcess(Report $report)
    {
        $this->authorizeReportModeration($report);

        $report->update(['status' => 'diproses', 'handled_by' => Auth::id()]);

        return back()->with('status', 'Laporan ditandai sedang diproses.');
    }

    public function reportFinish(Report $report)
    {
        $this->authorizeReportModeration($report);

        $report->update(['status' => 'selesai', 'handled_by' => Auth::id()]);

        return redirect()->route('moderasi.laporan')->with('status', 'Laporan ditandai selesai.');
    }

    public function reportDeleteContent(Report $report)
    {
        $this->authorizeReportModeration($report);

        if ($report->reportable_type === 'material') {
            Material::find($report->reportable_id)?->delete();
        } else {
            Comment::find($report->reportable_id)?->delete();
        }

        $report->update(['status' => 'selesai', 'handled_by' => Auth::id()]);

        return redirect()->route('moderasi.laporan')->with('status', 'Konten yang dilaporkan sudah dihapus.');
    }

    private function authorizeReportModeration(Report $report): void
    {
        $targetFaculty = $report->getTargetFaculty();
        if (! Auth::user()->canModerateContent($targetFaculty)) {
            abort(403, 'Anda tidak memiliki hak akses untuk memoderasi laporan dari fakultas ini.');
        }
    }

    // ---------- Verifikasi materi ----------
    public function verifyMaterial(Material $material)
    {
        if ((int) $material->user_id === (int) Auth::id()) {
            abort(403, 'Anda tidak dapat memverifikasi materi milik sendiri.');
        }

        $faculty = $material->faculty ?? $material->course?->faculty;
        if (! Auth::user()->canModerateContent($faculty)) {
            abort(403, 'Anda tidak memiliki hak akses untuk memverifikasi materi dari fakultas ini.');
        }

        $material->update([
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('status', 'Materi berhasil diverifikasi.');
    }

    public function rejectMaterial(Request $request, Material $material)
    {
        if ((int) $material->user_id === (int) Auth::id()) {
            abort(403, 'Anda tidak dapat menolak materi milik sendiri.');
        }

        $faculty = $material->faculty ?? $material->course?->faculty;
        if (! Auth::user()->canModerateContent($faculty)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menolak materi dari fakultas ini.');
        }

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter agar jelas bagi pengunggah.',
        ]);

        $material->update([
            'verification_status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('status', 'Materi ditolak dengan catatan alasan.');
    }

    // ---------- Diskusi ----------
    public function verifyAnswer(Comment $comment)
    {
        if (empty($comment->parent_id)) {
            abort(422, 'Hanya jawaban pada pertanyaan yang dapat diverifikasi.');
        }

        $parent = Comment::findOrFail($comment->parent_id);
        $user = Auth::user();
        $isQuestionOwner = (int) $parent->user_id === (int) $user->id;
        $contentFaculty = $comment->material?->faculty ?? $comment->material?->course?->faculty;
        $canModerate = $user->canModerateContent($contentFaculty);

        if (! $isQuestionOwner && ! $canModerate) {
            abort(403, 'Anda tidak memiliki hak untuk memverifikasi jawaban ini.');
        }

        DB::transaction(function () use ($comment, $parent, $user) {
            // Reset jawaban lain pada pertanyaan yang sama
            Comment::where('parent_id', $parent->id)->update([
                'is_verified' => false,
                'verified_by' => null,
            ]);

            // Set jawaban yang baru sebagai terverifikasi
            $comment->update([
                'is_verified' => true,
                'verified_by' => $user->id,
            ]);

            // Set pertanyaan induk sebagai terverifikasi
            $parent->update([
                'is_verified' => true,
            ]);
        });

        return back()->with('status', 'Jawaban berhasil diverifikasi.');
    }

    public function destroyComment(Comment $comment)
    {
        $user = Auth::user();
        $contentFaculty = $comment->material?->faculty ?? $comment->material?->course?->faculty;

        if (! $user->canModerateContent($contentFaculty)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('status', 'Komentar berhasil dihapus.');
    }
}
