<?php

namespace App\Http\Requests;

use App\Http\Enums\CategoryEnum;
use App\Rules\QuestionValidatorRule;
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
            "description" => "required|min:5|max:150",
            "category" => "required", Rule::in(
                [
                    CategoryEnum::ARTS,
                    CategoryEnum::GAMING,
                    CategoryEnum::GENERAL,
                    CategoryEnum::SCIENCE,
                    CategoryEnum::SPORTS,
                    CategoryEnum::ANIME,
                    CategoryEnum::INFORMATIQUE,
                    CategoryEnum::DEVINETTES,
                ]
            ),
            "questions" => "required", new QuestionValidatorRule(),
            "level" => "required", Rule::in(
                [
                    "Débutant",
                    "Amateur",
                    "Expert",
                    "Légende"
                ]
            ),
            "tags" => "required|array"
        ];
    }
}
