<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected mixed $apiKey;
    protected array $defaultHeaders;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->defaultHeaders = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . "{$this->apiKey}",
            'Cache-Control' => 'no-cache',
            'Accept' => 'application/json',
            'Connection' => 'keep-alive',
        ];

    }

    public function generateQuizOnCategory($prompt)
    {
        $body = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ]
                    ]
                ]
            ],
            "generationConfig" => [
                "response_mime_type" => "application/json",
                "responseSchema" => [
                    "type" => "OBJECT",
                    "properties" => [
                        "questions" => [
                            "type" => "ARRAY",
                            "items" => [
                                "type" => "OBJECT",
                                "properties" => [
                                    "question" => [
                                        "type" => "STRING",
                                    ],
                                    "options" => [
                                        "type" => "ARRAY",
                                        "items" => [
                                            "type" => "STRING",
                                        ]
                                    ],
                                    "answer" => [
                                        "type" => "STRING",
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "required" => ["questions"]
                ],
            ]
        ];
        try {
            $response = Http::withHeaders($this->defaultHeaders)
                ->withQueryParameters(['key' => $this->apiKey])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent', $body);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (ConnectionException $e) {
            return $e->getMessage();
        }
        return null;
    }
}
