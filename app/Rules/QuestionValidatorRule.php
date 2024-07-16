<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class QuestionValidatorRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        foreach ($value as $question) {
            if (!is_object($question) || empty($question->description) || empty($question->answer)) {
                return false;
            }

            if (!is_array($question->options) || count($question->options) < 3) {
                return false;
            }

            $answerPresent = false;

            foreach ($question->options as $option) {
                if (!is_string($option) || empty($option)) {
                    return false;
                }
                if ($option === $question->answer) {
                    $answerPresent = true;
                }
            }

            if (!$answerPresent) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Chaque question doit être un objet avec une description, une réponse et au moins 3 options valides. La réponse doit être présente parmi les options.';
    }
}
