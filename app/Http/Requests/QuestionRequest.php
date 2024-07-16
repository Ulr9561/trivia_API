<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quiz_id' => "required|exists:quizzes,_id",
            'description' => "required|string|min:5|max:255",
            'options' => "required|array|min:3",
            "options.*" => "required|string|distinct",
            "answer" => "required|string|in_array:options.*",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
