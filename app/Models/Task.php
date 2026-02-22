<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'inProgress';
    public const STATUS_DONE = 'done';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'task_id');
    }
}
