<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quiz_id' => "required|exists:quizzes,_id",
            'description' => "required|string",
            'options' => "required|array",
            'answer' => "required|string|max:255",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
