<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = ['titulo', 'autor', 'editorial', 'paginas','portada','isbn'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


