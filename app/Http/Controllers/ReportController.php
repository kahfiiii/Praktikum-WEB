<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Material;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function storeMaterial(Request $request, Material $material)
    {
        return $this->create($request, $material, 'material');
    }

    public function storeComment(Request $request, Comment $comment)
    {
        return $this->create($request, $comment, 'comment');
    }

    private function create(Request $request, $target, string $type)
    {
        if ((int) $target->user_id === (int) Auth::id()) {
            abort(403, 'Anda tidak dapat melaporkan konten milik sendiri.');
        }

        $alreadyReported = Report::where('reportable_type', $type)
            ->where('reportable_id', $target->id)
            ->where('reporter_id', Auth::id())
            ->whereIn('status', ['baru', 'diproses'])
            ->exists();

        if ($alreadyReported) {
            throw ValidationException::withMessages([
                'report' => 'Anda sudah pernah melaporkan konten ini dan sedang dalam peninjauan moderator.',
            ]);
        }

        $data = $request->validate([
            'reason' => ['required', 'in:hak-cipta,tidak-pantas,informasi-salah,lainnya'],
            'detail' => ['nullable', 'string', 'max:500'],
        ], [
            'reason.required' => 'Pilih alasan laporan dulu.',
            'reason.in' => 'Alasan laporan tidak dikenali.',
            'detail.max' => 'Keterangan maksimal 500 karakter.',
        ]);

        Report::create([
            'reportable_type' => $type,
            'reportable_id' => $target->id,
            'reporter_id' => Auth::id(),
            'reason' => $data['reason'],
            'detail' => $data['detail'] ?? null,
            'status' => 'baru',
        ]);

        return back()->with('status', 'Laporan terkirim. Moderator akan meninjau konten ini.');
    }

    public function upvote(Comment $comment)
    {
        if ((int) $comment->user_id === (int) Auth::id()) {
            abort(403, 'Anda tidak dapat memberikan upvote pada komentar milik sendiri.');
        }

        $message = DB::transaction(function () use ($comment) {
            $existing = CommentVote::where('comment_id', $comment->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                $existing->delete();
                $msg = 'Upvote dibatalkan.';
            } else {
                CommentVote::create([
                    'comment_id' => $comment->id,
                    'user_id' => Auth::id(),
                ]);
                $msg = 'Upvote terkirim.';
            }

            $totalVotes = CommentVote::where('comment_id', $comment->id)->count();
            $comment->update(['upvotes' => $totalVotes]);

            return $msg;
        });

        return back()->with('status', $message);
    }
}
