<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ratable extends Model
{
    use HasFactory;

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'name');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'ratable_id', 'id');
    }

    public function ratableLanguage()
    {
        return $this->hasMany(RatableLanguage::class, "ratable_id", "id");
    }
}
