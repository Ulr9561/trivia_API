<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Question;
use App\Models\Quiz;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class QuizController extends Controller
{
    public function index()
    {
        try {
            return QuizResource::collection(Quiz::all());
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching quizzes.'. $e->getMessage(),], 500);
        }
    }

    public function store(QuizRequest $request)
    {
        try {
            $data = $request->validated();
            if(Quiz::where('name', $data['name'])->exists()){
                return response()->json(['error' => 'Quiz with the same name already exists.'], 409);
            }

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
                "message" => 'Quiz created successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the quiz.'. $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            return new QuizResource($quiz);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Quiz not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the quiz. '. $e->getMessage(),], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            Gate::authorize('update', $quiz);

            $data = $request->validate([
                'name' => "required|string|max:255",
                'description' => "required|string",
                'category' => "required|string|max:255",
                'level' => "required|string|max:255",
                'tags' => "required|array|max:255",
            ]);

            if(Quiz::where('name', $data['name'])->where('_id', '!=', $id)->exists()){
                return response()->json(['error' => 'Quiz with the same name already exists.'], 409);
            }

            $quiz->update($data);

            return new QuizResource($quiz);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (AuthorizationException) {
            return response()->json(['error' => 'This action is unauthorized.'], 403);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Quiz not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the quiz.' . $e->getMessage(),], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            Gate::authorize('delete', $quiz);
            $quiz->delete();

            return response()->json(['message' => 'Quiz deleted successfully'], 204);
        } catch (AccessDeniedHttpException) {
            return response()->json(['error' => 'This action is unauthorized.'], 403);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Quiz not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the quiz.'. $e->getMessage(),], 500);
        }
    }
}
