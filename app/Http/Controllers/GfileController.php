<?php

namespace App\Http\Controllers;

use App\Models\Gfile;
use App\Models\GfileMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class GfileController extends Controller
{
    public $gfolder;

    public function __construct()
    {
        $this->gfolder = 'arsip_ramzi';
    }

    public function index()
    {
        $fileEntry = GfileMedia::with('gfile')->paginate(2);

        // dd($fileEntry);

        return view('gfiles.index', compact('fileEntry'));
    }


    public function create()
    {
        return view('gfiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file.*' => 'required|file|max:102400', // 100MB max
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $fileEntry = Gfile::create([
            'description' => $request->description,
        ]);


        // Upload semua file ke FileEntry yang sama
        $data = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileName = $file->getClientOriginalName();
                $storeFile = $this->gfolder . '/' . $fileName;
                // dd($storeFile);
                $fileExtension = $file->getClientOriginalExtension(); // Ekstensi file
                $fileSize = $file->getSize(); // Ukuran file dalam bytes
                $fileMimeType = $file->getMimeType(); // Tipe MIME file
                Gdrive::put($storeFile, $file);
                // $path = Storage::disk('google')->put($storeFile, $file);
                $data[] = [
                    'gfile_id' => $fileEntry->id,
                    'path' => $storeFile,
                    'driver' => 'google',
                    'file_name' => $fileName,
                    'file_extension' => $fileExtension,
                    'file_mime_type' => $fileMimeType,
                    'file_size' => $fileSize,
                ];
            }
        }
        if ($data != null) {
            GfileMedia::insert($data);
        }

        // Handle tags
        if ($request->tags) {
            $tags = explode(',', $request->tags);
            $fileEntry->syncTags($tags);
        }

        return redirect()->route('gfiles.index')
            ->with('success', 'File berhasil diupload.');
    }
}
