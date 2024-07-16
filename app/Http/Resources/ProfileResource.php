<?php

namespace App\Http\Resources;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Profile */
class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'solved_quizzes' => $this->solved_quizzes,
            'score' => $this->score,
            'achievements' => $this->achievements,
            'rank' => $this->rank,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
