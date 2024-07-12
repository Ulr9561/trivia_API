<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'solved_quizzes' => ['required', 'integer'],
            'user_id' => ['required', 'exists:users'],
            'score' => ['required', 'integer'],
            'achievments' => ['required'],
            'rank' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
