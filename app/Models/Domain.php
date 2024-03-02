<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function niche ()
    {
        return $this->belongsTo(Niche::class);
    }

    public function subNiche ()
    {
        return $this->belongsTo(SubNiche::class);
    }
}
