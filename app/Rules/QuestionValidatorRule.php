<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class QuestionValidatorRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        foreach ($value as $question) {
            if (! is_object($question) || empty($question->description) || empty($question->answer)) {
                return false;
            }

            if (! is_array($question->options) || empty($question->options)) {
                return false;
            }

            foreach ($question->options as $option) {
                if (! is_object($option) || count(get_object_vars($option)) !== 1) {
                    return false;
                }
                $keys = array_keys(get_object_vars($option));
                $key = $keys[0];
                $value = $option->$key;

                if (! is_string($key) || empty($value)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Chaque question doit être un objet avec des attributs valides.';
    }
}
