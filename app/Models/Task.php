<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'done_at',
        'due_date',
        'pinned',
        'user_id',
        'category_id'
    ];

    protected $casts = [
        'done_at' => 'datetime',
        'due_date' => 'datetime',
        'pinned' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function deadlineMissed(): ?bool
    {
        return filled($this->due_date) ? empty($this->done_at) && $this->due_date->isPast() : null;
    }
}
