<?php

namespace App\Http\Requests;

use App\Http\Enums\CategoryEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|min:5|max:150",
            "duration" => "required|integer",
            "category" => ["required", Rule::in([
                CategoryEnum::ARTS,
                CategoryEnum::GAMING,
                CategoryEnum::GENERAL,
                CategoryEnum::SCIENCE,
                CategoryEnum::SPORTS,
                CategoryEnum::ANIME,
                CategoryEnum::INFORMATIQUE,
                CategoryEnum::DEVINETTES,
            ])],
            "questions" => "required|array",
            "questions.*.description" => "required|string|min:5|max:255",
            "questions.*.options" => "required|array|min:2",
            "questions.*.options.*" => "required|string|distinct",
            "questions.*.answer" => "required|string|in_array:questions.*.options.*",
            "level" => ["required", Rule::in([
                "Debutant",
                "Amateur",
                "Expert",
                "Legende"
            ])],
            "tags" => "required|array"
        ];
    }
}
