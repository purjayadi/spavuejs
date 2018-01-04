<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Halaman extends Model
{
    //
    protected $fillable = ['judul', 'slug', 'deskripsi', 'meta'];
}
