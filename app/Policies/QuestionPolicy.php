<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /*public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Question $question): bool
    {
    }*/

    public function update(User $user, Question $question): bool
    {
        $quiz = Quiz::find($question->quiz_id);
        return $quiz->user_id == $user->id;
    }

    public function delete(User $user, Question $question): bool
    {
        $quiz = Quiz::find($question->quiz_id);
        return $quiz->user_id == $user->id;
    }

    public function restore(User $user, Question $question): bool
    {
        $quiz = Quiz::find($question->quiz_id);
        return $quiz->user_id == $user->id;
    }

    public function forceDelete(User $user, Question $question): bool
    {
        $quiz = Quiz::find($question->quiz_id);
        return $quiz->user_id == $user->id;
    }
}
