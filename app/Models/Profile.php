<?php

namespace App\Models;



use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'solved_quizzes',
        'user_id',
        'score',
        'achievments',
        'rank',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'achievments' => 'array',
        ];
    }
}
