<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PublicInformation extends Model
{
    protected $table = 'public_informations';

    protected $fillable = [
        'ktp',
        'surat_pertanggungjawaban',
        'surat_permintaan',
        'nama',
        'alamat',
        'pekerjaan',
        'npwp',
        'no_hp',
        'email',
        'rincian_informasi',
        'tujuan_informasi',
        'cara_memperoleh',
        'cara_salinan',
    ];}
