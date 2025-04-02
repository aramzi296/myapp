<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SetupAppController extends Controller
{


    // Langkah:

    // - buat tabel: anggotas
    // - buat tabel: member_expertise
    // - import tabel: keahlians 
    // - import tabel: anggotas
    // - import tabel: negaras 

    // - selesaikan dulu migrasi database
    // - export table asal sebagai sql file. contoh: anggota.sql 
    // - import sql file di atas
    // - perbaiki method di bawah ini supaya cocok field tabel asal dengan tabel baru.
    // - jalankan router untuk method di bawah ini. jika router belum ada, buatlah
    // - hapus tabel yang diimport yang tidak diperlukan lagi.


    public function createUser()
    {
        // Fetch all records from the anggota table
        $anggotas = DB::table('anggotas')->select('nama_anggota', 'email_anggota')
            ->whereNotNull('email_anggota')
            ->get();

        $data = [];

        $password =  Hash::make('12345678');

        foreach ($anggotas as $value) {
            $data[] = [
                'name' => $value->nama_anggota,
                'email' => $value->email_anggota,
                'password' => $password,
            ];
        }

        User::insert($data);
        return 'Users had been created...';
    }

    public function createCountryList() {}

    public function createMember()
    {

        $anggotas = DB::table('users')->leftJoin('anggotas', 'users.email', '=', 'anggotas.email_anggota')
            ->whereNotNull('anggotas.id')
            ->select('users.id as userId', 'users.email as userEmail', 'users.name as userName', 'anggotas.*')
            ->orderBy('users.id', 'asc')
            ->get();
        // dd($anggotas);


        // $data = [];
        foreach ($anggotas as $anggota) {
            Member::create([
                'prefix' => $anggota->prefix_anggota ?? "",
                'suffix'  => $anggota->suffix_anggota ?? "",
                'user_id'  => $anggota->userId,
                'job_title'  => $anggota->job_title ?? "",
                'company'  => $anggota->affiliation_anggota ?? "",
                // 'department'  => $anggota->nama_anggota,
                'address'  => $anggota->alamat_anggota ?? "",
                'country_id'  => $anggota->negara_id,
                'website'  => $anggota->website_anggota ?? "",
                'bio'  => $anggota->bio_anggota ?? "",
                'title'  => $anggota->title_anggota ?? "",
                'slug'  => $this->generateUniqueSlug($anggota->nama_anggota),
                'phone_number'  => $anggota->telp_anggota ?? "",
                // 'picture'  => $anggota->foto_anggota,
            ]);
        }

        // dd($data);

        // Member::insert($data);
        return 'Members had been created...';
    }

    // Method untuk membuat slug unik
    public function generateUniqueSlug($data)
    {
        $slug = Str::slug($data);

        // Cek jika slug sudah ada
        $count = Member::where('slug', 'LIKE', "{$slug}%")->count();

        // Jika sudah ada, tambahkan angka di belakang
        return $count ? "{$slug}-{$count}" : $slug;
    }


    public function create_keahlian_for_anggota()
    {
        // Fetch all records from the anggota table
        $anggotas = DB::table('anggotas')->get();

        foreach ($anggotas as $anggota) {
            $updated = DB::table('anggota_keahlians')
                ->where('expertise_id', $anggota->keahlian_id)
                ->update(['user_id' => $anggota->user_id]);
        }

        return 'Users have been created successfully!';
    }

    public function add_keahlian_for_anggota()
    {
        // Fetch all records from the anggota table
        $anggotas = DB::table('anggotas')->get();

        foreach ($anggotas as $anggota) {
            $updated = DB::table('anggota_keahlians')
                ->where('user_id', $anggota->user_id)
                ->update(['anggota_id' => $anggota->id]);
        }

        return 'Users have been created successfully!';
    }
}

// MIGRASI TABEL DARI SAFENETWORK KE MEMBERSHIP

// 1. tambahkan kolom user_id (BIGINT) pada tabel anggota_keahlians
// 2. buat tabel anggota_sosmeds
// 3. tambahkan kolom role_name (enum: admin, member, guest)
// 4. tambahkan kolom baru ke tabel anggotas:
//          foto_anggota
//          pekerjaan_anggota
//          prefix_anggota
//          suffix_anggota
//          alamat_anggota
//          website_anggota
//          bio_anggota
//          department_anggota
//          is_approved
//     note:    kolom affiliation_anggota digunakan  sebagai university name.
//              kolom department_anggota digunakan sebagai nama fakultas atau departement
// 


// Route berikut digunakan untuk membuat user berdasarkan tabel anggota.
// Syarat: ditabel anggotas dibuat dulu kolom $user_id
// Kode dijalankan saat tabel user masih kosong dari anggota lain selain user yg berfungsi sebagai admin.
// Batam 8 Jan 2025 


// setting up akun secara umum:
//  1.  logo harus disimpan di folder public/images/logo.png. nama file harus logo.png
//      ukuran logo?


//  status anggota ada 4 sbb:
//      1.  null -> berarti sebagai user, tapi tidak menjadi anggota
//      2.  profile_incomplete -> anggota yang sudak mengajukan aplikasi sebagai anggota
//          dengan klik tombol 
