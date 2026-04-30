<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use OpenAI\Factory;

class OpenAiService
{
    /**
     * Create a new class instance.
     */
    public function generatePromptFromImage(UploadedFile $image): string
    {
        $imageData = base64_encode(file_get_contents($image->getPathname()));
        $mimeType = $image->getMimeType();

        $client = (new Factory())
            ->withApiKey(config('services.openai.key'))
            ->make();

        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Analyze this image and generate a detailed descriptive prompt for AI image generation. Include style, lighting, composition, colors, and preserve aspect ratio.'
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:' . $mimeType . ';base64,' . $imageData,
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        return $response['choices'][0]['message']['content'] ?? '';
    }
}
