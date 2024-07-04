<?php

namespace Database\Seeders;

use App\Http\Enums\AnimeEnum;
use App\Http\Enums\ArtsEnum;
use App\Http\Enums\CategoryEnum;
use App\Http\Enums\GamingEnum;
use App\Http\Enums\InformatiqueEnum;
use App\Http\Enums\ScienceEnum;
use App\Http\Enums\SportsEnum;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\GeminiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Random\RandomException;

class QuizSeeder extends Seeder
{
    /**
     * Seed the quiz's table.
     * @throws RandomException
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $mainCategory = $this->getRandomMainCategory();
            list($subCategory1, $subCategory2) = $this->getRandomSubCategories($mainCategory);
            $difficulty = $this->getRandomDifficulty();
            $prompt = $this->generatePrompt($mainCategory, $subCategory1, $subCategory2, $difficulty);
            $geminiService = new GeminiService();
            $quizData = $geminiService->generateQuizOnCategory($prompt);

            if (isset($quizData['questions'])) {
                $quiz = Quiz::create([
                    'name' => "Quiz $i - $mainCategory",
                    'description' => "Quiz sur $mainCategory, sous-catégories $subCategory1 et $subCategory2, niveau $difficulty",
                    'category' => $mainCategory,
                ]);

                foreach ($quizData['questions'] as $questionData) {
                    Log::info('Quiz data response', ['response' => $questionData]);
                    $question = new Question([
                        'question' => $questionData['question'],
                        'options' => $questionData['options'],
                        'answer' => $questionData['answer'],
                    ]);
                    $quiz->questions()->save($question);
                }
            } else {
                Log::error('Quiz data is null or does not contain questions', ['quizData' => $quizData, 'prompt' => $prompt]);
            }
        }
    }

    /**
     * Sélectionne aléatoirement une catégorie principale parmi celles définies.
     */
    private function getRandomMainCategory(): string
    {
        $categories = [
            CategoryEnum::GENERAL,
            CategoryEnum::SCIENCE,
            CategoryEnum::SPORTS,
            CategoryEnum::GAMING,
            CategoryEnum::ARTS,
            CategoryEnum::ANIME,
            CategoryEnum::INFORMATIQUE,
            CategoryEnum::DEVINETTES
        ];

        return $categories[array_rand($categories)];
    }

    private function getRandomSubCategories(string $mainCategory): array
    {
        $subCategories = match ($mainCategory) {
            CategoryEnum::GENERAL => ['générale', 'technique', 'commun'],
            CategoryEnum::SCIENCE => [
                ScienceEnum::BIOLOGIE,
                ScienceEnum::MATH,
                ScienceEnum::CHIMIE,
                ScienceEnum::PHYSIQUE,
            ],
            CategoryEnum::SPORTS => [
                SportsEnum::ATHLETISME,
                SportsEnum::BASEBALL,
                SportsEnum::BASKETBALL,
                SportsEnum::EXTREME,
                SportsEnum::TENNIS,
                SportsEnum::FOOTBALL
            ],
            CategoryEnum::ANIME => [
                AnimeEnum::ACTION,
                AnimeEnum::AMOUR,
                AnimeEnum::SF,
                AnimeEnum::COMEDIE,
                AnimeEnum::SHOJO,
                AnimeEnum::SHONEN,
                AnimeEnum::SLICEOFLIFE,
                AnimeEnum::SPORT
            ],
            CategoryEnum::INFORMATIQUE => [
                InformatiqueEnum::GL,
                InformatiqueEnum::SI,
                InformatiqueEnum::WEB,
                InformatiqueEnum::PROGRAMMATION,
                InformatiqueEnum::IA
            ],
            CategoryEnum::GAMING =>  [
                GamingEnum::SPORT,
                GamingEnum::ACTION,
                GamingEnum::FPS,
                GamingEnum::MMO,
                GamingEnum::RPG,
                GamingEnum::RTS,
                GamingEnum::SIMULATION,
            ],
            CategoryEnum::ARTS => [
                ArtsEnum::LITTERATURE,
                ArtsEnum::PEINTURE,
                ArtsEnum::SCULPTURE,
                ArtsEnum::STREET,
                ArtsEnum::THEATRE
            ],
            default => ['error'],
        };

        if (count($subCategories) < 2) {
            return [$subCategories[0], $subCategories[0]];
        }

        shuffle($subCategories);
        return array_slice($subCategories, 0, 2);
    }

    /**
     * Sélectionne aléatoirement un niveau de difficulté pour le quiz.
     */
    private function getRandomDifficulty(): string
    {
        $difficulties = ['Débutant', 'Amateur', 'Professionnel', 'Légende'];

        return $difficulties[array_rand($difficulties)];
    }

    /**
     * Génère le prompt en fonction des sélections aléatoires.
     * @throws RandomException
     */
    private function generatePrompt(string $mainCategory, string $subCategory1, string $subCategory2, string $difficulty): string
    {
        return "Un quiz de " . random_int(10, 30) . " questions, des questions aléatoires (ne me genere pas la même chose deux fois) sur $mainCategory, sous-catégories $subCategory1 et $subCategory2, niveau $difficulty";
    }
}
