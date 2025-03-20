<?php

namespace App\Http\Controllers;

use App\Models\FileEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $fileEntry = FileEntry::with('media')->get();

        return view('files.index', compact('fileEntry'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file.*' => 'required|file|max:102400', // 100MB max
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $fileEntry = FileEntry::create([
            'description' => $request->description,
        ]);

        // if ($request->hasFile('file')) {
        //     $fileEntry->addMediaFromRequest('file')
        //         ->toMediaCollection('ramzi');
        // }

        // dd($request);

        // Upload semua file ke FileEntry yang sama
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                // dd($file);
                $fileEntry->addMedia($file)
                    ->toMediaCollection('ramzi');
            }
        }

        // Handle tags
        if ($request->tags) {
            $tags = explode(',', $request->tags);
            $fileEntry->syncTags($tags);
        }

        return redirect()->route('files.index')
            ->with('success', 'File berhasil diupload.');
    }

    public function show(FileEntry $fileEntry)
    {


        return view('files.show', compact('fileEntry'));
    }

    public function destroy(FileEntry $fileEntry)
    {
        $fileEntry->clearMediaCollection('ramzi');
        $fileEntry->delete();

        return redirect()->route('files.index')
            ->with('success', 'File berhasil dihapus.');
    }

    public function download(FileEntry $fileEntry)
    {
        $mediaItem = $fileEntry->getFirstMedia('ramzi');


        if ($mediaItem) {

            // Baca konten file
            $fileContents = Storage::disk('r2')->get($mediaItem->getPath());

            // Buat response dengan header yang memaksa download
            $response = response($fileContents)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $mediaItem->file_name . '"')
                ->header('Content-Length', $mediaItem->size);

            return $response;
        } else {
            return redirect()->route('files.index')
                ->with('error', 'File tidak ditemukan.');
        }
    }
}
