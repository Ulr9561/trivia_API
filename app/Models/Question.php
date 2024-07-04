<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'options',
        'answer',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
