<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Quiz */
class QuizResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name' => $this->name,
            'level' => $this->level,
            'description' => $this->description,
            'category' => $this->category,
            'questions' => $this->questions,
            'tags' => $this->tags,
        ];
    }
}
