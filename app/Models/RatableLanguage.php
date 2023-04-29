<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatableLanguage extends Model
{
    use HasFactory;

    public function ratable()
    {
        return $this->belongsTo(Ratable::class, "ratable_id", "id");
    }
}
