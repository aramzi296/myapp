<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileUploadController extends Controller
{
    public function index()
    {

        // ambil data file yang diupload dengan single file upload
        // $files = FileUpload::latest()->paginate(10);
        // return view('uploads.index', compact('files'));

        // Fetch FileUpload records with their media, paginated
        $fileUploads = FileUpload::with('media')
            ->latest()
            ->paginate(10);
        return view('uploads.index', compact('fileUploads'));
    }

    public function store(Request $request)
    {
        // Jika single file upload, gunakan kode ini
        // $request->validate([
        //     'file' => 'required|file|max:10240', // Maksimal 10MB
        // ]);

        // Jika upload multiple file, gunakan kode ini
        $request->validate([
            'title' => 'required|string|max:255',
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:10240', // Max 10MB per file
        ], [
            'title.required' => 'Title harus ada.',
            'files.required' => 'File harus ada.',
        ]);

        $fileUpload = FileUpload::create([
            'title' => $request->input('title', 'Untitled File')
        ]);

        // Jika single file upload, gunakan kode ini
        // $fileUpload->addMediaFromRequest('file')
        //     ->toMediaCollection('cloudflare_r2');


        // Jika multiple file upload, gunakan kode ini
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileUpload->addMedia($file)
                    ->toMediaCollection('cloudflare_r2');
            }
        }

        return redirect()->back()->with('success', 'File berhasil diupload');
    }

    public function destroy($id)
    {
        $file = Media::findOrFail($id);
        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deleteTitle($id)
    {
        // hapus file
        $files = Media::where('model_id', $id)->get();
        foreach ($files as $file) {
            $file->delete();
        }

        // hapus title
        $title = FileUpload::findOrFail($id);
        $title->delete();

        return redirect()->back()->with('success', 'Title dan file berhasil dihapus');
    }

    public function downloadMedia($id)
    {
        $media = Media::findOrFail($id);
        return response()->download($media->getPath(), $media->file_name);
    }
}
