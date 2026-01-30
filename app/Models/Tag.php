<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function knowledge(): BelongsToMany
    {
        return $this->belongsToMany(Knowledge::class, 'knowledge_tag');
    }
}
