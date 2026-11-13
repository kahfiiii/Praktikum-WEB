<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function show(Material $material)
    {
        return view('materi-detail', [
            'material' => $material->load(['course', 'author']),
            'comments' => $material->comments()->with(['author', 'replies.author'])->get(),
        ]);
    }

    public function create()
    {
        return view('unggah', ['courses' => Course::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120'],
            'course_id' => ['required', 'exists:courses,id'],
            'file' => ['required', 'file', 'mimes:pdf,docx,pptx', 'max:5120'],
            'body' => ['nullable', 'string', 'max:20000'],
        ], [
            'title.required' => 'Judul materi wajib diisi.',
            'title.min' => 'Judul minimal 5 karakter.',
            'course_id.required' => 'Pilih mata kuliah dulu.',
            'file.required' => 'File materi wajib diunggah.',
            'file.mimes' => 'Format file harus PDF, DOCX, atau PPTX.',
            'file.max' => 'Ukuran file maksimal 5 MB (5120 KB).',
        ]);

        $file = $request->file('file');
        $path = $file->store('materi', 'local');

        $material = Material::create([
            'course_id' => $data['course_id'],
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'body' => $data['body'] ?? 'Materi belum memiliki catatan tambahan.',
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_type' => strtoupper($file->getClientOriginalExtension()),
            'file_size_kb' => (int) ceil($file->getSize() / 1024),
            'faculty' => Course::find($data['course_id'])?->faculty,
        ]);

        return redirect()->route('materi.detail', $material)
            ->with('status', 'Materi "'.$material->title.'" berhasil diunggah.');
    }

    public function edit(Material $material)
    {
        Gate::authorize('update', $material);

        return view('materi-edit', [
            'material' => $material,
            'courses' => Course::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Material $material)
    {
        Gate::authorize('update', $material);

        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120'],
            'course_id' => ['required', 'exists:courses,id'],
            'file' => ['nullable', 'file', 'mimes:pdf,docx,pptx', 'max:5120'],
            'body' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul materi wajib diisi.',
            'title.min' => 'Judul minimal 5 karakter.',
            'course_id.required' => 'Pilih mata kuliah dulu.',
            'file.mimes' => 'Format file harus PDF, DOCX, atau PPTX.',
            'file.max' => 'Ukuran file maksimal 5 MB (5120 KB).',
        ]);

        $needsReset = in_array($material->verification_status, ['verified', 'rejected'], true);

        $updateData = [
            'title' => $data['title'],
            'course_id' => $data['course_id'],
            'body' => $data['body'] ?? $material->body,
            'faculty' => Course::find($data['course_id'])?->faculty ?? $material->faculty,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($material->file_path && Storage::disk('local')->exists($material->file_path)) {
                Storage::disk('local')->delete($material->file_path);
            }
            $path = $file->store('materi', 'local');
            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $file->getClientOriginalName();
            $updateData['file_type'] = strtoupper($file->getClientOriginalExtension());
            $updateData['file_size_kb'] = (int) ceil($file->getSize() / 1024);
        }

        if ($needsReset) {
            $updateData['verification_status'] = 'unverified';
            $updateData['verified_by'] = null;
            $updateData['verified_at'] = null;
            $updateData['rejection_reason'] = null;
        }

        $material->update($updateData);

        $msg = $needsReset
            ? 'Perubahan tersimpan. Karena materi ini sebelumnya sudah ditinjau, statusnya kembali menjadi belum diverifikasi.'
            : 'Perubahan materi tersimpan.';

        return redirect()->route('materi.detail', $material)->with('status', $msg);
    }

    public function destroy(Material $material)
    {
        Gate::authorize('delete', $material);

        $title = $material->title;
        $course = $material->course;
        $material->delete();

        return redirect()->route('mata-kuliah.detail', $course)
            ->with('status', 'Materi "'.$title.'" berhasil dihapus.');
    }

    public function download(Material $material)
    {
        if (empty($material->file_path) || !Storage::disk('local')->exists($material->file_path)) {
            return back()->withErrors(['file' => 'File belum tersedia untuk diunduh.'])
                         ->with('error', 'File belum tersedia');
        }

        $filename = $material->original_filename ?: ($material->title.'.'.strtolower($material->file_type));

        return Storage::disk('local')->download($material->file_path, $filename);
    }

    public function comment(Request $request, Material $material)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'kind' => ['nullable', 'in:question,answer'],
            'source_ref' => ['nullable', 'string', 'max:200'],
        ], [
            'body.required' => 'Komentar tidak boleh kosong.',
            'body.min' => 'Komentar minimal 3 karakter.',
            'source_ref.max' => 'Sumber/referensi maksimal 200 karakter.',
        ]);

        Comment::create([
            'body' => $data['body'],
            'parent_id' => $data['parent_id'] ?? null,
            'kind' => $data['kind'] ?? 'answer',
            'source_ref' => $data['source_ref'] ?? null,
            'material_id' => $material->id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('status', 'Terkirim. Terima kasih sudah membantu teman belajar.');
    }
}
