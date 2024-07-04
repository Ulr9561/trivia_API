<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return QuizResource::collection(Quiz::all());
    }

    public function store(QuizRequest $request)
    {
        $data = $request->validated();

        $quiz = Quiz::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'level' => $data['level'],
            'tags' => $data['tags'],
        ]);

        foreach ($data['questions'] as $questionData) {
            $question = new Question($questionData);
            $quiz->questions()->save($question);
        }
        return response()->json([
            "success" => true,
            "data" => new QuizResource($quiz),
            "message" => $data['questions'],
        ]);
    }

    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'category' => ['required'],
        ]);

        $quiz->update($data);

        return new QuizResource($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return response()->json();
    }
}
