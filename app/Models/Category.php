<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'pinned'
    ];

    protected $casts = [
        'pinned' => 'boolean'
    ];

    public function trashed(): bool
    {
        return filled($this->deleted_at);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
