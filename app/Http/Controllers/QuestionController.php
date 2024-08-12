<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionController extends Controller
{
    public function index(): JsonResponse|AnonymousResourceCollection
    {
        try {
            return QuestionResource::collection(Question::all());
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching quizzes.' . $e->getMessage(),], 500);
        }
    }

    public function store(QuestionRequest $request): JsonResponse|QuestionResource
    {
        try {
            $data = $request->validated();
            $question = (new Question)->create($data);

            return new QuestionResource($question);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the question.'. $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse|QuestionResource
    {
        try {
            $question = (new Question)->findOrFail($id);
            return new QuestionResource($question);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Question not found'], 404);
        } catch (Exception $e) {
            $errorMessage = 'An error occurred while fetching the question.';
            $statusCode = 500;

            if ($e->getCode() == 404) {
                $errorMessage = 'Question not found';
                $statusCode = 404;
            }

            return response()->json(['error' => $errorMessage], $statusCode);
        }
    }

    public function update(QuestionRequest $request, string $id): JsonResponse|QuestionResource
    {
        try {
            $question = (new Question)->findOrFail($id);
            Gate::authorize('update', $question);
            $data = $request->validated();

            $question->update($data);
            return new QuestionResource($question);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (AccessDeniedHttpException) {
            return response()->json(['error' => 'This action is unauthorized.'], 403);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Question not found'], 404);
        } catch (Exception) {
            return response()->json(['error' => 'An error occurred while updating the question.'], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $question = (new Question)->findOrFail($id);
            Gate::authorize('delete', $question);
            $question->delete();

            return response()->json(['message' => 'Quiz deleted successfully'], 204);
        } catch (AccessDeniedHttpException) {
            return response()->json(['error' => 'This action is unauthorized.'], 403);
        } catch (NotFoundHttpException) {
            return response()->json(['error' => 'Quiz not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the question.' . $e->getMessage(), 'user_id' => Auth::user()->getAuthIdentifier() ], 500);
        }
    }
}
