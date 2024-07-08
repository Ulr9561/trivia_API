<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

/**
 * @property mixed $user
 */
class Quiz extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'quizzes';

    use HasFactory;

    protected $fillable = [
        'name', 'description', 'category', 'questions', 'level', 'tags', 'user_id'
    ];

    protected $casts = [
        'questions' => 'array',
        'tags' => 'array',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
