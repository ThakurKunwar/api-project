<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->baseUrl = "https://generativelanguage.googleapis.com/v1beta";
    }

    public function generatePromptFromImage(string $base64Image): string
    {
        $response = Http::post("{$this->baseUrl}/models/gemini-2.0-flash:generateContent?key={$this->apiKey}", [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => "Describe this image and generate a prompt for it"
                        ],
                        [
                            "inline_data" => [
                                "mime_type" => "image/jpeg",
                                "data" => $base64Image
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception("Gemini API failed: " . $response->body());
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text']
            ?? throw new \Exception("Unexpected Gemini response");
    }
}
