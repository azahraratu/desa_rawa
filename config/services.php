<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Gateway WhatsApp Fonnte - https://fonnte.com
    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
        'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com/send'),
        'enabled' => env('FONNTE_ENABLED', true),
    ],

    // Identitas Desa untuk kop surat & layout
    'desa' => [
        'nama' => env('DESA_NAMA', 'Desa Rawa'),
        'kecamatan' => env('DESA_KECAMATAN', 'Lumbung'),
        'kabupaten' => env('DESA_KABUPATEN', 'Ciamis'),
        'provinsi' => env('DESA_PROVINSI', 'Jawa Barat'),
        'alamat_kantor' => env('DESA_ALAMAT_KANTOR', 'Jl. Raya Desa Rawa'),
        'kepala_desa' => env('DESA_KEPALA_DESA', 'Kepala Desa'),
        'foto_kepala_desa' => env('DESA_FOTO_KEPALA_DESA', 'img/avatar-placeholder.png'),
        'sejarah' => env('DESA_SEJARAH', 'Desa Rawa adalah salah satu desa di Kecamatan Lumbung, Kabupaten Ciamis, Jawa Barat. Sejarah dan profil lengkap desa dapat diperbarui oleh Admin melalui file konfigurasi.'),
        'visi' => env('DESA_VISI', 'Mewujudkan Desa Rawa yang maju, mandiri, dan sejahtera berlandaskan semangat Ciamis Sabilulungan.'),
        'misi' => env('DESA_MISI', 'Meningkatkan pelayanan publik berbasis digital; Memberdayakan ekonomi masyarakat; Menjaga kelestarian lingkungan dan budaya lokal.'),
        // Opsional: tautan embed Google Maps kantor desa (ambil dari Google Maps > Bagikan > Sematkan peta).
        // Jika kosong, homepage otomatis membuat tautan pencarian dari field alamat_kantor di atas.
        'maps_embed_url' => env('DESA_MAPS_EMBED_URL'),
    ],

];
