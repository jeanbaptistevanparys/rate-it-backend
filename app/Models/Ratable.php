<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratable extends Model
{
    use HasFactory;

    function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}